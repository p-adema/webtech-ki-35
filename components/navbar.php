
<?php
require 'dropdown_function.php';
require 'sidebar_right.php';
function navbar(): void
{
    if ($_SESSION['auth']){
        $html = "
            <div class='topnav'>
            <a   href='/'><img src='images/logo-no-background.png' width='110px' height='32px' ></a>
             " .

            dropDown("<img src='images/account2.png' width='32px' height='32px' >", ['auth/account/index.php', 'auth/logout.php'],['Account management', 'Log out'])
            . "
            <div id='mandje' onclick='openRightMenu()'>
            <img src='images/winkelandje.png'   width='32px' height='32px'>
            </div>" .
            sidebarRight()
            ."</div>
        ";
    } else {
        $html = "
        <div class='topnav'>
            <a   href='/'><img src='images/logo-no-background.png' width='110px' height='32px' ></a>
            
            ".
            dropDown("<img src='images/account2.png' width='32px' height='32px' >", ['auth/register.php', 'auth/login.php'],['Register', 'Log in'])
            . "
             <div id='mandje' onclick='openRightMenu()'>
            <img src='images/winkelandje.png'   width='32px' height='32px'>
            </div>" .
            sidebarRight()
            ."</div>
        ";
    }
    echo $html;


}
