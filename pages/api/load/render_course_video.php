<?php
require "api_resolve.php";
require_once "pdo_read.php";
$errors = [
    'tag' => [],
    'count' => [],
    'submit' => []
];
$valid = true;

$tag = $_POST['tag'] ?? '';
$count = $_POST['count'] ?? '';


if (empty($tag)) {
    $errors['tag'][] = 'Please provide a tag';
    $valid = false;
}

if ($count === '') {
    $errors['count'][] = 'Please provide the amount of added videos';
    $valid = false;
} elseif (!is_numeric($count)) {
    $errors['count'][] = 'Please provide video count as a number';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

try {
    $sql = 'SELECT `name`, `tag` FROM db.videos WHERE tag = :tag';
    $prep = prepare_readonly($sql);
    $prep->execute(['tag' => $tag]);
    $video = $prep->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors['submit'][] = "Couldn't lookup tag";
    api_fail("Couldn't lookup tag", $errors);
}

if ($video === false) {
    $errors['tag'][] = 'Please provide a valid tag';
    api_fail('Invalid tag provided', $errors);
}

require_once "form_elements.php";
$response = ['html' => form_sortable_item('videos', $count, $video), 'tag' => $video['tag']];
api_succeed('Video rendered', data: $response);
