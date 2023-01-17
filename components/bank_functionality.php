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
    echo  '<div class="transaction-boxy">';
        echo '<div class="amount-text"> -€';
            echo $transaction['amount'];
        echo '</div>';
        echo '<div class="payment-when">';
            echo substr($transaction['payment_time'], 0, 10);
        echo "</div>";
    echo '</div>';
}

function print_pending($pending): void
{
    $tag = $pending['url_tag'];

    echo  '<div class="transaction-boxy">';
        echo '<div class="amount-text"> -€';
            echo $pending['amount'];
        echo '</div>';
        echo '<div class="payment-when">';
            echo substr($pending['request_time'], 0, 10);
        echo "</div>";
        echo "<form action='/bank/verify.php?tag=$tag' method='get' target='_blank'><button class='pay-button' type='submit'>Pay now</button>";
    echo '</div>';
}

function obtain_user_information($tag): string
{
    $pdo_read = new_pdo_read();

    $sql = 'SELECT (user_id) FROM db.transactions_pending WHERE (url_tag = :url_tag)';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['url_tag' => $tag]);

    return $sth->fetch();
}

function confirm_payment($user_id): void
{

}

function deny_payment($user_id): void
{

}