<?php
/**
 * Checks whether the given password is valid
 * @param string $password Password to be checked
 * @return array Array of errors. When empty, the password is valid
 */

function check_password(string $password): array
{
    $errors = [];

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } else {
        if (strlen($password) < 8) {
            $errors[] = "Passwords must be at least 8 characters long.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Passwords must contain a lowercase character.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Passwords must contain an uppercase character.";
        }
        if (!preg_match('/\d/', $password)) {
            $errors[] = "Passwords must contain a number.";
        }
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = "Passwords must contain a special character.";
        }
    }

    return $errors;
}

function check_name(string $name, PDO $PDO): array
{
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Username is required.';
    } else if (strlen($name) < 5) {
        $errors[] = "Username must be at least 5 characters.";
    } else if (strlen(htmlspecialchars($name)) > 128) {
        $errors[] = "Username must be shorter (max 128 standard characters).";
    } else if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Username should not be an email.";
    } else {
        $sql_duplicate = 'SELECT (id) FROM db.users WHERE (name = :name);';
        $data = ['name' => htmlspecialchars($name)];

        $sql_prep = $PDO->prepare($sql_duplicate);

        if (!$sql_prep->execute($data)) {
            $errors[] = 'Internal server error, try again later';
        }
        $duplicate = $sql_prep->fetch();
        if (!empty($duplicate)) {
            $errors[] = 'This username is already in use';
        }
    }

    return $errors;
}

function check_email(string $email, PDO $PDO): array
{
    $errors = [];

    if (empty($email)) {
        $errors[] = 'Email is required.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else if (strlen(htmlspecialchars($email)) > 128) {
        $errors[] = "Email must be shorter (max 128 standard characters).";
    } else {
        $sql_duplicate = 'SELECT (id) FROM db.users WHERE (email = :email);';
        $data = ['email' => htmlspecialchars($email)];

        $sql_prep = $PDO->prepare($sql_duplicate);

        if (!$sql_prep->execute($data)) {
            $errors[] = 'Internal server error, try again later';
        }
        $duplicate = $sql_prep->fetch();
        if (!empty($duplicate)) {
            $errors[] = 'This email is already in use';
        }
    }

    return $errors;
}
