<?php

/*
Password constraints:
     *  Length >= 8
     *  Contains uppercase letter
     *  Contains lowercase letter
     *  Contains number
     *  Contains special character
 */

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

function check_name(string $name, int $self_uid = -1): array
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

        $sql_prep = prepare_readonly($sql_duplicate);

        if (!$sql_prep->execute($data)) {
            $errors[] = 'Internal server error, try again later';
        }
        $duplicate = $sql_prep->fetch();
        if (!empty($duplicate) and $duplicate['id'] !== $self_uid) {
            $errors[] = 'This username is already in use';
        }
    }

    return $errors;
}

function check_email(string $email, int $self_uid = -1): array
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

        $sql_prep = prepare_readonly($sql_duplicate);

        if (!$sql_prep->execute($data)) {
            $errors[] = 'Internal server error, try again later';
        }
        $duplicate = $sql_prep->fetch();
        if (!empty($duplicate) and $duplicate['id'] !== $self_uid) {
            $errors[] = 'This email is already in use';
        }
    }

    return $errors;
}

function check_re_pwd(string $password, string $re_pwd): array
{
    $errors = [];

    if ($password !== $re_pwd) {
        $errors[] = 'Passwords do not match';
    }

    return $errors;
}

function check_full_name(string $full_name): array
{
    $errors = [];
    if (strlen(htmlspecialchars($full_name)) > 128) {
        $errors[] = "Full name must be shorter (max 128 standard characters).";
    }

    return $errors;
}

function check_acc_fields(string $name, string $email, string $password, string $re_pwd, string $full_name): array
{
    $errors = [];

    $errors['name'] = check_name($name);
    $errors['email'] = check_email($email);
    $errors['password'] = check_password($password);
    $errors['re_pwd'] = check_re_pwd($password, $re_pwd);
    $errors['full_name'] = check_full_name($full_name);

    return $errors;
}

function check_acc_err(array $acc_errors): bool
{
    return empty($acc_errors['name'])
        && empty($acc_errors['email'])
        && empty($acc_errors['password'])
        && empty($acc_errors['re_pwd'])
        && empty($acc_errors['full_name']);
}
