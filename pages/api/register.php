<?php
/*
 * Expects a POST request with:
 *      name        :   < username >
 *      email       :   < email >
 *      password    :   < password >
 *      full_name   :   < full name >
 */

require "api_resolve.php";
require 'tag_actions.php';
require "mail.php";
require "pdo_write.php";
require "check_acc_fields.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$re_pwd = $_POST['re_pwd'];
$full_name = $_POST['full_name'];

/** @noinspection DuplicatedCode */
try {
    $pdo_write = new_pdo_write(err_fatal: false);
    $errors = check_acc_fields($pdo_write, $name, $email, $password, $re_pwd, $full_name);
    $errors['submit'] = [];
    $valid = check_acc_err($errors);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    api_fail('Internal database error', $errors);
}

if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}

$data = [
    'name' => htmlspecialchars($_POST['name']),
    'email' => htmlspecialchars($_POST['email']),
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'full_name' => htmlspecialchars($_POST['full_name'])
];

$sql_user = 'INSERT INTO db.users (name, email, password, full_name)
VALUES (:name, :email, :password, :full_name);';

$sql_prep = $pdo_write->prepare($sql_user);
if (!$sql_prep->execute($data)) {
    $errors['submit'][] = 'Internal server error';
    api_fail('Internal server error, try again later', $errors);
}

$url_tag = tag_create();

$sql_email = 'INSERT INTO db.emails_pending (type, url_tag, user_id)
        SELECT \'verify\', :tag, id FROM db.users WHERE name = :name';

$data = [
    'tag' => htmlspecialchars($url_tag),
    'name' => htmlspecialchars($name)
];

$sql_prep = $pdo_write->prepare($sql_email);
$sql_prep->execute($data);

$link = '/auth/verify/' . $url_tag;
if (mail_acc_verify($link, $email)) { #TODO PRODUCTION: remove link
    api_succeed("An E-mail to activate your account has been sent to $email <br />  <a href='$link'>dev</a>", $errors);
} else {
    $errors['submit'][] = "Verification email couldn't be sent  <br />  <a href='$link'>dev</a>";
    api_fail("The email to verify your account couldn't be sent", $errors);
}
