<?php
require 'html_page.php';
html_header(title: 'Checkout', styled: 'checkout.css', scripted: false);
require "checkout_components.php";
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
            review_checkout($cart->total());
            ?>
        </div>
    </div>

<?php html_footer();
