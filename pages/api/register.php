<?php
/*
 * Expects a POST request with:
 *      name        :   < username >
 *      email       :   < email >
 *      password    :   < password >
 *      full_name   :   < full name >
 */

require "api_resolve.php";

$errors = [
    'name' => [],
    'email' => [],
    'password' => [],
    'full_name' => [],
    'submit' => []
];
$valid = true;

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$full_name = $_POST['full_name'];

if (empty($name)) {
    $errors['name'][] = 'Username is required.';
    $valid = false;
} else if (strlen($name) < 5) {
    $errors['name'][] = "Username must be at least 5 characters.";
    $valid = false;
} else if (strlen(htmlspecialchars($name)) > 128) {
    $errors['name'][] = "Username must be shorter (max 128 standard characters).";
    $valid = false;
} else if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
    $errors['name'][] = "Username should not be an email.";
    $valid = false;
}
# Use a read/write, because we might need to insert a user later
require "pdo_write.php";
try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    $valid = false;
}

if (isset($pdo_write)) {
    $sql = 'SELECT (id) FROM db.users WHERE (name = :name);';
    $data = ['name' => htmlspecialchars($name)];

    $sql_prep = $pdo_write->prepare($sql);

    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal server error, try again later';
        $valid = false;
    }
    $duplicate = $sql_prep->fetch();
    if (!empty($duplicate)) {
        $errors['name'][] = 'This username is already in use';
        $valid = false;
    }
}

if (empty($email)) {
    $errors['email'][] = 'Email is required.';
    $valid = false;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'][] = "Invalid email format.";
    $valid = false;
} else if (strlen(htmlspecialchars($email)) > 128) {
    $errors['email'][] = "Email must be shorter (max 128 standard characters).";
    $valid = false;
}

if (isset($pdo_write)) {
    /** @noinspection DuplicatedCode */
    $sql = 'SELECT (id) FROM db.users WHERE (email = :email);';
    $data = ['email' => htmlspecialchars($email)];

    $sql_prep = $pdo_write->prepare($sql);

    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal server error, try again later';
        $valid = false;
    }
    $duplicate = $sql_prep->fetch();
    if (!empty($duplicate)) {
        $errors['email'][] = 'This email is already in use';
        $valid = false;
    }
}

/*
Password constraints:
     *  Length >= 8
     *  Contains uppercase letter
     *  Contains lowercase letter
     *  Contains number
     *  Contains special character
 */

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

if (strlen(htmlspecialchars($full_name)) > 128) {
    $errors['full_name'][] = "Full name must be shorter (max 128 standard characters).";
    $valid = false;
}

if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}
$data = [
    'name' => htmlspecialchars($_POST['name']),
    'email' => htmlspecialchars($_POST['email']),
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'full_name' => htmlspecialchars($_POST['full_name'])
];

$sql = 'INSERT INTO db.users (name, email, password, full_name, membership)
VALUES (:name, :email, :password, :full_name, \'none\');';

/** @noinspection PhpUndefinedVariableInspection */
$sql_prep = $pdo_write->prepare($sql);
if (!$sql_prep->execute($data)) {
    $errors['submit'][] = 'Internal server error';
    api_fail('Internal server error, try again later', $errors);
}

if(isset($pdo_write)) {
    $sql = 'SELECT (id) FROM db.users WHERE (email = :email);';
    $data = ['email' => htmlspecialchars($email)];
    $sql_prep = $pdo_write->prepare($sql);
    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal server error';
        $valid = false;
    }
    $user_id = $sql_prep->fetch();
    $user_id = $user_id['id'];
    require 'tag_actions.php';
    $random_tag = tag_create();
    $sql = 'INSERT INTO db.emails_pending (type, url_tag, user_id, request_time)
    VALUES (:type, :tag, :user_id, DEFAULT);';
    $data = ['type' => htmlspecialchars('verify'),
        'tag' => htmlspecialchars("$random_tag"),
        'user_id' => htmlspecialchars("$user_id")];
    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute($data);
    $link = '/auth/verify.php?tag=' . $random_tag;
    api_succeed("An E-mail to activate your account has been sent to $email <br>  <a href='$link'>link</a>", $errors);

}


# TODO: add email verification
# TODO: add verification status to user table