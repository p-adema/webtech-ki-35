<?php

require 'video_functionality.php';
require 'comments_components.php';
require_once 'api_resolve.php';

api_require_login();

$valid = true;

$errors = [
    'message' => [],
    'item_tag' => [],
    'comment_tag' => []
];

$comment = $_POST['message'] ?? '';
$item_tag = $_POST['item_tag'] ?? '';
$reply_to = $_POST['comment_tag'] ?? '';

if (empty($comment)) {
    $valid = false;
    $errors['message'][] = 'Please type a message';
}

if (empty($item_tag)) {
    $valid = false;
    $errors['item_tag'][] = 'Please provide an item tag';
}

if (empty($reply_to)) {
    $errors['comment_tag'][] = 'Please provide a comment tag';
    $valid = false;
}

if (!$valid) {
    api_fail('Please provide all information', $errors);
} else {
    $comment_tag = add_comment($comment, $item_tag, $reply_to);
    if ($comment_tag === false) {
        api_fail('Invalid item or comment tag');
    }
    $response = [
        'html' => render_comment(get_comment_info(comment_id_from_tag($comment_tag), 0), true)
    ];
    api_succeed('Successfully added comment!', data: $response);
}
