<?php
/*
 * Expects a POST request with:
 *      no parameters
 */
require "pdo_write.php";
require "api_resolve.php";

ensure_session();

if (!isset($_SESSION['url_tag']) or $_SESSION['url_tag_type'] !== 'verify') {
    api_fail('Invalid referal link', ['submit' => "You don't seem to have come from a valid link"]);
}
try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    api_fail('Internal server error', ['submit' => 'Internal server error']);
}

$tag = $_SESSION['url_tag'];

$sql = 'CALL resolve_account(:tag)';
$prep = $pdo_write->prepare($sql);

if (!$prep->execute(['tag' => $tag])) {
    api_fail("Couldn't verify account", ['submit' => "Couldn't verify account"]);
}
api_succeed('Account verified!');
