<?php

function sidebar(): void
{
    echo "<div class='sidebar' id='sidebar'>";
    echo "<a href='#'>Hallo</a>";
    echo "</div>";
    echo "<div id='sidebar-button'>";
    echo "<button class='openbtn' onclick='open_sidebar()'> Open sidebar </button>";
    echo "<h2>CollapsedSidebar</h2>";
    echo "<p>Content...<p>";
    echo "</div>";
}