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

$errors = [
    'name' => [],
    'email' => [],
    'password' => [],
    're_pwd' => [],
    'full_name' => [],
    'submit' => []
];
$valid = true;

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$re_pwd = $_POST['re_pwd'];
$full_name = $_POST['full_name'];

/** @noinspection DuplicatedCode */
try {
//      Use a read/write, because we might need to insert a user later
    $pdo_write = new_pdo_write(err_fatal: false);

//      Checks if name is valid
    $errors['name'] = check_name($name, $pdo_write);
    if (!empty($errors['name'])) {
        $valid = false;
    }

//      Checks if email is valid
    $errors['email'] = check_email($email, $pdo_write);
    if (!empty($errors['email'])) {
        $valid = false;
    }
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    $valid = false;
}

/*
Password constraints:
     *  Length >= 8
     *  Contains uppercase letter
     *  Contains lowercase letter
     *  Contains number
     *  Contains special character
 */

$errors['password'] = check_password($password);
if (!empty($errors['password'])) {
    $valid = false;
} elseif ($password !== $re_pwd) {
    $errors['re_pwd'][] = 'Passwords do not match';
    $valid = false;
}

if (strlen(htmlspecialchars($full_name)) > 128) {
    $errors['full_name'][] = "Full name must be shorter (max 128 standard characters).";
    $valid = false;
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

$sql_user = 'INSERT INTO db.users (name, email, password, full_name, membership)
VALUES (:name, :email, :password, :full_name, \'none\');';

/** @noinspection PhpUndefinedVariableInspection : If the connection failed, we'd already have exited */
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

$link = '/auth/verify?tag=' . $url_tag;
if (mail_acc_verify($link, $email)) { #TODO PRODUCTION: remove link
    api_succeed("An E-mail to activate your account has been sent to $email <br />  <a href='$link'>dev</a>", $errors);
} else {
    $errors['submit'][] = "Verification email couldn't be sent  <br />  <a href='$link'>dev</a>";
    api_fail("The email to verify your account couldn't be sent", $errors);
}
