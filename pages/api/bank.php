<?php

require 'api_resolve.php';
require 'bank_functionality.php';

ensure_session();

$errors = [
    'tag' => [],
    'type' => []
];

$valid = true;

$tag = $_POST['tag'];
$type = $_POST['type'];

if (empty($tag)) {
    $valid = false;
    $errors['tag'][] = 'Please provide a valid tag';
}

if (empty($type)) {
    $valid = false;
    $errors['type'][] = 'Please provide a valid button type';
}

elseif ($type !== 'Confirm' and $type !== 'Deny') {
    $valid = false;
    $errors['type'][] = 'Please provide a valid button type';
}

if (!$valid) {
    api_fail('Please use a correct and working link.', $errors);
}

if ($type === 'Confirm') {
    confirm_payment($tag);
    api_succeed('Payment was successful!');
}

else {
    deny_payment($tag);
    api_succeed('Payment was successfully denied!');
}