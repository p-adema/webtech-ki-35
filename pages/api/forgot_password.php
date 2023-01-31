<?php
/*
 * Expects a POST request with:
 *      email : < email address >
 */
require 'api_resolve.php';
require 'tag_actions.php';
require 'mail.php';

$errors = [
    'email' => [],
    'submit' => []
];
$valid = true;

$email = $_POST['email'] ?? '';

if (empty($email)) {
    $errors['email'][] = 'Email is required.';
    $valid = false;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'][] = 'Invalid email format.';
    $valid = false;
} else if (strlen(htmlspecialchars($email)) > 128) {
    $errors['email'][] = 'Email must be shorter (max 128 standard characters).';
    $valid = false;
}

require "pdo_write.php";
try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    api_fail('Internal error', $errors);
}

if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}


$url_tag = tag_create();

$sql_email = "INSERT INTO db.emails_pending (type, url_tag, user_id)
              SELECT 'password-reset', :tag, id FROM db.users WHERE email = :email";

$data = [
    'tag' => htmlspecialchars($url_tag),
    'email' => htmlspecialchars($email)
];

$sql_prep = $pdo_write->prepare($sql_email);
$sql_prep->execute($data);

$link = '/auth/verify/' . $url_tag;

if (mail_forgot_password($link, $email)) { #TODO PRODUCTION: remove dev link
    api_succeed("An email has been sent to the account linked to $email <br>  <a href='$link'>dev</a>", $errors);
} else {
    $errors['submit'][] = "Reset email couldn't be sent  <br />  <a href='$link'>dev</a>";
    api_fail("The email to reset your password couldn't be sent", $errors);
}
