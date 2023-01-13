<?php
require "api_resolve.php";
session_start();
session_logout();
api_succeed('Logout successful!');
