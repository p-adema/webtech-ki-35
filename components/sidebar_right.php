<?php
require_once 'form_elements.php';
require_once 'Cart.php';


function sidebarRight(): string{
    $cart = new Cart;
    $html = '';
    foreach ($cart->items_long() as $item) {
        $html .= "<a href='/'> {$item['name']} </a>";

    }
    return "<div class='sidebar_right sidebar_block sidebar_animate_right'>
                <button onclick='closeRightMenu()' class='sidebar_close'>Close</button>
                <p class='sidebar_text'> Shopping cart: </p>
                <hr>".
                $html . " <hr> 
                <div class='checkout_sidebar'>
                <button onclick='go_to_checkout()' id='checkout_sidebar' type='button'>Checkout</button> </div>
            </div>";
}
// animation dropdown
// click next to sidebar leave
