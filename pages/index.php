<?php
require 'html_page.php';
require 'link.php';
html_header('DB Example', scripted: 'ajax');
require 'navbar.php';
navbar();

echo '<p>';
echo 'Base page';
echo '<br/> See <a href="info.php">info</a> for install info';
echo '<br/> See <a href="table.php">table</a> for an SQL table';
echo '<br/> See <a href="auth/register.php">auth/register</a> for a registration form';
echo '<br/> See <a href="auth/login.php">auth/login</a> for a login form';
echo '<br/> See <a href="auth/logout.php">auth/logout</a> for a logout form';
echo '<br/> See <a href="voorbeeld.php">voorbeeld</a> for an example';
echo '<br/> See <a href="session.php">session</a> for a session test';
echo '<br/> See <a href="auth/forgot_password.php">forgot password</a> in case you forgot your password';
echo '<br/> See <a href="modify_cart.php">modify cart</a> to add/remove example to/from cart';
echo '<br/> See <a href="show_cart.php">show cart</a> to view cart';
echo '<br/> See <a href="courses/video.php?tag=example_free">example free</a> to view a free example video';
echo '</p>';

html_footer();
