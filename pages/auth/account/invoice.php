<?php
require_once 'account_elements.php';
require 'html_page.php';
require_once 'purchase_functionality.php';
require_once 'billing_info.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Purchase invoice', styled: 'account/invoice.css', scripted: 'ajax');

ensure_session();

$url_tag = $_GET['tag'] ?? '';
if (empty($url_tag)) {
    ?>
    <div class="main-container">
        <span class="title">Invoices</span>
        <div class="history-outline">
            <div class="transaction-history">
                <span class="purchase-title">Purchases and Gifts</span>
                <?php display_invoices(); ?>
            </div>
        </div>
    </div>
    <?php
} elseif (purchase_tag_exists($url_tag)) {
    ?>
    <div class="main-container">
        <span class="title">Purchase and payment details</span>
        <a href="/auth/account/invoice">
            <span class="invoice-back"> Back to all invoices </span>
        </a>
        <div class='billing-container'>
            <?php display_purchase_billing_info($url_tag); ?>
        </div>
        <div class="purchase-information">
            <?php display_product_information($_SESSION['uid'], $url_tag) ?>
        </div>
    </div>
    <?php
} else {
    ?>
    <link rel='stylesheet' href='/styles/form.css' type='text/css'>

    <div class="form-content">
        <h1> Invalid link </h1>
        <div class="form-outline">
            <form>
                <p> This link doesn't seem quite right </p>
                <?php
                echo '<div class="form-btns">';
                display_text_link('Go back', '/auth/account/invoice');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>
    <?php
}


html_footer();
