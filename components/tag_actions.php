<?php

function tag_create(int $length = 64): string
{
    $permitted_characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $perm_char_count = 62;
    $new_tag = '';
    for ($i = 0; $i < $length; $i++) {
        $new_tag .= $permitted_characters[rand(0, $perm_char_count - 1)];
    }
    return $new_tag;
}


function email_tag_check(string $tag, string $type): bool
{
    $valid = false;
    require_once "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {
        return false;
    }


    $sql = 'SELECT (user_id) FROM db.emails_pending WHERE (url_tag = :tag) AND (type = :type);';
    $data = [
        'tag' => $tag,
        'type' => $type
    ];

    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute($data);
    $user_id_fetch = $sql_prep->fetch();

    return !empty($user_id_fetch);
}
