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

$name = $_POST['name'];
$email = $_POST['email'];
$full_name = $_POST['full_name'];
$current_password = $_POST['password'];
$new_password = $_POST['new_password'];
$repeated_password = $_POST['repeated_password'];


//      Checks if full name is valid

ensure_session();
$user_id = $_SESSION['uid'];
if (isset($pdo_write)) {
    $sql = 'SELECT (password) FROM db.users WHERE (id = :id);';
    $data = ['id' => $user_id];
    $sql_prep = $pdo_write->prepare($sql);
    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal server error, try again later';
        $valid = false;
    }
    $user_password_hashed = $sql_prep->fetch();
    $user_password_hashed = $user_password_hashed['password'];


    if (!empty($current_password)) {
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
    } else {
        if (!empty($new_password) or !empty($repeated_password)) {
            $errors['password'][] = 'Passwords is required';
            $valid = false;
        }
    }
    if ($new_password != $repeated_password) {
        $errors['repeated_password'][] = 'Passwords do not match';
        $valid = false;
    }
}

try {
    $pdo_write = new_pdo_write(err_fatal: false);

    $errors['name'] = check_name($name, $pdo_write);
    $errors['email'] = check_email($email, $pdo_write);
    $errors['full_name'] = check_full_name($full_name);

    $valid &= check_acc_err($errors);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    api_fail('Internal database error', $errors);
}

if (!$valid) {
    api_fail('Please properly fill in the fields.', $errors);
}

if (empty($current_password)) {
    $sql = 'UPDATE db.users t SET t.name = :name, t.email = :email, t.full_name = :full_name  WHERE t.id = :id;';
    $data = [
        'name' => htmlspecialchars($name),
        'email' => htmlspecialchars($email),
        'full_name' => htmlspecialchars($full_name),
        'id' => htmlspecialchars($user_id),
    ];
} else {
    $sql = 'UPDATE db.users t SET t.name = :name, t.email = :email, t.full_name = :full_name, t.password = :new_password WHERE t.id = :id;';
    $data = [
        'name' => htmlspecialchars($name),
        'email' => htmlspecialchars($email),
        'full_name' => htmlspecialchars($full_name),
        'new_password' => password_hash($new_password, PASSWORD_DEFAULT),
        'id' => htmlspecialchars($user_id),
    ];
}
$sql_prep = $pdo_write->prepare($sql);
$sql_prep->execute($data);


api_succeed('Data has been changed', $errors);
