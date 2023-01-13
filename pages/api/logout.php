<?php
/*
 * Expects a POST request with:
 *      no parameters
 */
require "api_resolve.php";
if (!api_logout()) {
    api_fail('Unexpected server error', ['submit' => ['Unexpected server error']]);
}
api_succeed('Logout successful!');
