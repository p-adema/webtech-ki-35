<?php

require_once 'tag_actions.php';
require_once 'api_resolve.php';
require_once 'admin_controls.php';

$errors = [
    'user' => [],
    'item_tag' => [],
    'submit' => []
];

ensure_session();

if ($_SESSION['auth']) {

    $valid = true;
    $uid = $_POST['user'] ?? '';
    $item_tag = $_POST['item_tag'] ?? '';
    $aid = $_SESSION['uid'];

    if (!is_admin($aid)) {
        api_fail('Insufficient privileges', ['submit' => 'Insufficient privileges']);
    }


    if (empty(username($uid))) {
        $valid = false;
        $errors['user'][] = 'Please select an existing user to gift to.';
    }

    if (empty(item($item_tag))) {
        $valid = false;
        $errors['item_tag'][] = 'Please select an existing item to gift.';
    }

    if ($valid) {
        gift_item($aid, username($uid), item($item_tag), $item_tag);
        api_succeed('Successfully gifted this item!', $errors);
    } else {
        api_fail('Please provide correct parameters', $errors);
    }
}

else {
    api_fail('You must be logged in to perform this action', ['submit' => 'Log in first']);
}
