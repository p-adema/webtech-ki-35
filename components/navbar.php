<?php
require_once 'dropdown_function.php';
require_once 'sidebar_cart.php';
require_once "searchbar.php";
function navbar(): void
{
    $admin = $_SESSION['admin'] ? "<a href='/admin/' class='navbar-round-button navbar-admin-button'> <span class=\"material-symbols-outlined\"> admin_panel_settings </span> Admin </a>" : '';
    $library = $_SESSION['auth'] ? "
<div class='courses-button'> 
    <a href='/courses/library'>
        <div id='library-button' >
            <span class='material-symbols-outlined'>video_library</span> 
            Library 
        </div>
    </a>
</div>
      " : '';
    $dropdown_subjects_rendered = [];
    foreach (SUBJECTS as $subject) {
        $capitalised = ucfirst($subject);
        $dropdown_subjects_rendered[] = "<a href='/courses/subject?tag=$subject'> $capitalised </a>";
    }
    $dropdown_subjects = join(PHP_EOL, $dropdown_subjects_rendered);
    $html = "
     <div class='topnav'>
     <div class='home-button'>
     <a href='/'><img src='/resources/images/logo.png' width='110' height='32' alt='Edugrove'></a>
     </div>
     <div class='navbar-stretch-1'> </div>
      <div class='browse-button'> 
      <a href='/courses/browse_videos'><div id='browse-button'> <span class='material-symbols-outlined'>search</span> Explore</div></a>
      </div>
      <div class='courses-button'> 
      <a href='/courses/'><div id='courses-button' ><span class='material-symbols-outlined'>school</span> Courses</div></a>
      </div>
      <div class='videos-button dropdown-videos'> 
      <a href='/search'><div id='videos-button'> <span class='material-symbols-outlined'>play_circle</span> Video's</div></a>  
      </div>
      $library
        <div class='navbar-stretch-2'></div>
        " . searchbar() . "
        <a href='/upload/' class='navbar-round-button'> <span class=\"material-symbols-outlined\"> upload </span> Upload </a>
        $admin
    <div class='shopping-cart'>
     <div id='mandje'>
     <span id='shopping-cart' class='material-symbols-outlined'>
     shopping_cart
     </span>
     </div></div>
     <div class='dropdown-menu'>
      ";
    if ($_SESSION['auth']) {
        $html .= dropDown("<span id='account-picture' class='material-symbols-outlined'>account_circle</span>", ['/auth/account/', '/auth/account/invoice', '/bank/', '/auth/logout'], ['Account', 'Invoices', 'Bank', 'Log out']);
    } else {
        $html .= dropDown("<span id='account-picture' class='material-symbols-outlined'>account_circle</span>", ['/auth/register', '/auth/login'], ['Register', 'Log in']);
    }
    $html .= " </div>"
        . "</div> 
 <div class='videos-button dropdown-videos'>
 <div class='dropdown-videos-content'> 
      $dropdown_subjects
      </div>
      </div>
 " . sidebar_cover() . render_sidebar_cart();

    echo $html;


}
