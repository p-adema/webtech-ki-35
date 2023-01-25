<?php

ensure_session();

if ($_SESSION['auth'] and $_POST['star'] != null) {

    $tag = $_POST['tag']['on'];
    $uid = $_SESSION['uid'];
    $star = $_POST['star'];

    update_rating($star, $uid, $tag);
}
