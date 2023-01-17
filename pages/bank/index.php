<?php
require 'html_page.php';
html_header(title: 'balance', styled: true);
?>
<div class="main-container">
    <div class="background-box">
        <div class="title-box">
            <span class="title">
                NietNG
            </span>
        </div>
    </div>
    <div class="big-balance-box">
        <span class="balance-header">Current balance</span>
        <div class="balance">
            <?php
            require "bank_functionality.php";
            $user_id = 1;
            echo "<span class='balance-text'>€</span>";
            echo '<div class="balance-amount">';
            echo get_balance($user_id);
            echo '</div>'
            ?>
        </div>
    </div>
    <div class="big-transaction-box">
        <div class="main-pending-box">
            <span class="pending-header">Transactions waiting for confirmation</span>
            <div class="pending_box">
                <?php
                $all_current_transactions = get_pending_transaction($user_id);
                foreach ($all_current_transactions as $pending_transaction) {
                    print_pending($pending_transaction);
                    echo "<br>";
                }
                ?>
            </div>
        </div>
        <div class="all-transactions">
            <span class="transaction-title"> Transaction log</span>
            <div class="transaction-box">
                <?php
                $past_transactions = get_transaction_log($user_id);
                foreach ($past_transactions as $transaction) {
                    print_transaction($transaction);
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php html_footer();

/*
 * Taak 1:
 * TODO:
 *      Mooi geformateerde balans (miss .00 kleiner)
 *      Pending transactions als die er zijn (anders hide)
 *      Transaction log
 */
