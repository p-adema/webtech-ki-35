<?php
require 'html_page.php';
html_header(title: 'balance', styled: true);
?>
<div id="main-container">
    <div class="big-balance-box">
        <div class="balance">
            <?php
            require "bank_functionality.php";
            $user_id = 1;
            echo 'Dit is uw balans: €';
            echo get_balance($user_id);
            ?>
        </div>
    </div>
    <div class="main_pending_box">
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
        <div class="transaction-box">
            <div class="transaction-text">
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
