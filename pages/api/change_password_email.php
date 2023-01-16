<?php
require "api_resolve.php";
$errors = [
    'password' => [],
    'password_repeat' => [],
    'submit' => []
];
$valid = true;

$tag = $_POST['tag'];

require "pdo_write.php";
try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    $valid = false;
}


if (isset($pdo_write)) {
    $sql = 'SELECT (user_id) FROM db.emails_pending WHERE (url_tag = :tag) AND (type = \'password-reset\');';
    $data = ['tag' => htmlspecialchars("$tag")];
    $sql_prep = $pdo_write->prepare($sql);

    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal ser error, try again later';
        $valid = false;
    }
    $user_id = $sql_prep->fetch();

    if (empty($user_id)) {
        $errors['submit'][] = 'Invalid tag';
        $valid = false;
        # return user a error message
    } else {
        $user_id = $user_id['user_id'];
    }

}
$password = $_POST['password'];
$repeated_password = $_POST['password_repeated'];

if (empty($password)) {
    $errors['password'][] = 'Password is required.';
    $valid = false;
} else {
    if (strlen($password) < 8) {
        $errors['password'][] = "Passwords must be at least 8 characters long.";
        $valid = false;
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors['password'][] = "Passwords must contain a lowercase character.";
        $valid = false;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors['password'][] = "Passwords must contain an uppercase character.";
        $valid = false;
    }
    if (!preg_match('/\d/', $password)) {
        $errors['password'][] = "Passwords must contain a number.";
        $valid = false;
    }
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors['password'][] = "Passwords must contain a special character.";
        $valid = false;
    }
}


if ($password != $repeated_password) {
    $errors['password'][] = "Passwords do not match.";
    $valid = false;
}




if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}
if(isset($pdo_write)) {
    $sql = 'UPDATE db.users t SET t.password = :new_password WHERE t.id = :user_id;';

    $data = ['new_password' => password_hash($password, PASSWORD_DEFAULT),
            'user_id' => htmlspecialchars("$user_id")];
    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute($data);
}

api_succeed('Password has been changed!', $errors);


