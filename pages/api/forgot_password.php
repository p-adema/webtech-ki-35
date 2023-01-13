<?php
require "api_resolve.php";
$errors = [
    'email' => [],
    'submit' => []
    ];
$valid = true;

$email = $_POST['email'];

if (empty($email)) {
    $errors['email'][] = 'Email is required.';
    $valid = false;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email'][] = 'Invalid email formta.';
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
    $valid = false;
}

if (isset($pdo_write)) {
    $sql = 'SELECT (id) FROM db.users WHERE (email = :email);';
    $data = ['email' => htmlspecialchars($email)];

    $sql_prep = $pdo_write->prepare($sql);

    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal server error, try again later';
        $valid = false;
    }
    $duplicate = $sql_prep->fetch();
    if (empty($duplicate)) {
        $errors['email'][] = 'This email is not in use';
        $valid = false;
    }
}

if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}

api_succeed("An E-mail has been sent to $email", $errors);