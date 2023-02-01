<?php
require_once 'account_elements.php';
require 'html_page.php';
require_once 'purchase_functionality.php';
require_once 'billing_info.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Purchase invoice', styled: 'account/invoice.css', scripted: false);

ensure_session();

if ($_SESSION['auth']) {
    if (empty($_GET['tag'])) {
        ?>
        <div class="main-container">
            <span class="title">Invoices</span>
            <div class="history-outline">
                <div class="transaction-history">
                    <span class="purchase-title">Purchases and Gifts</span>
                    <?php render_items(); ?>
                </div>
            </div>
        </div>
        <?php
    } elseif (purchase_tag_exists($_GET['tag'])) {
        ?>
        <div class="main-container">
            <span class="title">Purchase and payment details</span>
            <div class="purchase-information">
                <?php product_information($_SESSION['uid'], $_GET['tag']) ?>
            </div>
            <div class='billing-container'>
                <?php render_billing_info(last_billing_info($_SESSION['uid']), info_by_tag($_GET['tag'])); ?>
            </div>
        </div>
        <?php
    } else {
        echo "This link seems wrong.";
    }
} else {
    echo "<span class='error'>Please log in</span>";
}


html_footer();
