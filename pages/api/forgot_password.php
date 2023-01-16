<?php
require "api_resolve.php";
$errors = [
    'email' => [],
    'submit' => []
    ];
$valid = true;

$email = $_POST['email'];

if (empty($email)) {
    $errors['email'][] = 'Email is required.';
    $valid = false;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email'][] = 'Invalid email formta.';
    $valid = false;
} else if (strlen(htmlspecialchars($email)) > 128) {
    $errors['email'][] = 'Email must be shorter (max 128 standard characters).';
    $valid = false;
}

require "pdo_write.php";
try {
    $pdo_write = new_pdo_write(err_fatal: false);
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error (unable to connect to database)';
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
    $user_id = $sql_prep->fetch();
    $user_id = $user_id['id'];
}

if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}


if(isset($pdo_write)) {
    if (!empty($user_id)) {
    require 'tag_actions.php';
    $random_tag = tag_create();
    $sql = 'INSERT INTO db.emails_pending (type, url_tag, user_id, request_time)
    VALUES (:type, :tag, :user_id, DEFAULT);';
    $data = ['type' => htmlspecialchars('password-reset'),
            'tag' => htmlspecialchars("$random_tag"),
            'user_id' => htmlspecialchars("$user_id")];
    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute($data);
    $link = '/auth/change_password_email.php?tag=' . $random_tag;
     api_succeed("If you entered a valid E-mail adress, an E-mail has been sent to $email <br>  <a href='$link'>link</a>", $errors);
    }
}
