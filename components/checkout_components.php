<?php

function render_review_item(array $item): void
{
    $type = $item['type'] === 'video' ? 'movie' : 'library_books';
    echo "
<div class='review-item-wrapper'>
    <span class='review-item-icon material-symbols-outlined'> $type </span>
    <span class='review-item-name'> {$item['name']} </span>
    <span class='review-item-price'> {$item['price']} </span>
    <span class='review-item-delete material-symbols-outlined' tag='{$item['tag']}> cancel </span>
</div>
";
}
