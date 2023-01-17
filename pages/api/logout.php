<?php
/*
 * Expects a POST request with:
 *      no parameters
 */
require "api_resolve.php";

if (!api_logout()) {
    api_fail('You must be logged in to log out', ['submit' => ['You must be logged in to log out']]);
}
api_succeed('Successfully logged out!');
