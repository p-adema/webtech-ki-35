<?php
require 'html_page.php';
html_header(title: 'Checkout', styled: true, scripted: 'ajax');
require "review_components.php";
require "billing_info.php";
?>
    <div class="content-wrapper">
        <h1> Review cart items: </h1>
        <div class="items-wrapper">

            <?php
            $cart = new Cart;

            $items = $cart->items_long();
            foreach ($items as $item) {
                review_item($item);
            }
            echo "<div class='checkout-empty-wrapper'><span class='checkout-empty-text'> You have nothing in your cart </span> <a href='/' class='link-back'> Go back </a></div>";
            $has_info = ($_SESSION['auth'] and (last_billing_info($_SESSION['uid']) !== false));
            review_checkout($cart->total(), $has_info);
            ?>
        </div>
    </div>

<?php html_footer();
