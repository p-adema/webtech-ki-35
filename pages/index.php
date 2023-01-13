<?php
require 'html_page.php';
html_header('DB Example', scripted: 'ajax');

echo 'Base page';
echo '</br> See <a href="info.php">info</a> for install info';
echo '</br> See <a href="table.php">table</a> for an SQL table';
echo '</br> See <a href="auth/register.php">auth/register</a> for a registration form';
echo '</br> See <a href="auth/login.php">auth/login</a> for a login form';
echo '</br> See <a href="auth/logout.php">auth/logout</a> for a logout form';
echo '</br> See <a href="voorbeeld.php">voorbeeld</a> for an example';
echo '</br> See <a href="session.php">session</a> for a session test';

html_footer();
