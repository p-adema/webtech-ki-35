<?php

require_once 'tag_actions.php';
require_once 'api_resolve.php';
require_once 'admin_controls.php';
api_require_admin();

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

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

if ($action === 'ban') {
    if (!admin_set_user_banned($target_uid, true)) {
        api_fail('Error banning user');
    }

    api_succeed('Successfully banned user!');
}

if (!admin_set_user_banned($target_uid, false)) {
    api_fail('Error unbanning user');
}

api_succeed('Successfully unbanned user!');
