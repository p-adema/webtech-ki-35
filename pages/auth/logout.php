<?php
require 'html_page.php';
html_header(title: 'Log out', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Log out </h1>
        <div class="form-outline">
            <form action="/api/logout.php" method="POST">
                <?php
                require "form_elements.php";
                require_once "link.php";

                form_submit(text: 'Confirm log out', extra_cls: 'long-btn');
                form_error();
                text_link('Go back to home', '/');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
