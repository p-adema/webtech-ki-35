<?php
/*
 * Expects a POST request with:
 *      TODO
 */
require "api_resolve.php";
require "pdo_write.php";
require "check_acc_fields.php";

$errors = [
    'password' => [],
    'password_repeated' => [],
    'submit' => []
];
$valid = true;

ensure_session();
if (!isset($_SESSION['url_tag']) or $_SESSION['url_tag_type'] !== 'password-reset') {
    $errors['submit'][] = "You don't seem to have come from a valid reset link";
    api_fail('Invalid origin link', $errors);
} else {
    $tag = $_SESSION['url_tag'];
}
# TODO: check that password and tag are both actually POSTed

try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    $valid = false;
}


if (isset($pdo_write)) {
    $sql = 'SELECT (user_id) FROM db.emails_pending WHERE (url_tag = :tag) AND (type = \'password-reset\');';
    $data = ['tag' => $tag];
    $sql_prep = $pdo_write->prepare($sql);

    if (!$sql_prep->execute($data)) {
        $errors['submit'][] = 'Internal server error, try again later';
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

$errors['password'] = check_password($password);
if (!empty($errors['password'])) {
    $valid = false;
}

if ($password != $repeated_password) {
    $errors['password_repeated'][] = "Passwords do not match.";
    $valid = false;
}


if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}
if (isset($pdo_write)) {
    $sql = 'UPDATE db.users t SET t.password = :new_password WHERE t.id = :user_id;';

    $data = [
        'new_password' => password_hash($password, PASSWORD_DEFAULT),
        'user_id' => $user_id
    ];
    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute($data);
}

api_succeed('Password has been changed!', $errors);
