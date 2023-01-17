<?php

function text_link(string $text, string $adress, $id = ''): void
{
    if ($id) {
        $id_attr = "id='$id'";
    } else {
        $id_attr = '';
    }
    $html ="
    <div class='link-box link-box-text' $id_attr>
        <a href='$adress'> $text </a>
    </div> ";
    echo $html;
}

function button_link(string $text, string $adress, $id = ''): void
{
    if ($id) {
        $id_attr = "id='$id'";
    } else {
        $id_attr = '';
    }
    $html = "
    <div class='link-box link-box-btn' $id_attr>
        <button onClick=\"location.href='$adress'\"> $text </button>
    </div> ";
    echo $html;
}
