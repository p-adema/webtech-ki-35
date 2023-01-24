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

$l_name = null;
if (empty($_POST['l_name'])) {
    $errors['l_name'][] = 'Please fill in your legal name';
    $valid = false;
} else {
    $l_name = $_POST['l_name'];
}

$country = null;
if (empty($_POST['country'])) {
    $errors['country'][] = 'Please fill in your country of residence';
    $valid = false;
} else {
    $country = $_POST['country'];
}

$city = null;
if (empty($_POST['city'])) {
    $errors['city'][] = 'Please fill in your city of residence';
    $valid = false;
} else {
    $city = $_POST['city'];
}

$zipcode = null;
if (empty($_POST['zipcode'])) {
    $errors['zipcode'][] = 'Please fill in your zipcode';
    $valid = false;
} else {
    $zipcode = $_POST['zipcode'];
}

$streetnum = null;
if (empty($_POST['streetnum'])) {
    $errors['streetnum'][] = 'Please fill in your street number';
    $valid = false;
} else {
    $streetnum = $_POST['streetnum'];
}

if (!$valid) {
    api_fail('Please fill in all fields', $errors);
}

ensure_session();
if (!$_SESSION['auth']) {
    api_fail('You must be logged in', ['submit' => 'Please log in first']);
}

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
$p_sql = $pdo_write->prepare($sql);

if (!$p_sql->execute($data)) {
    api_fail('Data couldn\'t be added', ['submit' => 'Internal error']);
}

api_succeed('Billing information has been added');
