<?php

function render_comment(int $id, string $commenter, string $date, string $text): string
{
    return "
<div class='comment-wrapper comment-top' id='$id'>
    <div class='head'>
        <span class='comment-username'> $commenter </span>
        <span class='comment-date'> $date </span>
    </div>
    <div class='comment-text'>
        <span class=''
</div>
</div>
    ";
}
