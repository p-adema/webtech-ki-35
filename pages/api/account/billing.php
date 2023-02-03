<?php
require "api_resolve.php";

$errors = [
    'l_name' => [],
    'country' => [],
    'city' => [],
    'zipcode' => [],
    'streetnum' => [],
    'submit' => []
];
$valid = true;
$l_name = $_POST['l_name'] ?? '';
$country = $_POST['country'] ?? '';
$city = $_POST['city'] ?? '';
$zipcode = $_POST['zipcode'] ?? '';
$streetnum = $_POST['streetnum'] ?? '';

if (empty($_POST['l_name'])) {
    $errors['l_name'][] = 'Please fill in your legal name';
    $valid = false;
}

if (empty($_POST['country'])) {
    $errors['country'][] = 'Please fill in your country of residence';
    $valid = false;
}

if (empty($_POST['city'])) {
    $errors['city'][] = 'Please fill in your city of residence';
    $valid = false;
}

if (empty($_POST['zipcode'])) {
    $errors['zipcode'][] = 'Please fill in your zipcode';
    $valid = false;
}

if (empty($_POST['streetnum'])) {
    $errors['streetnum'][] = 'Please fill in your street number';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields', $errors);
}

api_require_login();

$sql = "INSERT INTO db.billing_information (user_id, legal_name, country, city, zipcode, street_number)
        VALUES (:uid, :l_name, :country, :city, :zipcode, :streetnum);";

$data = [
    'uid' => $_SESSION['uid'],
    'l_name' => htmlspecialchars($l_name),
    'country' => htmlspecialchars($country),
    'city' => htmlspecialchars($city),
    'zipcode' => htmlspecialchars($zipcode),
    'streetnum' => htmlspecialchars($streetnum)
];

require_once "pdo_write.php";
$pdo_write = new_pdo_write();
$p_sql = prepare_write($sql);

if (!$p_sql->execute($data)) {
    api_fail("Data couldn't be added", ['submit' => 'Internal error']);
}

api_succeed('Billing information has been added');
