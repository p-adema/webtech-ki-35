<?php
require 'html_page.php';
html_header('EduGrove', scripted: 'ajax');

echo '<p>';
echo 'Base page';
echo '<br/> See <a href="/auth/register">auth/register</a> for a registration form';
echo '<br/> See <a href="/auth/login">auth/login</a> for a login form';
echo '<br/> See <a href="/auth/logout">auth/logout</a> for a logout form';
echo '<br/> See <a href="/auth/forgot_password">forgot password</a> in case you forgot your password';
echo '<br/> See <a href="/show_cart">show cart</a> to view cart';
echo '<br/> See <a href="/courses/video/example_free">example free</a> to view a free example video';
echo '<br/> See <a href="/courses/video/example_paid">example paid</a> to view a paid example video';
echo '<br/> See <a href="/bank/"> bank</a> to see your balance';
echo '<br/> See <a href="/courses/course/bh5ubPO5vCg71d5D64a5GeQMP2enL02DHBiOSTne5vYdlS15rGmsRWuwAuW8t06h"> Physics</a> for a first scraped course';
echo '</p>';

html_footer();
