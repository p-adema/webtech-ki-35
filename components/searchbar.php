<?php
function searchbar(): string
{
    return "
<div class='navbar-search-wrapper'>
    <span class='navbar-search-icon material-symbols-outlined'> search </span>
    <input class='navbar-search-input' type='text' placeholder='Search...' id='navbar-search'/>
</div>
";
}
function searchbar_results() : string
{
    return "
<div class='navbar-search-results'> </div>
";
}

function render_search_result(array $item, string $origin): string
{
    $link = '/courses/' . ($item['type'] === 'video' ? 'video/' : 'course/') . $item['tag'];
    $icon = $item['type'] === 'video' ? 'movie' : 'library_books';
    return "
<a class='$origin-review-item-anchor' href='$link' tag='{$item['tag']}'>
    <div class='$origin-search-result-wrapper'>
        <span class='$origin-search-result-icon material-symbols-outlined'> $icon </span>
        <span class='$origin-search-result-name'> {$item['name']} </span>
    </div>
</a>";
}
