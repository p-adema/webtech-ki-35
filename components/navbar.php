
<?php
require 'dropdown_function.php';
function navbar(): void
{
    if ($_SESSION['auth']){
        $html = "
            <div class='topnav'>
            <a   href='/'><img src='images/logo-no-background.png' width='110px' height='32px' ></a>
            " .
            dropDown("<img src='images/account2.png' width='32px' height='32px' >", ['auth/account/index.php', 'auth/logout.php'],['Account management', 'Log out'])
            . "
            <a id='mandje' href='/'><img src='images/winkelandje.png' width='32px' height='32px'></a>
            </div>
        ";
    } else {
        $html = "
        <div class='topnav'>
            <a   href='/'><img src='images/logo-no-background.png' width='110px' height='32px' ></a>
            
            ".
            dropDown("<img src='images/account2.png' width='32px' height='32px' >", ['auth/register.php', 'auth/login.php'],['Register', 'Log in'])
            . "
            <a id='mandje' href='/'><img src='images/winkelandje.png' width='32px' height='32px'></a>
            </div>
        ";
    }
    echo $html;


}
