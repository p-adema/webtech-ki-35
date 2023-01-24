<?php
require "html_page.php";
html_header(title: 'Voorbeeld', styled: true, scripted: true);

echo '<h1> Dit is een voorbeeld </h1>';
echo '<p> Dit is de subtext </p>';
require_once 'sidebar_functionality.php';

$links = ["Register" => "/auth/register", "Login" => "/auth/login", "Logout" => "/auth/logout"];

sidebar();

html_footer();
