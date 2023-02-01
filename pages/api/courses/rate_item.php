<?php

require_once 'api_resolve.php';
require_once 'video_functionality.php';

api_require_login();

$errors = [
    'tag' => [],
    'star' => [],
];
$valid = true;

$uid = $_SESSION['uid'];
$tag = $_POST['tag'] ?? '';
$star = $_POST['star'] ?? '';

if (empty($tag)) {
    $errors['tag'][] = 'Please provide an item tag to rate';
    $valid = false;
}

if (empty($star)) {
    $errors['star'][] = 'Please provide your rating';
    $valid = false;
}
if (!in_array($star, [1, 2, 3, 4, 5])) {
    $errors['star'][] = 'Please provide a valid rating';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

if (!update_rating($star, $uid, $tag)) {
    api_fail('Please provide a valid item tag');
}
api_succeed('Rating changed successfully!');
