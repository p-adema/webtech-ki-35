<?php

require "api_resolve.php";
require_once "pdo_read.php";

$query = $_POST['query'] ?? '';

require_once "owned_items.php";

$videos = get_owned_videos($query);
$response = ['html' => render_owned_videos($videos)];
api_succeed('Search results retrieved', data: $response);
