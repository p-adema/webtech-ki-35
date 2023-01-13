<?php

function text_link(string $text, string $adress): void
{
    $html = "<div class = 'link-box'>
    <a href = '$adress'>$text</a>
    </div>";
    echo $html;
}
