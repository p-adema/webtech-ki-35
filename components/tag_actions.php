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


function email_tag_check(string $tag): string|false
{
    $valid = false;
    require_once "pdo_write.php";

    $sql = 'SELECT (type) FROM db.emails_pending WHERE (url_tag = :tag);';
    $data = [
        'tag' => $tag,
    ];

    $sql_prep = prepare_write($sql);
    $sql_prep->execute($data);
    $type_fetched = $sql_prep->fetch();
    if (empty($type_fetched)) {
        return false;
    }
    return $type_fetched['type'];
}
