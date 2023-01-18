<?php
require "html_page.php";
html_header(title: 'Voorbeeld', styled: true, scripted: true);

echo '<h1> Dit is een voorbeeld </h1>';
echo '<p> Dit is de subtext </p>';
require 'sidebar_functionality.php';

$links = ["Register" => "/auth/register.php", "Login" => "/auth/login.php", "Logout" => "/auth/logout.php"];

sidebar();

html_footer();
