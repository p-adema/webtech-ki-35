<?php
require_once "pdo_read.php";

# Linter attribute to describe control flow,
# as these functions end script execution
use JetBrains\PhpStorm\NoReturn;

/**
 * Send JSON data, then stop execution
 * @param string $message Message to be returned
 * @param ?array $errors Errors encountered during data processing. Defaults to 'message'
 * @param array $data Additional data to be sent
 */
#[NoReturn] function api_fail(string $message, ?array $errors = null, array $data = []): void
{
    $response['success'] = false;
    $response['message'] = $message;
    $response['errors'] = $errors ?? ['submit' => $message];
    $response['data'] = $data;
    echo json_encode($response);
    exit;
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
    $data['message'] = $message;
    $response['data'] = $data;
    echo json_encode($response);
    exit;
}

/**
 * Creates a session if one did not exist yet
 */
function ensure_session(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['auth'])) {
        $_SESSION['auth'] = false;
    } elseif ($_SESSION['auth']) {
        $user_type = user_type($_SESSION['uid']);
        if ($user_type === 'invalid') {
            unset($_SESSION['uid']);
            $_SESSION['auth'] = false;
            session_regenerate_id(true);
        } elseif ($user_type === 'admin') {
            $_SESSION['admin'] = true;
        }
    }
    $_SESSION['admin'] ??= false;
}

/**
 * Set session cookies to be logged in for this request
 * @param string $username_or_email Name/email of user to be logged in as
 * @return bool Success of login
 */
function api_login(string $username_or_email): bool|string
{
    require_once "pdo_read.php";
    ensure_session();
    if ($_SESSION['auth']) { # Can't log in if already logged in
        return 'Already logged in';
    }
    if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
        $sql = 'SELECT (id) FROM db.users WHERE (email = :name);';
    } else {
        $sql = 'SELECT (id) FROM db.users WHERE (name = :name);';
    }
    $data = ['name' => $username_or_email];
    $sql_prep = prepare_readonly($sql);
    $sql_prep->execute($data);
    $uid = $sql_prep->fetch(PDO::FETCH_ASSOC);

    if ($uid === false) { # No such user
        return 'No such user';
    } elseif (user_type($uid['id']) === 'invalid') {
        return 'User has been banned';
    }
    session_regenerate_id();
    $_SESSION['uid'] = $uid['id'];
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
    session_regenerate_id();
    unset($_SESSION['uid']);
    $_SESSION['auth'] = false;
    $_SESSION['admin'] = false;
    return true;
}

function user_type(int $uid): string
{
    $sql = 'SELECT banned, admin FROM users WHERE id = :uid';
    $prep = prepare_readonly($sql);
    $prep->execute(['uid' => $uid]);
    $user = $prep->fetch();

    if ($user === false or $user['banned']) {
        return 'invalid';
    } elseif ($user['admin']) {
        return 'admin';
    }
    return 'standard';
}

function api_require_login(): void
{
    ensure_session();

    if (!$_SESSION['auth']) {
        api_fail('Please log in first');
    }
}
