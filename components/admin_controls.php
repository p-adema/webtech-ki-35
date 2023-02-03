<?php

function is_admin($uid): bool
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT admin FROM db.users WHERE id = :uid';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['uid' => $uid]);

    return $sth->fetch()['admin'];
}

function api_require_admin(): void
{
    ensure_session();
    if (!$_SESSION['admin']) {
        api_fail('Insufficient privileges');
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

function item_id_from_tag(string $item_tag): int|false
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT id FROM db.items WHERE tag = :item_tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['item_tag' => $item_tag]);

    $item = $sth->fetch();
    return $item !== false ? $item['id'] : false;
}

function comment_id_from_tag($comment_tag): int|false
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT id FROM db.comments WHERE tag = :comment_tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['comment_tag' => $comment_tag]);

    $item = $sth->fetch();
    return $item !== false ? $item['id'] : false;
}

function admin_gift_item($admin_uid, $reciever_uid, $item_id, $item_tag): bool
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

function remove_video($video_tag): void
{
    require_once 'pdo_write.php';

    $pdo_write = new_pdo_write();

    $sql = 'DELETE FROM db.videos WHERE tag = :video_tag';
    $sth = $pdo_write->prepare($sql);
    $sth->execute(['video_tag' => $video_tag]);
}

function admin_ban_user(int $target_uid, bool $ban = true): bool
{
    require_once 'pdo_write.php';

    $pdo_write = new_pdo_write();

    $sql = 'UPDATE users SET banned = :ban WHERE id = :uid';
    $prep = $pdo_write->prepare($sql);
    $data = [
        'uid' => $target_uid,
        'ban' => $ban ? 1 : 0
    ];
    return $prep->execute($data);
}

function admin_restrict_item(int $item_id, bool $restrict = true): bool
{
    require_once 'pdo_write.php';

    $pdo_write = new_pdo_write();

    $sql = 'UPDATE items SET restricted = :restrict WHERE id = :iid';
    $prep = $pdo_write->prepare($sql);
    $data = [
        'iid' => $item_id,
        'restrict' => $restrict ? 1 : 0
    ];
    return $prep->execute($data);
}

function admin_hide_comment(int $comment_id, bool $hide = true): bool
{
    require_once 'pdo_write.php';

    $pdo_write = new_pdo_write();

    $sql = 'UPDATE comments SET hidden = :hide WHERE id = :cid';
    $prep = $pdo_write->prepare($sql);
    $data = [
        'cid' => $comment_id,
        'hide' => $hide ? 1 : 0
    ];
    return $prep->execute($data);
}
