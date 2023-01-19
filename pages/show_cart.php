<?php
require 'html_page.php';
require "Cart.php";

html_header(title: 'Shopping cart', styled: false, scripted: false);

?>
    <div style="width: 100vw; display: flex; align-items: center; justify-content: center; flex-flow: column">
    <h1>Shopping cart</h1>
    <div style="width: 50%">
        <table style="width: 100%; text-align: center">
            <tr>
                <th style="width: 33%;">Tag</th>
                <th style="width: 33%;">Name</th>
                <th style="width: 33%;">Price</th>
            </tr>

            <?php
            $cart = new Cart;

            foreach ($cart->items_long() as $item) {
                $html = "
        <tr>
            <td> {$item['tag']} </td>
            <td> {$item['name']}</td>
            <td> {$item['price']} </td>
        </tr>
        ";
                echo $html;
            }
            $pdo_read = null;
            ?>

        </table>
    </div>
<?php
require_once "link.php";
text_link('Go back to home', '/');
echo '</div>';

html_footer();
