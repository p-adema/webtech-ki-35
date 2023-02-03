<?php

require_once 'tag_actions.php';
require_once 'api_resolve.php';
require_once 'admin_controls.php';
api_require_admin();

$errors = [
    'comment_tag' => [],
    'action' => [],
    'submit' => []
];
$valid = true;

$comment_tag = $_POST['comment_tag'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($action)) {
    $errors['action'][] = 'Please provide an action';
    $valid = false;
} elseif ($action !== 'hide' and $action !== 'unhide') {
    $errors['action'][] = 'Please provide a valid action';
    $valid = false;
}

$target_cid = comment_id_from_tag($comment_tag);
if ($target_cid === false) {
    $valid = false;
    $errors['comment_tag'][] = 'Invalid comment tag';
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

if ($action === 'hide') {
    if (!admin_hide_comment($target_cid)) {
        api_fail('Error hiding comment');
    }

    api_succeed('Successfully hid comment!');
}

if (!admin_hide_comment($target_cid, false)) {
    api_fail('Error hiding comment');
}

api_succeed('Successfully unhid comment!');
