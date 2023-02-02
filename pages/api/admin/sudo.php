<?php

require_once 'api_resolve.php';
require_once 'admin_controls.php';
api_require_admin();

$target_id = $_POST['user'] ?? '';

if (empty($target_id)) {
    api_fail('Please provide a user ID');
} elseif (!is_numeric($target_id)) {
    api_fail('Please provide a numerical user ID');
}
$_SESSION['uid'] = $target_id;

api_succeed("Successfully sudo'd user!");
