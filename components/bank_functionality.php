<?php

/**
 * Get the bank balance of a user
 * @param int $user_id users.id
 * @return float balances.balance
 */
function user_balance_from_id(int $user_id): float
{
    require_once "pdo_read.php";

    $sql = 'SELECT (balance) FROM db.balances WHERE (user_id = :user)';

    $prep = prepare_readonly($sql);
    $prep->execute(['user' => $user_id]);

    return $prep->fetch()['balance'];
}

/**
 * Checks whether a user has sufficient balance for a transaction
 * @param string $transaction_tag transactions_pending.url_tag
 * @return bool Whether the user has sufficent balance
 */
function transaction_sufficient_balance(string $transaction_tag): bool
{
    require_once "pdo_read.php";

    $sql = 'SELECT amount, user_id FROM db.transactions_pending WHERE (url_tag = :url_tag)';

    $prep = prepare_readonly($sql);
    $prep->execute(['url_tag' => $transaction_tag]);

    $results = $prep->fetch();
    $required = $results['amount'];
    $user_bal = user_balance_from_id($results['user_id']);

    return $user_bal > $required;
}

/**
 * Fetches all pending transactions for a user
 * @param int $user_id users.id
 * @return array amount, url_tag, request_time FROM transactions_pending
 */
function user_pending_transactions(int $user_id): array
{
    require_once "pdo_read.php";

    $sql = 'SELECT amount, url_tag, request_time FROM db.transactions_pending WHERE (user_id = :user)';
    $prep = prepare_readonly($sql);
    $prep->execute(['user' => $user_id]);

    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetches the transaction log of a user
 * @param int $user_id users.id
 * @return array amount, request_time, payment_time FROM transaction_log
 */
function user_transaction_log(int $user_id): array
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
