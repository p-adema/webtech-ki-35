<?php
require 'html_page.php';
require "billing_info.php";
auth_redirect(if_not_auth: '/checkout/auth');
has_info_redirect('/checkout/billing');
html_header(title: 'Payment', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Confirm payment details </h1>
        <div class="form-outline">
            <form action="/api/cart/pay" method="POST">
                <?php
                form_error();
                $cart = new Cart();
                render_billing_info(last_billing_info($_SESSION['uid']), $cart->total());

                echo '<div class="form-btns">';
                text_link('Change billing information', '/checkout/billing');
                form_submit(text: 'Confirm');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
