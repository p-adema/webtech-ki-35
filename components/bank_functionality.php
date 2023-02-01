<?php

function get_balance($user_id): string
{
    require_once "pdo_read.php";
    $pdo_read = new_pdo_read();

    $sql = 'SELECT (balance) FROM db.balances WHERE (user_id = :user)';

    $sth = $pdo_read->prepare($sql);
    $sth->execute(['user' => $user_id]);

    return $sth->fetch()['balance'];
}

function enough_balance($user_id, $tag): bool
{
    require_once "pdo_read.php";
    $user_bal = get_balance($user_id);

    $pdo_read = new_pdo_read();

    $sql = 'SELECT (amount) FROM db.transactions_pending WHERE (url_tag = :url_tag)';

    $sth = $pdo_read->prepare($sql);
    $sth->execute(['url_tag' => $tag]);

    $required = $sth->fetch()['amount'];

    return $user_bal > $required;
}

function get_pending_transaction($user_id): array
{
    require_once "pdo_read.php";
    $pdo_read = new_pdo_read();

    $sql = 'SELECT amount, url_tag, request_time FROM db.transactions_pending WHERE (user_id = :user)';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['user' => $user_id]);

    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function get_transaction_log($user_id): array
{
    require_once "pdo_read.php";
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
        echo "<a href='/bank/verify?tag=$tag'><button class='pay-button' type='submit'>Pay now</button></a>";
    echo '</div>';
}

function obtain_user_information($tag): array
{
    require_once "pdo_read.php";
    $pdo_read = new_pdo_read();

    $sql = 'SELECT user_id, amount FROM db.transactions_pending WHERE (url_tag = :url_tag)';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['url_tag' => $tag]);

    return $sth->fetch();
}

function confirm_payment($tag): void
{
    require_once 'pdo_write.php';
    $pdo_write = new_pdo_write();

    $sql = 'CALL resolve_purchase(:url_tag);';
    $prep = $pdo_write->prepare($sql);
    $prep->execute(['url_tag' => $tag]);


}

function deny_payment($tag): void
{
    require_once 'pdo_write.php';
    $pdo_write = new_pdo_write();

    $sql = 'DELETE FROM db.transactions_pending WHERE (url_tag = :url_tag)';
    $sth = $pdo_write->prepare($sql);
    $sth->execute(['url_tag' => $tag]);
}

function add_balance($user_id, $input): void
{
    require_once "pdo_write.php";
    $current_balance = get_balance($user_id);

    $pdo_write = new_pdo_write();

    $sql = 'UPDATE db.balances SET balance = :new_bal WHERE (user_id = :person)';
    $sth = $pdo_write->prepare($sql);
    $sth->execute(['person' => $user_id, 'new_bal' => $current_balance.$input]);
}
