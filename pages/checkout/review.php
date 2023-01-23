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

            foreach ($cart->items_long() as $item) {
                render_review_item($item);
            }
            $pdo_read = null;
            ?>
        </div>
    </div>
<?php html_footer();
