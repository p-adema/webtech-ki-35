<?php
require "api_resolve.php";

$errors = [
    'name' => [],
    'password' => [],
    'submit' => []
];
$valid = true;
$name = $_POST['name'];
$password = $_POST['password'];

if (empty($name)) {
    $errors['name'][] = 'Please fill in a username';
    $valid = false;
}

if (empty($password)) {
    $errors['password'][] = 'Please fill in a password';
    $valid = false;
}


if (!$valid) {
    api_fail($errors, 'Please fill in all fields');
}

$data = [
    'name' => htmlspecialchars($name),
];

require "pdo_read.php";
$sql = 'SELECT (password) FROM db.users WHERE (name = :name);';

try {
    $pdo_read = new_pdo_read(err_fatal: false);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    api_fail($errors, 'Internal server error (unable to connect to database)');
}
$sql_prep = $pdo_read->prepare($sql);

if (!$sql_prep->execute($data)) {
    $errors['submit'][] = 'Internal server error';
    api_fail($errors, 'Internal server error, try again later');
}
$hash = $sql_prep->fetch();
if (empty($hash) or !password_verify($password, $hash[0])) {
    $errors['submit'][] = 'Incorrect username or password';
    api_fail($errors, 'Username or password invalid');
}

api_succeed('Login successful!');
