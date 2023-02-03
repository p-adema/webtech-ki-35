<?php
require_once "relative_time.php";
require_once "rating_functionality.php";

function get_video_data($video_tag): array|false
{
    require_once 'pdo_read.php';


    $sql = 'SELECT name, description, subject, uploader, upload_date, views, restricted, r.rating
            FROM db.videos v 
                INNER JOIN items i on v.tag = i.tag
                LEFT JOIN ratings r on i.id = r.item_id and r.rater_id = :uid
            WHERE v.tag = :video_tag';

    $sth = prepare_readonly($sql);

    ensure_session();
    $data = [
        'video_tag' => $video_tag,
        'uid' => $_SESSION['uid'] ?? null
    ];
    $sth->execute($data);

    return $sth->fetch(PDO::FETCH_ASSOC);
}

function check_video_tag($tag): bool
{
    $valid = false;
    require "pdo_write.php";
    try {
        $pdo_write = new_pdo_write();
    } catch (PDOException) {
        return false;
    }


    $sql = 'SELECT (user_id) FROM db.videos WHERE (tag = :tag)';

    $sql_prep = prepare_write($sql);
    $sql_prep->execute(['tag' => $tag]);

    return !empty($sql_prep->fetch());
}

function owns_video($user, $video_id): bool
{
    require_once 'pdo_read.php';


    $sql = 'SELECT origin FROM db.ownership WHERE user_id = :user AND item_tag = :video_id';
    $sth = prepare_readonly($sql);
    $sth->execute(['user' => $user, 'video_id' => $video_id]);

    return !empty($sth->fetch());
}

function video_cost($video_id): bool
{
    require_once 'pdo_read.php';


    $sql = 'SELECT free FROM db.videos WHERE (tag = :video_id)';
    $sth = prepare_readonly($sql);
    $sth->execute(['video_id' => $video_id]);

    return $sth->fetch()['free'];
}

function update_rating($rating, $uid, $tag): bool
{
    require_once 'pdo_read.php';
    require_once 'pdo_write.php';

    $message = " ";

    $pdo_write = new_pdo_write();

    $sql_get_id = 'SELECT id FROM db.items WHERE (tag = :name)';
    $sth_get_id = prepare_readonly($sql_get_id);
    $sth_get_id->execute(['name' => $tag]);

    $item = $sth_get_id->fetch();

    if ($item === false) {
        return false;
    }

    $item_id = $item['id'];

    $sql_read = 'SELECT rating FROM db.ratings WHERE item_id = :id AND rater_id = :uid';

    $sth_read = prepare_readonly($sql_read);
    $sth_read->execute(['id' => $item_id, 'uid' => $uid]);

    $data = $sth_read->fetch()['rating'];

    if (empty($data)) {
        $sql_new = 'INSERT INTO db.ratings (rater_id, item_id, rating, text)
                    VALUES (:rater, :item, :stars, :message)';
        $sth = prepare_write($sql_new);
        $sth->execute(['rater' => $uid, 'item' => $item_id, 'stars' => intval($rating), 'message' => $message]);
    }
    else {
        $sql_update = 'UPDATE db.ratings SET rating = :new_rating WHERE rater_id = :rater and item_id = :video';
        $sth = prepare_write($sql_update);
        $sth->execute(['new_rating' => $rating, 'rater' => $uid, 'video' => $item_id]);
    }

    calculate_rating($item_id);
    return true;
}

function user_name_from_id($uid): string
{
    require_once 'pdo_read.php';

    $sql = 'SELECT name FROM db.users WHERE id = :uid';
    $sth = prepare_readonly($sql);
    $sth->execute(['uid' => $uid]);

    return $sth->fetch()['name'];

}
