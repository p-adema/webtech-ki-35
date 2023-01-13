<?php
/*
 * Expects a POST request with:
 *      no parameters
 */
require "api_resolve.php";
api_logout();
api_succeed('Logout successful!');
