<?php

require 'video_functionality.php';

require_once 'api_resolve.php';

ensure_session();

if ($_SESSION['auth']) {

    $tag = $_POST['tag']['on'];
    $uid = $_SESSION['uid'];
    $star = $_POST['star'];

    update_rating($star, $uid, $tag);
}