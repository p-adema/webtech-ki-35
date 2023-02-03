<?php

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

    $sql = 'SELECT id FROM db.users WHERE name = :uid';
    $sth = prepare_readonly($sql);
    $sth->execute(['uid' => $uid]);

    $user = $sth->fetch();
    return $user !== false ? $user['id'] : false;
}

function item_id_from_tag(string $item_tag): int|false
{
    require_once 'pdo_read.php';

    $sql = 'SELECT id FROM db.items WHERE tag = :item_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['item_tag' => $item_tag]);

    $item = $sth->fetch();
    return $item !== false ? $item['id'] : false;
}

function comment_id_from_tag($comment_tag): int|false
{
    require_once 'pdo_read.php';

    $sql = 'SELECT id FROM db.comments WHERE tag = :comment_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['comment_tag' => $comment_tag]);

    $item = $sth->fetch();
    return $item !== false ? $item['id'] : false;
}

function admin_gift_item($admin_uid, $reciever_uid, $item_id, $item_tag): bool
{
    require_once 'pdo_write.php';

    $sql = 'CALL resolve_purchase(:admin_id, :reciever_id, :item_id, :item_tag);';
    $prep = prepare_write($sql);
    $data = [
        'admin_id' => $admin_uid,
        'reciever_id' => $reciever_uid,
        'item_id' => $item_id,
        'item_tag' => $item_tag,
    ];
    return $prep->execute($data);
}

function admin_ban_user(int $target_uid, bool $ban = true): bool
{
    require_once 'pdo_write.php';


    $sql = 'UPDATE users SET banned = :ban WHERE id = :uid';
    $prep = prepare_write($sql);
    $data = [
        'uid' => $target_uid,
        'ban' => $ban ? 1 : 0
    ];
    return $prep->execute($data);
}

function admin_restrict_item(int $item_id, bool $restrict = true): bool
{
    require_once 'pdo_write.php';


    $sql = 'UPDATE items SET restricted = :restrict WHERE id = :iid';
    $prep = prepare_write($sql);
    $data = [
        'iid' => $item_id,
        'restrict' => $restrict ? 1 : 0
    ];
    return $prep->execute($data);
}

function admin_hide_comment(int $comment_id, bool $hide = true): bool
{
    require_once 'pdo_write.php';


    $sql = 'UPDATE comments SET hidden = :hide WHERE id = :cid';
    $prep = prepare_write($sql);
    $data = [
        'cid' => $comment_id,
        'hide' => $hide ? 1 : 0
    ];
    return $prep->execute($data);
}
