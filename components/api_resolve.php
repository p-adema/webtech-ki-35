<?php

# Linter attribute to describe control flow,
# as these functions end script execution
use JetBrains\PhpStorm\NoReturn;

/**
 * Send JSON data, then stop execution
 * @param string $message Message to be returned
 * @param array $errors Errors encountered during data processing
 * @param array $data Additional data to be sent
 */
#[NoReturn] function api_fail(string $message, array $errors, array $data = []): void
{
    $response['success'] = false;
    $response['message'] = $message;
    $response['errors'] = $errors;
    $response['data'] = $data;
    echo json_encode($response);
    die();
}

/**
 * Send JSON data, then stop execution
 * @param string $message Message to be returned
 * @param array $errors Errors encountered during data processing
 * @param array $data Additional data to be sent
 */
#[NoReturn] function api_succeed(string $message, array $errors = [], array $data = []): void
{
    $response['success'] = true;
    $response['message'] = $message;
    $response['errors'] = $errors;
    $response['data'] = $data;
    echo json_encode($response);
    die();
}

/**
 * Creates a session if one did not exist yet
 */
function ensure_session(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Set session cookies to be logged in for this request
 * @param string $username_or_email Name/email of user to be logged in as
 * @return bool Success of login
 */
function api_login(string $username_or_email): bool
{
    require_once "pdo_read.php";
    ensure_session();
    if ($_SESSION['auth']) { # Can't log in if already logged in
        return false;
    }
    if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
        $sql = 'SELECT (id) FROM db.users WHERE (email = :name);';
    } else {
        $sql = 'SELECT (id) FROM db.users WHERE (name = :name);';
    }
    $data = ['name' => $username_or_email];
    $pdo_read = new_pdo_read();
    $sql_prep = $pdo_read->prepare($sql);
    $sql_prep->execute($data);
    $uid = $sql_prep->fetch();

    if ($uid === false) { # No such user
        return false;
    }

    $_SESSION['uid'] = $uid[0];
    $_SESSION['auth'] = true;
    return true;
}

/**
 * Reset session cookies to be logged out for this request
 * @return bool Success of logout (fails if request wasn't logged in)
 */
function api_logout(): bool
{
    ensure_session();
    if (!$_SESSION['auth']) {
        return false;
    }
    unset($_SESSION['uid']);
    $_SESSION['auth'] = false;
    return true;
}

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
            $errors['password'][] = "Passwords must contain a special character.";
        }
    }

    return $errors;
}
