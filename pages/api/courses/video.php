<?php

require 'video_functionality.php';

$star = $_POST['star'];
$uid = $_GET['uid'];
$video_tag = $_GET['tag'];

require_once 'dropdown_function.php';

$pdo_write = new_pdo_write();

$sql = 'hallo';