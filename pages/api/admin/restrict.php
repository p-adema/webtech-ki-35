<?php

require_once 'tag_actions.php';
require_once 'api_resolve.php';
require_once 'admin_controls.php';
api_require_admin();

$errors = [
    'item_tag' => [],
    'action' => [],
    'submit' => []
];
$valid = true;

$target_tag = $_POST['item_tag'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($action)) {
    $errors['action'][] = 'Please provide an action';
    $valid = false;
} elseif ($action !== 'restrict' and $action !== 'unrestrict') {
    $errors['action'][] = 'Please provide a valid action';
    $valid = false;
}

$target_id = item_id_from_tag($target_tag);
if ($target_id === false) {
    $errors['item_tag'][] = 'Invalid item tag';
    api_fail('Invalid item tag', $errors);
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

if ($action === 'restrict') {
    if (!admin_restrict_item($target_id)) {
        api_fail('Error restricting item');
    }

    api_succeed('Successfully restricted item!');
}

if (!admin_restrict_item($target_id, false)) {
    api_fail('Error unrestricting item');
}

api_succeed('Successfully unrestricted item!');
