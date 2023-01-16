<?php
require "pdo_write.php";
require "api_resolve.php";

try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
    $valid = false;
}

$tag = $_POST['tag'];
if (isset($pdo_write)) {
    $sql = 'SELECT (user_id) FROM db.emails_pending WHERE (url_tag = :tag) AND (type = \'verify\');';
    $data = ['tag' => htmlspecialchars("$tag")];
    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute($data);
    $user_id = $sql_prep->fetch();
    $user_id = $user_id['user_id'];
    $sql = 'UPDATE db.users t SET t.verified = 1 WHERE t.id = :user_id;';
    $data = ['user_id' => htmlspecialchars("$user_id")];
    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute($data);
    api_succeed('Account verified!');
}
