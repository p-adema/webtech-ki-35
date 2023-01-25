<?php

require 'video_functionality.php';
require 'comments_components.php';
require_once 'api_resolve.php';

$valid = true;

$errors = [
    'message' => [],
    'video_tag' => []
];


$video_tag = $_POST['video_tag'];
$comment = $_POST['message'];

if (empty($comment)) {
    $valid = false;
    $errors['message'][] = 'Please type a message.';
}

if (empty($video_tag)) {
    $valid = false;
    $errors['video_tag'][] = 'Please find a page with a correct video tag.';
}

if (!$valid) {
    api_fail('Please use a correct and working link.', $errors);
}

else {
    $comment_tag = add_new_comment($comment, $video_tag, null);
    require_once 'pdo_read.php';
    $data = [
        'html' => render_comment(get_comment_info(get_comment_id($comment_tag), false),0)
    ];
    api_succeed('Successfully added comment!', $errors, $data);
}