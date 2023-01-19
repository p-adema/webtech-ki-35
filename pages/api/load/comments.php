<?php

/*
 * Expects a POST request with:
 *      type : < 'item' or 'replies' >
 *      on : < item tag > or < comment tag >
 */

require "api_resolve.php";
require "pdo_read.php";
require "comments.php";

$errors = [
    'type' => [],
    'on' => []
];
$valid = true;
$type = $_POST['type'];
$on = $_POST['on'];

if (empty($type)) {
    $errors['type'][] = 'Please provide the comment source type';
    $valid = false;
} elseif ($type !== 'item' and $type !== 'replies') {
    $errors['type'][] = 'Please provide a valid source type';
    $valid = false;
}

if (empty($on)) {
    $errors['on'][] = 'Please provide the comment source tag or id';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields', $errors);
}

$pdo_read = new_pdo_read();
$response = [
    'html' => []
];

if ($type === 'item') {
    $id = get_id($on, $pdo_read);

    if ($id === false) {
        $errors['on'][] = 'Invalid item tag';
        api_fail('Invalid item tag', $errors);
    }
    $comments = get_comments_item($id, $pdo_read);

    foreach ($comments as $comment) {
        $response['html'][] = render_comment($comment);
    }

    api_succeed('Comments retrieved', $errors, $response);
}
