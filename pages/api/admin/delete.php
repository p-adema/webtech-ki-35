<?php

require_once 'tag_actions.php';
require_once 'api_resolve.php';
require_once 'admin_controls.php';
api_ensure_admin();

$errors = [
    'item_tag' => [],
    'submit' => []
];
$valid = true;

$item_tag = $_POST['item_tag'] ?? '';


$item_id = item_id_from_tag($item_tag);
if ($item_id === false) {
    $valid = false;
    $errors['item_tag'][] = 'Invalid item tag';
}

if (!admin_delete_item($item_id)) {
    $errors['submit'][] = 'Error deleting item';
    api_fail('Error deleting item', $errors);
}

api_succeed('Successfully deleted item!', $errors);
