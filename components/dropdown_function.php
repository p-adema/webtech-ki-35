<?php

function dropDown($list): Void
{
    echo "<div class='dropdown'> 
          <button class='dropbtn'>Dropdown</button>
          <div class='dropdown-content'>";
    foreach($list as $subject => $link) {
        echo "<a href='$link'>$subject</a>";
    }
    echo "</div>
          </div>";

}
