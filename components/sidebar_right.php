<?php
require_once 'form_elements.php';
require_once 'Cart.php';


function sidebarRight(): string
{
    $cart = new Cart;
    $items_html = '';
    $items = $cart->items_long();
    $items_empty = "
<span class='cart-items-empty'>
    You don't seem to have anything in your cart.
    Browse videos and courses to find new items!
</span>
";
    foreach ($items as $item) {
        $link = '/courses/' . ($item['type'] === 'video' ? 'video/' : 'course/') . $item['tag'];
        $items_html .= "
<a class='cart-item-anchor' href='$link' tag='{$item['tag']}'>
    <div class='cart-item-wrapper'> 
        <span class='cart-item-name'> {$item['name']} </span> 
        <span class='cart-item-price'> €{$item['price']} </span> 
        <span class='cart-item-delete material-symbols-outlined' tag='{$item['tag']}'> cancel </span>
    </div> 
</a>";

    }

    return "
<div class='sidebar-right sidebar-block sidebar_animate_right'>
    <button onclick='close_right_menu()' class='sidebar-close'>Close</button>
    $items_html
    $items_empty
    <div class='checkout-sidebar'>
        <button onclick='go_to_checkout()' class='checkout-button' type='button'>Continue to cart</button> 
    </div>
</div>";
}


function sidebar_cover(): string
{
    return "<span class='sidebar-active-cover hidden'></span>";
}
