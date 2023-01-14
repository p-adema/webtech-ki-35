<?php
require "html_page.php";
html_header(title: 'Voorbeeld', styled: true);

echo '<h1> Dit is een voorbeeld </h1>';
echo '<p> Dit is de subtext </p>';
require 'dropdown_function.php';
require 'sidebar_function.php';

$links = ["Register" => "/auth/register.php", "Login" => "/auth/login.php", "Logout" => "/auth/logout.php"];

//    dropDown($links);
sidebar($links);

require "link.php";
text_link('Go back to home', '/');
html_footer();
