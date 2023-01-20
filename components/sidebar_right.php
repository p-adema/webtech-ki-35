<?php
require_once 'form_elements.php';
require_once 'Cart.php';


function sidebarRight(): string{
    $cart = new Cart;
    $html = '';
    foreach ($cart->items_long() as $item) {
        $html .= "<a href='/'> {$item['name']} </a>";

    }
    return "<div class='sidebar-right sidebar-block sidebar_animate_right'>
                <button onclick='close_right_menu()' class='sidebar-close'>Close</button>
                <p class='sidebar-text'> Shopping cart: </p>
                <hr>" .
                $html . " <hr> 
                <div class='checkout-sidebar'>
                <button onclick='go_to_checkout()' id='checkout-sidebar' type='button'>Checkout</button> </div>
            </div>";
}
// animation dropdown


function sidebar_cover(): string
{
    return "<span class='sidebar-active-cover hidden'></span>";
}