<?php
require 'html_page.php';
html_header(title: 'Logout', styled: 'form.css', scripted: true);
?>
    <div id="form_container">
        <h1> Logout </h1>
        <div id="helper-box">
            <form action="/api/logout.php" method="POST">
                <?php
                require "form_elements.php";
                require "link.php";

                form_submit(text: 'Confirm logout');
                text_link('Go back to home', '/');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
