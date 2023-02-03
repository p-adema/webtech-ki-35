<?php
require "api_resolve.php";
require "pdo_write.php";
require "check_acc_fields.php";


$errors = [
    'name' => [],
    'email' => [],
    'full_name' => [],
    'password' => [],
    'new_password' => [],
    'repeated_password' => [],
];
$valid = true;

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$full_name = $_POST['full_name'] ?? '';
$current_password = $_POST['password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$repeated_password = $_POST['repeated_password'] ?? '';

api_require_login();

$user_id = $_SESSION['uid'];

try {
    $errors['name'] = check_name($name, $user_id);
    $errors['email'] = check_email($email, $user_id);
    $errors['full_name'] = check_full_name($full_name);

    $valid &= check_acc_err($errors);
} catch (PDOException $e) {
    api_fail('Internal database error');
}

$sql_password = 'SELECT (password) FROM db.users WHERE (id = :id);';
$data_id = ['id' => $user_id];
$prep_password = prepare_write($sql_password);
if (!$prep_password->execute($data_id)) {
    api_fail('Internal error');
}
$user_password_hashed = $prep_password->fetch()['password'];


if (empty($current_password)) {
    if (!empty($new_password) or !empty($repeated_password)) {
        $errors['password'][] = 'Passwords is required';
        $valid = false;
    }
} else {
    if (!password_verify($current_password, $user_password_hashed)) {
        $errors['password'][] = 'Incorrect password';
        $valid = false;
    } else {
        if (!empty($new_password)) {
            $errors['new_password'] = check_password($new_password);
            if (!empty($errors['new_password'])) {
                $valid = false;
            }
        }
    }
    if (empty($new_password) or empty($repeated_password)) {
        $errors['new_password'][] = 'Please fill in your new passwords';
        $valid = false;
    }
    if ($new_password != $repeated_password) {
        $errors['repeated_password'][] = 'Passwords do not match';
        $valid = false;
    }
}


if (!$valid) {
    api_fail('Please properly fill in the fields.', $errors);
}

if (empty($current_password)) {
    $sql_update = 'UPDATE db.users t SET t.name = :name, t.email = :email, t.full_name = :full_name  WHERE t.id = :id;';
    $data_update = [
        'name' => htmlspecialchars($name),
        'email' => htmlspecialchars($email),
        'full_name' => htmlspecialchars($full_name),
        'id' => htmlspecialchars($user_id),
    ];
} else {
    $sql_update = 'UPDATE db.users t SET t.name = :name, t.email = :email, t.full_name = :full_name, t.password = :new_password WHERE t.id = :id;';
    $data_update = [
        'name' => htmlspecialchars($name),
        'email' => htmlspecialchars($email),
        'full_name' => htmlspecialchars($full_name),
        'new_password' => password_hash($new_password, PASSWORD_DEFAULT),
        'id' => htmlspecialchars($user_id),
    ];
}
$prep_update = prepare_write($sql_update);
if (!$prep_update->execute($data_update)) {
    api_fail("Couldn't update data");
}


api_succeed('Data has been changed', $errors);
