<?php
require_once 'pdo_write.php';
require_once 'api_resolve.php';

ensure_session();
if ($_SESSION['auth']) {
    $user_id = $_SESSION['uid'];
    $video_tag = $_POST['video_tag'];
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException $e) {
        $errors['submit'][] = 'Internal server error (unable to connect to database)';
        $valid = false;
    }
    if (isset($pdo_write)) {
        $sql = 'SELECT id FROM db.watches WHERE (user_id = :user_id and video_tag = :video_tag);';
        $data = ['user_id' => $user_id,
                'video_tag' => $video_tag];
        $sql_prep = $pdo_write->prepare($sql);

        $sql_prep->execute($data);
        $result = $sql_prep->fetch();
        if (empty($result)) {
            $sql = 'INSERT INTO db.watches (video_tag, user_id, watch_amount) VALUES (:video_tag, :user_id, :watch_amount);';
            $data = ['video_tag' => $video_tag,
                'user_id' => $user_id,
                'watch_amount' => 5];
            $sql_prep = $pdo_write->prepare($sql);

            $sql_prep->execute($data);
            $result = $sql_prep->fetch();
        }


    }
}