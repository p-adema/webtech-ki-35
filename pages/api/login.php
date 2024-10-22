<?php
/*
 * Expects a POST request with:
 *      name        :   < username > or < email >
 *      password    :   < password >
 */
require "api_resolve.php";

$errors = [
    'name' => [],
    'password' => [],
    'submit' => []
];
$valid = true;
$name = $_POST['name'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($name)) {
    $errors['name'][] = 'Please fill in a username';
    $valid = false;
}

if (empty($password)) {
    $errors['password'][] = 'Please fill in a password';
    $valid = false;
}


if (!$valid) {
    api_fail('Please fill in all fields', $errors);
}

$data = [
    'name' => htmlspecialchars($name),
];

if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
    $sql = 'SELECT password, verified FROM db.users WHERE (email = :name);';
} else {
    $sql = 'SELECT password, verified FROM db.users WHERE (name = :name);';
}

require_once 'pdo_read.php';

$sql_prep = prepare_readonly($sql);

if (!$sql_prep->execute($data)) {
    $errors['submit'][] = 'Internal server error';
    api_fail('Internal server error, try again later', $errors);
}
$user = $sql_prep->fetch();
if (empty($user) or !password_verify($password, $user['password'])) {
    $errors['submit'][] = 'Incorrect username or password';
    api_fail('Username or password invalid', $errors);
}
if (!$user['verified']) {
    $errors['submit'][] = 'Please verify your email first';
    api_fail('Unverified email address', $errors);
}

$success = api_login($name);
if ($success !== true) {
    $errors['submit'][] = $success;
    api_fail('Unexpected server error', $errors);
}

api_succeed('Successfully logged in!', $errors);
