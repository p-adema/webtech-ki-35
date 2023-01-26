<?php
require 'html_page.php';
require 'admin_controls.php';
html_header(title: 'Admin Controls', styled: true, scripted: false);

ensure_session();

if ($_SESSION['auth']) {
    if (is_admin($_SESSION['uid'])) {
        ?>

        <body>
        <div class="main-container">
            <span>Here are your controls</span><br>
            <a href="gift">Click here for a gift form</a>
            <a href="remove_comment"></a>
        </div>
        </body>

        <?php }
    else {
        echo "You do not have the permissions to be on this page.";
    }
}
else {
    echo 'You do not seem to be logged in.';
}


        html_footer();
