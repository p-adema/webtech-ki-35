<?php

require_once 'tag_actions.php';
require_once 'api_resolve.php';
require_once 'admin_controls.php';

$errors = [
    'user' => [],
    'item_tag' => [],
    'submit' => []
];
$valid = true;

$reciever_username = $_POST['user'] ?? '';
$item_tag = $_POST['item_tag'] ?? '';

ensure_session();
if (!$_SESSION['auth'] or !is_admin($_SESSION['uid'])) {
    $errors['submit'][] = 'Insufficient privileges';
    api_fail('Insufficient privileges', $errors);
}

$reciever_uid = user_id_from_name($reciever_username);
if ($reciever_uid === false) {
    $valid = false;
    $errors['user'][] = 'Invalid username';
}

$item_id = item_id_from_tag($item_tag);
if ($item_id === false) {
    $valid = false;
    $errors['item_tag'][] = 'Invalid item tag';
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

if (!gift_item($_SESSION['uid'], $reciever_uid, $item_id, $item_tag)) {
    $errors['submit'][] = 'Error gifting item';
    api_fail('Error gifting item', $errors);
}

api_succeed('Successfully gifted item!', $errors);
