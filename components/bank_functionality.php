<?php

function get_balance($user_id): string
{
    require_once "pdo_read.php";

    $sql = 'SELECT (balance) FROM db.balances WHERE (user_id = :user)';

    $sth = prepare_readonly($sql);
    $sth->execute(['user' => $user_id]);

    return $sth->fetch()['balance'];
}

function enough_balance($user_id, $tag): bool
{
    require_once "pdo_read.php";
    $user_bal = get_balance($user_id);


    $sql = 'SELECT (amount) FROM db.transactions_pending WHERE (url_tag = :url_tag)';

    $sth = prepare_readonly($sql);
    $sth->execute(['url_tag' => $tag]);

    $required = $sth->fetch()['amount'];

    return $user_bal > $required;
}

function get_pending_transaction($user_id): array
{
    require_once "pdo_read.php";

    $sql = 'SELECT amount, url_tag, request_time FROM db.transactions_pending WHERE (user_id = :user)';
    $sth = prepare_readonly($sql);
    $sth->execute(['user' => $user_id]);

    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function get_transaction_log($user_id): array
{
    require_once "pdo_read.php";

    $sql = 'SELECT amount, request_time, payment_time FROM db.transaction_log WHERE (user_id = :user)';
    $sth = prepare_readonly($sql);
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

    $sql = 'SELECT user_id, amount FROM db.transactions_pending WHERE (url_tag = :url_tag)';
    $sth = prepare_readonly($sql);
    $sth->execute(['url_tag' => $tag]);

    return $sth->fetch();
}

function confirm_payment($tag): void
{
    require_once 'pdo_write.php';

    $sql = 'CALL resolve_purchase(:url_tag);';
    $prep = prepare_write($sql);
    $prep->execute(['url_tag' => $tag]);


}

function deny_payment($tag): void
{
    require_once 'pdo_write.php';

    $sql = 'DELETE FROM db.transactions_pending WHERE (url_tag = :url_tag)';
    $sth = prepare_write($sql);
    $sth->execute(['url_tag' => $tag]);
}
