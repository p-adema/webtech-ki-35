<?php

require "pdo_read.php";

function get_balance($user_id): float
{
    $pdo_read = new_pdo_read();


    $sql = 'SELECT (balance) FROM db.balances WHERE (user_id = :user)';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['user' => $user_id]);

    return $sth->fetch()['balance'];
}
