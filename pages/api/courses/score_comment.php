<?php

require 'comments_components.php';

require_once 'api_resolve.php';

api_require_login();

$errors = [
    'score' => [],
    'comment_id' => [],
    'submit' => []
];
$valid = true;

$uid = $_SESSION['uid'];
$score = $_POST['rating'] ?? '';
$comment_id = $_POST['comment'] ?? '';

if (empty($score)) {
    $errors['score'][] = 'Please provide a score';
    $valid = false;
}
if (!in_array($score, [-1, 0, 1])) {
    $errors['score'][] = 'Please provide a valid score';
    $valid = false;
}

if (empty($comment_id)) {
    $errors['comment_id'][] = 'Please provide a comment ID';
    $valid = false;
}


if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

change_comment_score($score, $comment_id, $uid);
api_succeed('Comment score has been changed!');
