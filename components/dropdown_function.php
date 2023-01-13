<?php
require_once "link.php";

function dropDown($links): Void
{
    echo "<div class='dropdown'> 
          <button class='dropbtn'>Dropdown</button>
          <div class='dropdown-content'>";
    foreach($links as $text => $address) {
        text_link($text, $address);
    }
    echo "</div>
          </div>";

}
