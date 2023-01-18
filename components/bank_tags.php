<?php

function check_tag($tag): bool
{
    $valid = false;
    require "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {
        return false;
    }


    $sql = 'SELECT (user_id) FROM db.transactions_pending WHERE (url_tag = :tag)';

    $sql_prep = $pdo_write->prepare($sql);
    $sql_prep->execute(['tag' => $tag]);
    $user_id_fetch = $sql_prep->fetch();

    return !empty($user_id_fetch);
}
