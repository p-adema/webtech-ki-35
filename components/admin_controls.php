<?php

function is_admin($uid): bool
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT admin FROM db.users WHERE id = :uid';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['uid' => $uid]);

    $yes_or_no = $sth->fetch()['admin'];

    if ($yes_or_no === 1) {
        return true;
    }
    else {
        return false;
    }
}

function username($uid): int|false
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT id FROM db.users WHERE name = :uid';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['uid' => $uid]);

    return $sth->fetch()['id'];
}

function item($item_tag): int|false
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT id FROM db.items WHERE tag = :item_tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['item_tag' => $item_tag]);

    return $sth->fetch()['id'];
}

function gift_item($aid, $uid, $item_id, $item_tag): void
{
    require_once 'pdo_write.php';
    require_once 'pdo_read.php';

    $pdo_write = new_pdo_write();
    $pdo_read = new_pdo_read();

    $sql_gift_log = 'INSERT INTO db.gifts (item_id, user_id, admin_id, confirmation_time)
                    VALUES (:item_id, :uid, :admin_id, DEFAULT)';
    $sth_gift_log = $pdo_write->prepare($sql_gift_log);
    $sth_gift_log->execute(['item_id' => $item_id, 'uid' => $uid, 'admin_id' => $aid]);

    $sql_gift_id = 'SELECT id FROM db.gifts WHERE user_id = :uid AND item_id = :item_id';
    $sth_gift_id = $pdo_read->prepare($sql_gift_id);
    $sth_gift_id->execute(['uid' => $uid, 'item_id' => $item_id]);

    $gift_id = $sth_gift_id->fetch()['id'];

    $sql_ownership = 'INSERT INTO db.ownership (item_tag, user_id, origin, gift_id)
                    VALUES (:item_tag, :uid, :origin, :gift_id)';
    $sth_ownership = $pdo_write->prepare($sql_ownership);
    $sth_ownership->execute(['item_tag' => $item_tag, 'uid' => $uid, 'origin' => 'gift', 'gift_id' => $gift_id]);
}

function remove_comment($comment_tag): void
{
    require_once 'pdo_write.php';

    $pdo_write = new_pdo_write();

    $sql = 'DELETE FROM db.comments WHERE tag = :comment';
    $sth = $pdo_write->prepare($sql);
    $sth->execute(['comment' => $comment_tag]);
}

function remove_video($video_tag): void
{
    require_once 'pdo_write.php';

    $pdo_write = new_pdo_write();

    $sql = 'DELETE FROM db.videos WHERE tag = :video_tag';
    $sth = $pdo_write->prepare($sql);
    $sth->execute(['video_tag' => $video_tag]);
}