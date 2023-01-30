<?php
require 'html_page.php';
html_header('EduGrove', styled: true, scripted: 'ajax');

$homepage = true;
if ($homepage):

    $html = "
    <div class='homepage-all'>
    <div class='homepage-top'>
    <video autoplay loop muted  class='background-grove' src='/resources/images/homepage-video.mp4'></video>
    <div class='welcome-text'>
    <p id='welcome-text'>EduGrove: Your path to growth</p>
</div>
<div class='stretch-box-homepage-1'></div>
<div class='explore-button'>
<a href='/courses/browse_videos'> <div class='button-text'>
<span> Start exploring</span>
</div> </a>
</div>
<div class='stretch-box-homepage-2'></div>
</div>

<div class='homepage-bottom'>
<div class='homepage-information'>
<div class='text'>
<h1> About us: </h1>  
<p> Our goal is to provide education too all, on our site you can both upload and watch educational videos Lorem 
ipsum dolor sit amet, consectetur adipisicing elit. Animi deserunt dicta dignissimos, ducimus error in incidunt 
laboriosam laborum molestiae nemo numquam officiis optio provident quidem ratione soluta veritatis? Nesciunt, ullam!</p>
</div>
</div>
<div class='homepage-information'>
<div class='text'>
<h1> How does it work? </h1>  
<p> Our goal is to provide education too all, on our site you can both upload and watch educational videos Lorem 
ipsum dolor sit amet, consectetur adipisicing elit. Animi deserunt dicta dignissimos, ducimus error in incidunt 
laboriosam laborum molestiae nemo numquam officiis optio provident quidem ratione soluta veritatis? Nesciunt, ullam!</p>
</div>
</div>
<div class='homepage-information'>
<div class='text'>
<h1> Contact </h1>  
<p> Our goal is to provide education too all, on our site you can both upload and watch educational videos Lorem 
ipsum dolor sit amet, consectetur adipisicing elit. Animi deserunt dicta dignissimos, ducimus error in incidunt 
laboriosam laborum molestiae nemo numquam officiis optio provident quidem ratione soluta veritatis? Nesciunt, ullam!</p>
</div>
</div>
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
