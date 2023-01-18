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
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    $valid = false;
}
ensure_session();
if (!isset($_SESSION['url_tag']) or $_SESSION['url_tag_type'] !== 'verify') {
    api_fail('Invalid referal link', ['submit' => 'You don\'t seem to have come from a valid link']);
}
$tag = $_SESSION['url_tag'];

if (isset($pdo_write)) {
    $sql = 'SELECT (user_id) FROM db.emails_pending WHERE (url_tag = :tag) AND (type = \'verify\');';
    $data = ['tag' => $tag];

    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute($data);
    $user_id_fetch = $sql_prep->fetch();
    $user_id = $user_id_fetch['user_id'];

    $sql = 'UPDATE db.users u SET u.verified = 1 WHERE u.id = :user_id;';
    $data = ['user_id' => $user_id];

    $sql_prep = $pdo_write->prepare($sql);
    if (!$sql_prep->execute($data)) {
        api_fail("Couldn't change password", ['submit' => 'Couldn\'t change password']);
    }
    $sql = 'DELETE FROM db.emails_pending p WHERE p.url_tag = :tag;';
    $data = ['tag' => $tag];

    $sql_prep = $pdo_write->prepare($sql);

    if (!$sql_prep->execute($data)) {
        api_fail("Couldn't reset link", ['submit' => 'Couldn\'t reset link']);
    }
    api_succeed('Account verified!');
}
