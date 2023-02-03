<?php

require 'video_functionality.php';
require 'comments_components.php';
require_once 'api_resolve.php';

api_require_login();

$valid = true;

$errors = [
    'message' => [],
    'item_tag' => []
];


$item_tag = $_POST['item_tag'] ?? '';
$comment = $_POST['message'] ?? '';

if (empty($comment)) {
    $valid = false;
    $errors['message'][] = 'Please type a message.';
}

if (empty($item_tag)) {
    $valid = false;
    $errors['item_tag'][] = 'Please find a page with a correct video tag.';
}

if (!$valid) {
    api_fail('Please use valid parameters', $errors);
}

else {
    $comment_tag = add_comment($comment, $item_tag);
    $response = [
        'html' => render_comment(get_comment_info(comment_id_from_tag($comment_tag), 0))
    ];
    api_succeed('Successfully added comment!', data: $response);
}
