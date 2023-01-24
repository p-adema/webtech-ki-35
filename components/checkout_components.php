<?php

function review_item(array $item): void
{
    $type = $item['type'] === 'video' ? 'movie' : 'library_books';
    echo "
<div class='review-item-wrapper'>
    <span class='review-item-icon material-symbols-outlined'> $type </span>
    <span class='review-item-name'> {$item['name']} </span>
    <span class='review-item-price'> €{$item['price']} </span>
    <span class='review-item-delete material-symbols-outlined' tag='{$item['tag']}'> cancel </span>
</div>
";
}

function review_checkout(float $total): void
{
    echo "
<a class='payment-link' href='/checkout/billing'>
<div class='review-payment-wrapper'>
    <span class='review-item-icon material-symbols-outlined'> shopping_cart_checkout </span>
    <span class='review-item-name'> Continue to payment </span>
    <span class='review-item-price'> €$total </span>
</div>
</a>
";
}
