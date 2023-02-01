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
    } elseif ($_SESSION['auth'] and invalid_user($_SESSION['uid'])) {
        unset($_SESSION['uid']);
        $_SESSION['auth'] = false;
        session_regenerate_id(true);
    }
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
    $pdo_read = new_pdo_read();
    $sql_prep = $pdo_read->prepare($sql);
    $sql_prep->execute($data);
    $uid = $sql_prep->fetch(PDO::FETCH_ASSOC);

    if ($uid === false) { # No such user
        return 'No such user';
    } elseif (invalid_user($uid['id'])) {
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
    return true;
}

function invalid_user(int $uid): bool
{
    $pdo_read = new_pdo_read();
    $sql = 'SELECT banned FROM users WHERE id = :uid';
    $prep = $pdo_read->prepare($sql);
    $prep->execute(['uid' => $uid]);
    $user = $prep->fetch();

    return $user === false or $user['banned'];
}

function api_require_login(): void
{
    ensure_session();

    if (!$_SESSION['auth']) {
        api_fail('Please log in first');
    }
}
