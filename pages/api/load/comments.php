<?php

/*
 * Expects a POST request with:
 *      type : < 'item' or 'replies' >
 *      on : < item tag > or < comment id >
 */

require "api_resolve.php";
require "pdo_read.php";

$errors = [
    'type' => [],
    'on' => []
];
$valid = true;
$type = $_POST['type'];
$on = $_POST['on'];

if (empty($type)) {
    $errors['$VAR'][] = 'Please provide the comment source type';
    $valid = false;
} elseif ($type !== 'item' and $type !== 'replies') {
    $errors['$VAR'][] = 'Please provide a valid source type';
    $valid = false;
}

if (empty($on)) {
    $errors['$VAR'][] = 'Please provide the comment source tag or id';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields', $errors);
}
