<?php
require "html_page.php";

$errors = [
    'title' => [],
    'description' => [],
    'subject' => [],
    'free' => [],
    'price' => [],
    'submit' => []
];
$valid = true;

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$subject = $_POST['subject'] ?? '';
$free_raw = $_POST['free'] ?? '';
$price = $_POST['price'] ?? '';

if (empty($title)) {
    $errors['title'][] = 'Please provide a video title';
    $valid = false;
}

if (empty($description)) {
    $errors['description'][] = 'Please provide a video description';
    $valid = false;
}

if (empty($subject)) {
    $errors['subject'][] = 'Please provide a video subject';
    $valid = false;
} elseif (!in_array($subject, SUBJECTS)) {
    $errors['subject'][] = 'Please provide a valid video subject';
    $valid = false;
}

if (empty($free_raw)) {
    $free = null;
    $errors['free'][] = 'Please declare whether the video is free or paid';
    $valid = false;
} else {
    $free = $free_raw === 'yes';
}

if (empty($price)) {
    $errors['price'][] = 'Please provide a video price';
    $valid = false;
} elseif (!is_numeric($price)) {
    $errors['price'][] = 'Please provide a numerical video price';
    $valid = false;
}

if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}

require_once "pdo_write.php";


try {
    $pdo_write = new_pdo_write();
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error';
    api_fail("Couldn't connect to database", $errors);
}

$sql_add = '';
$p_add = $pdo_write->prepare($sql_add);
$d_add = [

];

if (!$p_add->execute($d_add)) {
    $errors['submit'][] = 'Internal server error';
    api_fail("Couldn't add video", $errors);
}

api_succeed('Video would have been added if this worked', $errors);
