<?php
require_once 'dropdown_function.php';
require_once 'sidebar_right.php';
require_once "searchbar.php";
function navbar(): void
{

 $html = "
     <div class='topnav'>
     <div class='home-button'>
     <a href='/'><img src='/resources/images/logo.png' width='110px' height='32px' ></a>
     </div>
     <div class='navbar-stretch-1'> </div>
      <div class='browse-button'> 
      <a href='/courses/browse_videos'><div id='browse-button'> <span class='material-symbols-outlined'>search</span> Explore</div></a>
      </div>
      <div class='courses-button'> 
      <a href='/courses/'><div id='courses-button' ><span class='material-symbols-outlined'>school</span> Courses</div></a>
      </div>
      <div class='videos-button dropdown-videos'> 
      <a><div id='videos-button'> <span class='material-symbols-outlined'>play_circle</span> Video's</div></a>  
        
      </div>
        <div class='navbar-stretch-2'></div>
        " . searchbar() . "
        <a href='/upload/' class='navbar-upload-button'> <span class=\"material-symbols-outlined\">
upload
</span> Upload </a>
    <div class='shopping-cart'>
     <div id='mandje' onclick='open_right_menu()'>
     <span id='shopping-cart' class='material-symbols-outlined'>
     shopping_cart
     </span>
     </div></div>
     <div class='dropdown-menu'>
      ";
    if ($_SESSION['auth']) {
        $html .= dropDown("<span id='account-picture' class='material-symbols-outlined'>account_circle</span>", ['/auth/account/', '/auth/logout'], ['Account management', 'Log out']);
    } else{
        $html .= dropDown("<span id='account-picture' class='material-symbols-outlined'>account_circle</span>", ['/auth/register', '/auth/login'],['Register', 'Log in']);
    }
    $html .= " </div>" .
        sidebar_right()
        . "</div> 
 <div class='videos-button dropdown-videos'>
 <div class='dropdown-videos-content'> 
      <a href='/courses/subject?tag=physics'> Phsics</a>
      <a href='/courses/subject?tag=biology'> Biology</a>
      <a href='/courses/subject?tag=geography'> Geography</a>
      </div>
      </div>
 ". sidebar_cover();

     echo $html;


}
