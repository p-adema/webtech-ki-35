<?php

# Linter attribute to describe control flow,
# as these functions end script execution
use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function api_fail(string $message, array $errors, array $data = []): void
{
    $response['success'] = false;
    $response['message'] = $message;
    $response['errors'] = $errors;
    $response['data'] = $data;
    echo json_encode($response);
    die();
}

#[NoReturn] function api_succeed(string $message, array $errors = [], array $data = []): void
{
    $response['success'] = true;
    $response['message'] = $message;
    $response['errors'] = $errors;
    $response['data'] = $data;
    echo json_encode($response);
    die();
}

function session_login(string $username): void
{
    require_once "pdo_read.php";

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

function session_logout(): void
{
    unset($_SESSION['uid']);
    $_SESSION['auth'] = false;
}
