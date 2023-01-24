<?php
require 'html_page.php';
html_header(title: 'Checkout', styled: 'checkout.css', scripted: 'ajax');
require "checkout_components.php";
require "billing_info.php";
?>
    <div class="content-wrapper">
        <h1> Review cart items: </h1>
        <div class="items-wrapper">

            <?php
            $cart = new Cart;

            $items = $cart->items_long();
            if (empty($items)) {
                echo '<span class="checkout-empty"> You have nothing in your cart </span> <a href="/"> Go home </a>';
            } else {
                foreach ($items as $item) {
                    review_item($item);
                }
            }
            $has_info = ($_SESSION['auth'] and (last_billing_info($_SESSION['uid']) !== false));
            review_checkout($cart->total(), $has_info);
            ?>
        </div>
    </div>

<?php html_footer();
