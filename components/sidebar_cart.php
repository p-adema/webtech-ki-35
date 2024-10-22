<?php
require_once 'form_elements.php';
require_once 'Cart.php';

function render_cart_item(array $item): string
{
    $link = '/courses/' . ($item['type'] === 'video' ? 'video/' : 'course/') . $item['tag'];
    return "
<a class='cart-item-anchor' href='$link' data-tag='{$item['tag']}'>
    <div class='cart-item-wrapper'> 
        <span class='cart-item-name'> {$item['name']} </span> 
        <span class='cart-item-price'> €{$item['price']} </span> 
        <span class='cart-item-delete material-symbols-outlined' data-tag='{$item['tag']}'> cancel </span>
    </div> 
</a>";
}

function render_cart_items(): string
{
    $cart = new Cart;
    $items = $cart->items_long();
    $items_rendered = [];
    foreach ($items as $item) {
        $items_rendered[] = render_cart_item($item);
    }
    return join(PHP_EOL, $items_rendered);
}

function render_sidebar_cart(): string
{
    $items_empty = "
<span class='cart-items-empty'>
    You don't seem to have anything in your cart.
    Browse videos and courses to find new items!
</span>
";
    $items_html = render_cart_items();
    $cart_go = '<span class="material-symbols-outlined">shopping_cart_checkout</span>';
    return "
<div class='sidebar-right sidebar-block sidebar_animate_right'>
    <button class='sidebar-close'>Close</button>
    $items_html
    $items_empty
    <div class='checkout-sidebar'>
        <a href='/checkout/review' class='checkout-button'>$cart_go <span> Continue to cart </span></a> 
    </div>
</div>";
}


function sidebar_cover(): string
{
    return "<span class='sidebar-active-cover hidden'></span>";
}
