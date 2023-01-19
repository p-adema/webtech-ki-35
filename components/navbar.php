<?php
require_once 'dropdown_function.php';
function navbar(): void
{
    if ($_SESSION['auth']){
        $html = "
            <div class='topnav'>
            <a   href='/'><img src='/images/logo-no-background.png' width='110px' height='32px' ></a>
             " .
            dropDown("<img src='/images/account2.png' width='32px' height='32px' >", ['auth/account/index.php', 'auth/logout.php'],['Account management', 'Log out'])
            . "
            <div id='mandje' onclick='openRightMenu()'>
            <img src='/images/winkelandje.png'   width='32px' height='32px'>
            </div>
            <div class='sidebar_right sidebar_block sidebar_animate_right' style='display:none;right:0;' id='rightMenu'>
                <button onclick='closeRightMenu()' class='sidebar_close'>Close</button>
                <p class='sidebar_text'> This is in your shopping cart:</p>
                <hr>
                <a href='#' class='sidebar_item'>Link 1</a>
                <a href='#' class='sidebar_item'>Link 2</a>
                <a href='#' class='sidebar_item'>Link 3</a>
            </div>
            </div>
        ";
    } else {
        $html = "
        <div class='topnav'>
            <a href='/'><img src='/images/logo-no-background.png' width='110px' height='32px' ></a>
            
            ".
            dropDown("<img src='/images/account2.png' width='32px' height='32px' >", ['auth/register.php', 'auth/login.php'],['Register', 'Log in'])
            . "
            
            </div>
        ";
    }
    echo $html;


}
