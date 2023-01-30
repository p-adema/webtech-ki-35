<?php

function review_item(array $item): void
{
    $link = '/courses/' . ($item['type'] === 'video' ? 'video/' : 'course/') . $item['tag'];
    $icon = $item['type'] === 'video' ? 'movie' : 'library_books';
    $price = number_format($item['price'], 2);
    echo "
<a class='review-item-anchor' href='$link' tag='{$item['tag']}'>
    <div class='review-item-wrapper'>
        <span class='review-item-icon material-symbols-outlined'> $icon </span>
        <span class='review-item-name'> {$item['name']} </span>
        <span class='review-item-price'> €{$price} </span>
        <span class='cart-item-delete material-symbols-outlined' tag='{$item['tag']}'> cancel </span>
    </div>
</a>
";
}

function review_checkout(float $total, $has_info): void
{
    if ($has_info) {
        $link = '/checkout/payment';
    } else {
        $link = '/checkout/billing';
    }
    $total = number_format($total, 2);
    echo "
<a class='review-payment-anchor' href='$link'>
    <div class='review-payment-wrapper'>
        <span class='review-item-icon material-symbols-outlined'> shopping_cart_checkout </span>
        <span class='review-item-name'> Continue to payment </span>
        <span class='review-item-price'> €$total </span>
    </div>
</a>
";
}
