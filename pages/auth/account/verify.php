<?php
require 'html_page.php';
html_header(title: 'Verify account', styled: true, scripted: true);

?>
    <div class="form-content">
        <h1> Verify account </h1>
        <div class="form-outline">
            <form action="/api/verify.php" method="POST">
                <?php
                require "form_elements.php";

                form_submit('Activate account', extra_cls: 'long-btn');
                form_error();
                ?>
            </form>
        </div>
    </div>

<?php
html_footer();
