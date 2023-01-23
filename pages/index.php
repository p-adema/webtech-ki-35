<?php
require 'html_page.php';
html_header('EduGrove', scripted: 'ajax');

echo '<p>';
echo 'Base page';
echo '<br/> See <a href="info">info</a> for install info';
echo '<br/> See <a href="auth/register">auth/register</a> for a registration form';
echo '<br/> See <a href="auth/login">auth/login</a> for a login form';
echo '<br/> See <a href="auth/logout">auth/logout</a> for a logout form';
echo '<br/> See <a href="voorbeeld">voorbeeld</a> for an example';
echo '<br/> See <a href="session">session</a> for a session test';
echo '<br/> See <a href="auth/forgot_password">forgot password</a> in case you forgot your password';
echo '<br/> See <a href="modify_cart">modify cart</a> to add/remove example to/from cart';
echo '<br/> See <a href="show_cart">show cart</a> to view cart';
echo '<br/> See <a href="courses/video?tag=example_free">example free</a> to view a free example video';
echo '<br/> See <a href="courses/video?tag=example_paid">example paid</a> to view a paid example video';
echo '<br/> See <a href="bank/index"> bank</a> to see your balance (if you have one)';
echo '</p>';

html_footer();
