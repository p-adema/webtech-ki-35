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

function user_id_from_name($uid): int|false
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT id FROM db.users WHERE name = :uid';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['uid' => $uid]);

    $user = $sth->fetch();
    return $user !== false ? $user['id'] : false;
}

function item_id_from_tag($item_tag): int|false
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT id FROM db.items WHERE tag = :item_tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['item_tag' => $item_tag]);

    $item = $sth->fetch();
    return $item !== false ? $item['id'] : false;
}

function gift_item($admin_uid, $reciever_uid, $item_id, $item_tag): bool
{
    require_once 'pdo_write.php';
    $pdo_write = new_pdo_write();

    $sql = 'CALL resolve_purchase(:admin_id, :reciever_id, :item_id, :item_tag);';
    $prep = $pdo_write->prepare($sql);
    $data = [
        'admin_id' => $admin_uid,
        'reciever_id' => $reciever_uid,
        'item_id' => $item_id,
        'item_tag' => $item_tag,
    ];
    return $prep->execute($data);
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
