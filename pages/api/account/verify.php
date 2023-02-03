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

$tag = $_SESSION['url_tag'];

$sql = 'CALL resolve_account(:tag)';
$prep = prepare_write($sql);

if (!$prep->execute(['tag' => $tag])) {
    api_fail("Couldn't verify account", ['submit' => "Couldn't verify account"]);
}
api_succeed('Account verified!');
