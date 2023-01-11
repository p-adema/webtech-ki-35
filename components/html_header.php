<?php
function html_header(string $title, string $description = '', bool $jquery = false): void
{
    $style = str_replace('.php', '.css', $_SERVER['SCRIPT_NAME']);
    if ($jquery) {
        $jquery_script = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>';
    } else {
        $jquery_script = '';
    }
    $html = "<!doctype html>            
            <head>
                <meta charset='utf - 8'>
                <meta name='description' content=$description/>
                <title>$title</title>
                $jquery_script
                <link rel='stylesheet' href='/global.css' type='text/css'/>
                <link rel='stylesheet' href='$style' type='text/css'/>
            </head>";
    echo $html;
}
