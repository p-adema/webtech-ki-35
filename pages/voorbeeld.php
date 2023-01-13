<?php
require "html_page.php";
html_header(title: 'Voorbeeld', styled: true, scripted: false);

echo '<h1> Dit is een voorbeeld </h1>';
echo '<p> Dit is de subtext </p>';
require 'dropdown_function.php';
require 'sidebar_function.php';

$links = array("Test 1" => "test_1.php", "Test 2" => "test_2.php", "Test 3" => "test_3.php");

    /*dropDown($links);*/
    sidebar($links);

html_footer();
