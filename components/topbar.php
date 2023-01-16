<?php
function maketext(): void
{
    $html = "<ul>
  <li><a href='home.php'><img src='pictures/icon1.png' alt='HTML tutorial' style='width:20px;height:20px;'></a></li>
  <li><a href='index.php'><img src='pictures/icon1.png' alt='HTML tutorial' style='width:15px;height:15px;'></a></li>
  <li><a href='home.php'>Contact</a></li>
  <li style='float:right; background-color: blue'><a href='home.php'>About</a></li>
</ul>";
    echo "$html";

}
