<?php
require_once "relative_time.php";

function get_video_data($video_tag): array|false
{
    require_once 'pdo_read.php';

    $new_pdo_read = new_pdo_read();

    $sql = 'SELECT name, description, subject, uploader, upload_date, views FROM db.videos WHERE (tag = :video_tag)';
    $sth = $new_pdo_read->prepare($sql);
    $sth->execute(['video_tag' => $video_tag]);

    return $sth->fetch(PDO::FETCH_ASSOC);
}

function check_video_tag($tag): bool
{
    $valid = false;
    require "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {
        return false;
    }


    $sql = 'SELECT (user_id) FROM db.videos WHERE (tag = :tag)';

    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute(['tag' => $tag]);

    return !empty($sql_prep->fetch());
}

function since_upload($upload_date): void
{
    echo time_since($upload_date);
}

function owns_video($user, $video_id): bool
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT origin FROM db.ownership WHERE user_id = :user AND item_tag = :video_id';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['user' => $user, 'video_id' => $video_id]);

    return !empty($sth->fetch());
}

function video_cost($video_id): bool
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT free FROM db.videos WHERE (tag = :video_id)';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['video_id' => $video_id]);

    return $sth->fetch()['free'];
}
