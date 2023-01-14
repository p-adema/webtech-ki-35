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








if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}

api_succeed("An E-mail has been sent to $email", $errors);
