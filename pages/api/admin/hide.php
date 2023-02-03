<?php

require_once 'tag_actions.php';
require_once 'api_resolve.php';
require_once 'admin_controls.php';
api_require_admin();

$errors = [
    'comment' => [],
    'action' => [],
    'submit' => []
];
$valid = true;

$target_name = $_POST['comment'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($action)) {
    $errors['action'][] = 'Please provide an action';
    $valid = false;
} elseif ($action !== 'hide' and $action !== 'unhide') {
    $errors['action'][] = 'Please provide a valid action';
    $valid = false;
}

$target_uid = comment_id_from_tag($target_name);
if ($target_uid === false) {
    $valid = false;
    $errors['comment'][] = 'Invalid comment tag';
}

if ($action === 'hide') {
    if (!admin_hide_comment($target_uid)) {
        api_fail('Error hiding comment');
    }

    api_succeed('Successfully hid comment!');
}

if (!admin_hide_comment($target_uid, false)) {
    api_fail('Error hiding comment');
}

api_succeed('Successfully unhid comment!');
