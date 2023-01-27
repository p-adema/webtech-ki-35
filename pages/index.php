<?php
require 'html_page.php';
html_header('EduGrove', styled: true, scripted: 'ajax');

$homepage = true;
if ($homepage):

    $html = "
    <div class='homepage-all'>
    <div class='homepage-top'>
    
    <img class='background-grove' src='/resources/images/homepage2.gif' alt='testtest'>
    <div class='welcome-text'>
    <p id='welcome-text'>EduGrove: Your path to growth</p>
</div>
</div>

<div class='homepage-middle'>

</div>
<div class='homepage-bottom'>

</div>
    
</div>
    ";
    echo $html;
    else:
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
echo '<br/><br/> See <a href="/courses/course/bh5ubPO5vCg71d5D64a5GeQMP2enL02DHBiOSTne5vYdlS15rGmsRWuwAuW8t06h"> Physics</a> for a first scraped course';
echo '<br/> See <a href="/courses/course/bPp0NYKzPJb8s6iBjnTphRwn9RG09phKh6V65UgqwsiY1kw1DaeFxmHgen4nx9QR"> Environmental Science </a> for a second scraped course';
echo '<br/> See <a href="/courses/course/69z4eIxCHLIkEoR6jNTdAoyEjaRwj0Q8wGgsjj4pBtgIyH0XtgxAXBT1VfCWXWtS"> IUCN </a> for a third scraped course';
echo '<br/><br/> See <a href="/upload/"> upload </a> to upload new content';
echo '</p>';

endif;

html_footer();
