<?php

require "pdo_read.php";

function get_balance($user_id): string
{
    $pdo_read = new_pdo_read();

    $sql = 'SELECT (balance) FROM db.balances WHERE (user_id = :user)';

    $sth = $pdo_read->prepare($sql);
    $sth->execute(['user' => $user_id]);

    return $sth->fetch()['balance'];
}

function get_pending_transaction($user_id): array
{
    $pdo_read = new_pdo_read();

    $sql = 'SELECT amount, url_tag, request_time FROM db.transactions_pending WHERE (user_id = :user)';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['user' => $user_id]);

    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function get_transaction_log($user_id): array
{
    $pdo_read = new_pdo_read();

    $sql = 'SELECT amount, request_time, payment_time FROM db.transaction_log WHERE (user_id = :user)';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['user' => $user_id]);

    return $sth->fetchAll(PDO::FETCH_ASSOC);
}


function print_transaction($transaction): void
{
    echo '-€';
    echo $transaction['amount'];
    echo ' Aangevraagd op: ';
    echo $transaction['request_time'];
    echo '<br> En betaald op: ';
    echo $transaction['payment_time'];
}

function print_pending($pending): void
{
    echo '€';
    echo $pending['amount'];
    echo ' Aangevraagd op: ';
    echo $pending['request_time'];
}