<?php
require_once "relative_time.php";
require_once "rating_functionality.php";

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

function update_rating($rating, $uid, $tag): void
{
    require_once 'pdo_read.php';
    require_once 'pdo_write.php';

    $message = " ";

    $pdo_read = new_pdo_read();
    $pdo_write = new_pdo_write();

    $sql_get_id = 'SELECT id FROM db.items WHERE (tag = :name)';
    $sth_get_id = $pdo_read->prepare($sql_get_id);
    $sth_get_id->execute(['name' => $tag]);

    $video_id = $sth_get_id->fetch()['id'];

    $sql_read = 'SELECT rating FROM db.ratings WHERE item_id = :id AND rater_id = :uid';

    $sth_read = $pdo_read->prepare($sql_read);
    $sth_read->execute(['id' => $video_id, 'uid' => $uid]);

    $data = $sth_read->fetch()['rating'];

    if (empty($data)) {
        $sql_new = 'INSERT INTO db.ratings (rater_id, item_id, rating, text)
                    VALUES (:rater, :item, :stars, :message)';
        $sth = $pdo_write->prepare($sql_new);
        $sth->execute(['rater' => $uid, 'item' => $video_id, 'stars' => intval($rating), 'message' => $message]);
    }
    else {
        $sql_update = 'UPDATE db.ratings SET rating = :new_rating WHERE rater_id = :rater and item_id = :video';
        $sth = $pdo_write->prepare($sql_update);
        $sth->execute(['new_rating' => $rating, 'rater' => $uid, 'video' => $video_id]);
    }

    calculate_rating($video_id);
}

//function get_video_watch_amount($uid, $video_tag): float
//{
//    require_once 'pdo_read.php';
//
//    $pdo_read = new_pdo_read();
//
//    $sql = 'SELECT watch_amount FROM db.watches WHERE user_id = :uid AND video_tag = :video_tag';
//    $sth = $pdo_read->prepare($sql);
//    $sth->execute(['uid' => $uid, 'video_tag' => $video_tag]);
//
//    return $sth->fetch()['watch_amount'];
//}