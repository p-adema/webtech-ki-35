<?php

# Linter attribute to describe control flow,
# as these functions end script execution
use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function api_fail(array $errors, string $failure_msg, array $data = []): void
{
    $response['success'] = false;
    $response['message'] = $failure_msg;
    $response['errors'] = $errors;
    $response['data'] = $data;
    echo json_encode($response);
    die();
}

#[NoReturn] function api_succeed(string $success_msg, array $data = []): void
{
    $response['success'] = true;
    $response['message'] = $success_msg;
    $response['errors'] = [];
    $response['data'] = $data;
    echo json_encode($response);
    die();
}
