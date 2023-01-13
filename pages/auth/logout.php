<?php
require 'html_page.php';
html_header(title: 'Logout', styled: '/auth/register.css', scripted: true);
?>
    <div id="form_container">
        <h1> Logout </h1>
        <div id="helper-box">
            <form action="/api/logout.php" method="POST">
                <?php
                require "form_elements.php";
                require "link.php";

                form_submit();
                text_link('Go back', '/');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
