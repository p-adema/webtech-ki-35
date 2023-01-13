<?php

function sidebar($list): void
{
    echo "<div id='mySidebar' class='sidebar'>
          <a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>x</a>";
    foreach($list as $topic => $link) {
        echo "<a href='$link'>$topic</a>";
    }
    echo "<div>";

    echo "<div id='main'>";
    echo "<button class='openbtn' onclick='openNav()'>☰ Open Sidebar</button>";

    echo '<script>
function openNav() {
  document.getElementById("mySidebar").style.width = "250px";
  document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
</script>';
}
