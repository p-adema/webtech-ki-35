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
 * Set session cookies to be logged in for a user
 * @param string $username Name of user to be logged in as
 * @throws InvalidArgumentException if username is not present in database
 */
function api_login(string $username): void
{
    require_once "pdo_read.php";
    ensure_session();
    $sql = 'select (id) from db.users where (name = :name)';
    $data = ['name' => $username];
    $pdo_read = new_pdo_read();
    $sql_prep = $pdo_read->prepare($sql);
    $sql_prep->execute($data);
    $uid = $sql_prep->fetch();
    if ($uid === false) {
        throw new InvalidArgumentException("Username not present in database");
    }

    $_SESSION['uid'] = $uid[0];
    $_SESSION['auth'] = true;
}

/**
 * Reset session cookies to be logged out
 * @throws InvalidArgumentException if not logged in
 */
function api_logout(): void
{
    ensure_session();
    if ($_SESSION['uid']) {
        unset($_SESSION['uid']);
        $_SESSION['auth'] = false;
    } else {
        throw new InvalidArgumentException("Session is not logged in");
    }
}
