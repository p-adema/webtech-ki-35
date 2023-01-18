<?php
/*
 * Expects a POST request with:
 *      item : < video or course tag >
 */
$errors = [
    'item' => [],
    'submit' => []
];
$valid = true;
$item = $_POST['item'];

if ($item) {
    $errors['item'][] = 'Please provide an item to be added';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields', $errors);
}

$data = [
    'item' => $item,
];

$sql = '';
