<?php
function searchbar(): string
{
    return "
<div class='navbar-search-wrapper'>
    <span class='navbar-search-icon material-symbols-outlined'> search </span>
    <input class='navbar-search-input' type='text' placeholder='Search... (press \"/\" )' id='navbar-search'>
</div>
";
}


function render_search_result(array $item, string $origin): string
{
    $link = '/courses/' . ($item['type'] === 'video' ? 'video/' : 'course/') . $item['tag'];
    $icon = $item['type'] === 'video' ? 'movie' : 'library_books';
    if ($item['owned']) {
        $class = 'owned';
    } elseif ($item['free']) {
        $class = 'free';
    } else {
        $class = 'unowned';
    }
    return "
<a class='$origin-review-item-anchor $class' href='$link' data-tag='{$item['tag']}'>
    <div class='$origin-search-result-wrapper'>
        <span class='$origin-search-result-icon material-symbols-outlined'> $icon </span>
        <span class='$origin-search-result-name'> {$item['name']} </span>
    </div>
</a>";
}
