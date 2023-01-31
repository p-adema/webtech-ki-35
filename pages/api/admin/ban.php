<?php

require_once 'tag_actions.php';
require_once 'api_resolve.php';
require_once 'admin_controls.php';
api_ensure_admin();

$errors = [
    'user' => [],
    'action' => [],
    'submit' => []
];
$valid = true;

$target_name = $_POST['user'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($action)) {
    $errors['action'][] = 'Please provide an action';
    $valid = false;
} elseif ($action !== 'ban' and $action !== 'unban') {
    $errors['action'][] = 'Please provide a valid action';
    $valid = false;
}

$target_uid = user_id_from_name($target_name);
if ($target_uid === false) {
    $valid = false;
    $errors['user'][] = 'Invalid username';
}

if ($action === 'ban') {
    if (!admin_ban_user($target_uid)) {
        $errors['submit'][] = 'Error banning user';
        api_fail('Error banning user', $errors);
    }

    api_succeed('Successfully banned user!', $errors);
}

if (!admin_unban_user($target_uid)) {
    $errors['submit'][] = 'Error unbanning user';
    api_fail('Error unbanning user', $errors);
}

api_succeed('Successfully unbanned user!', $errors);
