<?php

/**
 * Checks a bank verifcation tag for validity
 * @param string $url_tag transactions_pending.url_tag
 * @return bool Validity of tag
 */
function check_tag(string $url_tag): bool
{
    $valid = false;
    require "pdo_write.php";

    $sql = 'SELECT (user_id) FROM db.transactions_pending WHERE (url_tag = :tag)';

    $sql_prep = prepare_write($sql);
    $sql_prep->execute(['tag' => $url_tag]);
    $user_id_fetch = $sql_prep->fetch();

    return !empty($user_id_fetch);
}
