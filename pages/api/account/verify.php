<?php
/*
 * Expects a POST request with:
 *      no parameters
 */
require "pdo_write.php";
require "api_resolve.php";

try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    api_fail('Internal server error', ['submit' => 'Internal server error']);
}
ensure_session();
if (!isset($_SESSION['url_tag']) or $_SESSION['url_tag_type'] !== 'verify') {
    api_fail('Invalid referal link', ['submit' => 'You don\'t seem to have come from a valid link']);
}
$tag = $_SESSION['url_tag'];

if (isset($pdo_write)) {
    $sql_uid = 'SELECT (user_id) FROM db.emails_pending WHERE (url_tag = :tag) AND (type = \'verify\');';
    $d_tag = ['tag' => $tag];

    $p_uid = $pdo_write->prepare($sql_uid);
    $p_uid->execute($d_tag);
    $user_id_fetch = $p_uid->fetch();
    $user_id = $user_id_fetch['user_id'];

    $sql_verify = 'UPDATE db.users u SET u.verified = 1 WHERE u.id = :user_id;';
    $d_id = ['user_id' => $user_id];

    $p_verify = $pdo_write->prepare($sql_verify);
    if (!$p_verify->execute($d_id)) {
        api_fail("Couldn't verify account", ['submit' => 'Couldn\'t verify account']);
    }
    $sql_rm_email = 'DELETE FROM db.emails_pending p WHERE p.url_tag = :tag;';

    $p_rm_email = $pdo_write->prepare($sql_rm_email);

    $sql_balance = 'INSERT INTO db.balances (user_id, balance) VALUES (:user_id, 100.00);';
    $p_balance = $pdo_write->prepare($sql_balance);
    $p_balance->execute($d_id);

    if (!$p_rm_email->execute($d_tag)) {
        api_fail("Couldn't reset link", ['submit' => 'Couldn\'t reset link']);
    }
    api_succeed('Account verified!');
}
