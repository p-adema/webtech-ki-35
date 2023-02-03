<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'balance', navbar: false, styled: true, scripted: 'ajax');

if ($_SESSION['auth']) :
    $user_id = $_SESSION['uid']
    ?>

    <div class="main-container">
        <div class="background-box">
            <a href="/">
                <div class="title-box">
                    <span class="title"> NietNG </span>
                </div>
            </a>
        </div>
        <div class="big-balance-box">
            <span class="balance-header">Current balance</span>
            <div class="balance">
                <?php
                require "bank_functionality.php";
                $user_id = $_SESSION['uid'];
                echo "<span class='balance-text'>€</span>";
                echo '<div class="balance-amount">';
                echo number_format(user_balance_from_id($user_id), 2);
                echo '</div>'
                ?>
            </div>
        </div>
        <div class="big-transaction-box">
            <div class="main-pending-box">
                <span class="pending-header">Transactions waiting for confirmation</span>
                <div class="pending_box">
                    <?php
                    $all_current_transactions = user_pending_transactions($user_id);
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
                    $past_transactions = user_transaction_log($user_id);
                    foreach ($past_transactions as $transaction) {
                        print_transaction($transaction);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endif;

html_footer();
