<?php
function html_header(string $title, string $description = '', bool|string $styled = false, bool $scripted = false, string $extra = ''): void
{
    $page = explode('.php', $_SERVER['SCRIPT_NAME'])[0];
    $script = $page . '.js';
    if ($scripted) {
        $script_tags = "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js\"></script>
                        <script src=\"/scripts$script\"></script>";
    } else {
        $script_tags = '';
    }
    if ($styled) {
        if ($styled === true) {
            $style = $page . '.css';
        } else {
            $style = '/' . $styled;
        }
        $style_tag = "<link rel='stylesheet' href='/styles$style' type='text/css'/>";
    } else {
        $style_tag = '';
    }
    $html = "<!doctype html>            
            <head>
                <meta charset='utf - 8'>
                <meta name='description' content=$description/>
                <title>$title</title>
                $script_tags
                <link rel='stylesheet' href='/styles/global.css' type='text/css'/>
                $style_tag
                $extra
            </head>";
    echo $html;
}
