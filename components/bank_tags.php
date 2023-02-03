<?php

function check_tag($tag): bool
{
    $valid = false;
    require "pdo_write.php";
    try {
        $pdo_write = new_pdo_write();
    } catch (PDOException) {
        return false;
    }


    $sql = 'SELECT (user_id) FROM db.transactions_pending WHERE (url_tag = :tag)';

    $sql_prep = prepare_write($sql);
    $sql_prep->execute(['tag' => $tag]);
    $user_id_fetch = $sql_prep->fetch();

    return !empty($user_id_fetch);
}
