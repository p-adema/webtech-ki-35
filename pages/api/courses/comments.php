<?php

require 'comments_components.php';

require_once 'api_resolve.php';

ensure_session();

if ($_SESSION['auth']) {

    $uid = $_SESSION['uid'];
    $score = $_POST['rating'];
    $comment_id = $_POST['comment'];

    change_comment_score($score, $comment_id, $uid);
}

