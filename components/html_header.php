<?php
function html_header(string $title, string $description = '', bool $styled = false, bool $scripted = false, string $extra = ''): void
{
    $page = explode('.php', $_SERVER['SCRIPT_NAME'])[0];
    $style = $page . '.css';
    $script = $page . '.js';
    if ($scripted) {
        $script_tags = "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js\"></script>
                        <script src=\"scripts$script\"></script>";
    } else {
        $script_tags = '';
    }
    $html = "<!doctype html>            
            <head>
                <meta charset='utf - 8'>
                <meta name='description' content=$description/>
                <title>$title</title>
                $script_tags
                <link rel='stylesheet' href='styles/global.css' type='text/css'/>
                <link rel='stylesheet' href='styles$style' type='text/css'/>
                $extra
            </head>";
    echo $html;
}
