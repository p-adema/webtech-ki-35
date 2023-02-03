<?php

/**
 * Fail an API call if the caller is not an administrator
 * @return void
 */
function api_require_admin(): void
{
    ensure_session();
    if (!$_SESSION['admin']) {
        api_fail('Insufficient privileges');
    }
}

/**
 * @param string $username users.name
 * @return int|false users.id
 */
function user_id_from_name(string $username): int|false
{
    require_once 'pdo_read.php';

    $sql = 'SELECT id FROM db.users WHERE name = :uid';
    $sth = prepare_readonly($sql);
    $sth->execute(['uid' => $username]);

    $user = $sth->fetch();
    return $user !== false ? $user['id'] : false;
}

/**
 * @param string $item_tag items.tag
 * @return int|false items.id
 */
function item_id_from_tag(string $item_tag): int|false
{
    require_once 'pdo_read.php';

    $sql = 'SELECT id FROM db.items WHERE tag = :item_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['item_tag' => $item_tag]);

    $item = $sth->fetch();
    return $item !== false ? $item['id'] : false;
}

/**
 * @param string $comment_tag comments.tag
 * @return int|false comments.id
 */
function comment_id_from_tag(string $comment_tag): int|false
{
    require_once 'pdo_read.php';

    $sql = 'SELECT id FROM db.comments WHERE tag = :comment_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['comment_tag' => $comment_tag]);

    $item = $sth->fetch();
    return $item !== false ? $item['id'] : false;
}

/**
 * Gift a user an item
 * @param int $admin_uid User ID of the administrator providing the gift
 * @param int $reciever_uid User ID of the gift recipient
 * @param int $item_id Item ID of the item to be gifted
 * @param string $item_tag Item tag of the item to be gifted
 * @return bool Success
 */
function admin_gift_item(int $admin_uid, int $reciever_uid, int $item_id, string $item_tag): bool
{
    require_once 'pdo_write.php';

    $sql = 'CALL resolve_gift(:admin_id, :reciever_id, :item_id, :item_tag);';
    $prep = prepare_write($sql);
    $data = [
        'admin_id' => $admin_uid,
        'reciever_id' => $reciever_uid,
        'item_id' => $item_id,
        'item_tag' => $item_tag,
    ];
    return $prep->execute($data);
}

/**
 * Set the ban state of a user.
 * A banned user can't log in, and will have their sessions deauthenticated
 * @param int $target_uid User ID of the user to be banned
 * @param bool $ban On true, bans the user. On false, unbans them
 * @return bool Success of the state change. Will succeed even if no change was made
 */
function admin_set_user_banned(int $target_uid, bool $ban): bool
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

/**
 * Set the restriction state of an item.
 * A restricted item can't be viewed and won't show up in the global search
 * @param int $item_id Item ID of the item to be restricted
 * @param bool $restrict On true, restricts the item. On false, unrestricts it
 * @return bool Success of the state change. Will succeed even if no change was made
 */
function admin_set_item_restricted(int $item_id, bool $restrict): bool
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

/**
 * Sets the hidden state of a comment
 * A hidden comment can only be viewed by administrators. Replies are preserved
 * @param int $comment_id Comment ID of the comment to be hidden
 * @param bool $hide On true, hides the comment. On false, unhides it
 * @return bool Success of the state change. Will succeed even if no change was made
 */
function admin_set_comment_hidden(int $comment_id, bool $hide): bool
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
