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

$errors = [
    'name' => [],
    'email' => [],
    'password' => [],
    'full_name' => [],
    'submit' => []
];
$valid = true;

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$full_name = $_POST['full_name'];

if (empty($name)) {
    $errors['name'][] = 'Username is required.';
    $valid = false;
} else if (strlen($name) < 5) {
    $errors['name'][] = "Username must be at least 5 characters.";
    $valid = false;
} else if (strlen(htmlspecialchars($name)) > 128) {
    $errors['name'][] = "Username must be shorter (max 128 standard characters).";
    $valid = false;
} else if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
    $errors['name'][] = "Username should not be an email.";
    $valid = false;
}
# Use a read/write, because we might need to insert a user later
require "pdo_write.php";
try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    $valid = false;
}

if (isset($pdo_write)) {
    $sql = 'SELECT (id) FROM db.users WHERE (name = :name);';
    $data = ['name' => htmlspecialchars($name)];

    $sql_prep = $pdo_write->prepare($sql);

    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal server error, try again later';
        $valid = false;
    }
    $duplicate = $sql_prep->fetch();
    if (!empty($duplicate)) {
        $errors['name'][] = 'This username is already in use';
        $valid = false;
    }
}

if (empty($email)) {
    $errors['email'][] = 'Email is required.';
    $valid = false;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'][] = "Invalid email format.";
    $valid = false;
} else if (strlen(htmlspecialchars($email)) > 128) {
    $errors['email'][] = "Email must be shorter (max 128 standard characters).";
    $valid = false;
}

if (isset($pdo_write)) {
    /** @noinspection DuplicatedCode */
    $sql = 'SELECT (id) FROM db.users WHERE (email = :email);';
    $data = ['email' => htmlspecialchars($email)];

    $sql_prep = $pdo_write->prepare($sql);

    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal server error, try again later';
        $valid = false;
    }
    $duplicate = $sql_prep->fetch();
    if (!empty($duplicate)) {
        $errors['email'][] = 'This email is already in use';
        $valid = false;
    }
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

$sql = 'INSERT INTO db.users (name, email, password, full_name, membership)
VALUES (:name, :email, :password, :full_name, \'none\');';

/** @noinspection PhpUndefinedVariableInspection : If the connection failed, we'd already have exited */
$sql_prep = $pdo_write->prepare($sql);
if (!$sql_prep->execute($data)) {
    $errors['submit'][] = 'Internal server error';
    api_fail('Internal server error, try again later', $errors);
}

$sql = 'SELECT (id) FROM db.users WHERE (email = :email);';
$data = ['email' => htmlspecialchars($email)];
$sql_prep = $pdo_write->prepare($sql);
if (!$sql_prep->execute($data)) {
    $errors['submit'][] = 'Internal server error';
    $valid = false;
}
$user_id = $sql_prep->fetch()['id'];
$url_tag = tag_create();

$sql = 'INSERT INTO db.emails_pending (type, url_tag, user_id, request_time)
        VALUES (\'verify\', :tag, :user_id, DEFAULT);';

$data = [
    'tag' => htmlspecialchars("$url_tag"),
    'user_id' => htmlspecialchars("$user_id")
];

$sql_prep = $pdo_write->prepare($sql);
$sql_prep->execute($data);

$link = '/auth/verify.php?tag=' . $url_tag;
api_succeed("An E-mail to activate your account has been sent to $email <br>  <a href='$link'>link</a>", $errors);
