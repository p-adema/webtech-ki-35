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
