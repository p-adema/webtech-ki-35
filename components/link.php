<?php

function render_text_link(string $text, string $address, string $id = ''): string
{
    if ($id) {
        $id_attr = "id='$id'";
    } else {
        $id_attr = '';
    }
    return "
<div class='link-box link-box-text' $id_attr>
    <a href='$address'> $text </a>
</div>";
}

function display_text_link(string $text, string $address, $id = ''): void
{
    echo render_text_link($text, $address, $id);
}

function display_button_link(string $text, string $address, string $id = ''): void
{
    if ($id) {
        $id_attr = "id='$id'";
    } else {
        $id_attr = '';
    }
    $html = "
<div class='link-box link-box-btn' $id_attr>
    <button onClick=\"location.href='$address'\"> $text </button>
</div>";
    echo $html;
}

function display_link_pair(string $ltext, string $laddress, string $rtext, string $raddress) : void
{
    echo "
<div class='link-box link-box-pair'>
    <a href='$laddress'> $ltext </a>
    <span class='flex-gap'></span>
    <a href='$raddress'> $rtext </a>
</div>
";
}
