<?php
require_once 'dropdown_function.php';
require_once 'sidebar_right.php';
function navbar(): void
{

 $html = "
     <div class='topnav'>
     <a href='/'><img src='/images/logo-no-background.png' width='110px' height='32px' ></a>
      ";
     if ($_SESSION['auth']) {
         $html .= dropDown("<span id='account-picture' class='material-symbols-outlined'>account_circle</span>", ['/auth/account/index', '/auth/logout'], ['Account management', 'Log out']);
     } else{
         $html .= dropDown("<span id='account-picture' class='material-symbols-outlined'>account_circle</span>", ['/auth/register', '/auth/login'],['Register', 'Log in']);
     }
     $html .= "
     <div id='mandje' onclick='open_right_menu()'>
     <span id='shopping-cart' class='material-symbols-outlined'>
     shopping_cart
     </span>
     </div>" .
     sidebarRight()
     ."</div> ". sidebar_cover();

     echo $html;


}
