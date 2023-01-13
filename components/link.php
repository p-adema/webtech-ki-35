<?php

function text_link(string $text, string $adress, $id = ''): void
{
    if ($id) {
        $id_attr = "id='$id'";
    } else {
        $id_attr = '';
    }
    $html ="
    <div class='link-box' $id_attr>
        <a href='$adress'> $text </a>
    </div> ";
    echo $html;
}
