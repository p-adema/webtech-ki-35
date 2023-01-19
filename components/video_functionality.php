<?php

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
    date_default_timezone_set("Europe/Amsterdam");

    $current_date = date("Y/m/d");
    $current_time = date("H:i:s");

    $current_year = intval(substr($current_date, 0, 4));
    $current_month = intval(substr($current_date, 5, 7));
    $current_day = intval(substr($current_date, 8, 10));
    $current_hour = intval(substr($current_time, 0, 2));
    $current_minute = intval(substr($current_time, 3, 5));
    $current_second = intval(substr($current_time, 6, 8));

    $upload_year = intval(substr($upload_date, 0, 4));
    $upload_month = intval(substr($upload_date, 5, 7));
    $upload_day = intval(substr($upload_date, 8, 10));
    $upload_hour = intval(substr($upload_date, 11, 13));
    $upload_minute = intval(substr($upload_date, 14, 16));
    $upload_second = intval(substr($upload_date, 17, 19));

    if ($current_year === $upload_year) {
        if ($current_month === $upload_month) {
            if ($current_day === $upload_day) {
                if ($current_hour === $upload_hour) {
                    if ($current_minute === $upload_minute) {
                        echo $current_second - $upload_second;
                        echo " seconds";
                    }
                    else {
                            echo $current_minute - $upload_minute;
                            echo " minutes";
                        }
                    }
                else {
                    echo $current_hour - $upload_hour;
                    echo " hours";
                }
            }
            else {
                echo $current_day - $upload_day;
                echo " days";
            }
        }
        else {
            echo $current_month - $upload_month;
            echo " months";
        }
    }
    else {
        echo $current_year - $upload_year;
        echo " years";
    }
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