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



$sql = "SELECT (user_id) FROM db.emails_pending WHERE (url_tag = :tag) AND (type = 'password-reset');";
$data_tag = ['tag' => $tag];
$sql_prep = prepare_write($sql);

if (!$sql_prep->execute($data_tag)) {
    $errors['submit'][] = 'Internal server error, try again later';
    $valid = false;
}
$user_id = $sql_prep->fetch();

if ($user_id === false) {
    $errors['submit'][] = 'Invalid tag';
    api_fail('Invalid tag', $errors);
}

$user_id = $user_id['user_id'];

$password = $_POST['password'] ?? '';
$repeated_password = $_POST['password_repeated'] ?? '';

$errors['password'] = check_password($password);
$errors['password_repeated'] = check_re_pwd($password, $repeated_password);
$valid &= empty($errors['password']) && empty($errors['password_repeated']);


if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}
$sql = 'UPDATE db.users t SET t.password = :new_password WHERE t.id = :user_id;';

$data_password = [
    'new_password' => password_hash($password, PASSWORD_DEFAULT),
    'user_id' => $user_id
];
$sql_prep = prepare_write($sql);
$sql_prep->execute($data_password);

$sql_delete_old = 'DELETE FROM db.emails_pending WHERE db.emails_pending.url_tag = :tag';
$prep_delete = prepare_write($sql_delete_old);
$prep_delete->execute($data_tag);

api_succeed('Password has been changed!', $errors);
