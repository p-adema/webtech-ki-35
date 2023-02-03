<?php

/*
 * Expects a POST request with:
 *      type : < 'item' or 'replies' >
 *      on : < item tag > or < comment tag >
 */

require "api_resolve.php";
require_once 'pdo_read.php';
require "comments_components.php";

$errors = [
    'type' => [],
    'on' => []
];
$valid = true;
$type = $_POST['type'] ?? '';
$on = $_POST['on'] ?? '';

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

$response = [];

if ($type === 'item') {
    $id = item_id_given_tag($on);

    if ($id === false) {
        $errors['on'][] = 'Invalid item tag';
        api_fail('Invalid item tag', $errors);
    }
    $comments = get_comments_item($id);
    $rendered = [];

    foreach ($comments as $comment) {
        $rendered[] = render_comment($comment);
    }

    $response['html'] = join(PHP_EOL, $rendered);

    api_succeed('Comments retrieved', $errors, $response);
}

$comments = get_replies_comment($on);
$rendered = [];

foreach ($comments as $comment) {
    $rendered[] = render_comment($comment);
}

$response['html'] = join(PHP_EOL, $rendered);

api_succeed('Comments retrieved', $errors, $response);
