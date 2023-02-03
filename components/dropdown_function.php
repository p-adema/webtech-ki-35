<?php
require_once "link.php";


function dropDown($text_on_dropdown, $links, $names): string {
    $linkjes = '';
    for ($x = 0; $x < count($links); $x++) {
        $linkjes =  $linkjes . render_text_link($names[$x], $links[$x]);
    }

    return "<div class='dropdown'> 
          <button class='dropbtn'>$text_on_dropdown</button>
          <div class='dropdown-content'>" .
            $linkjes .
     "</div>
          </div>";

}
