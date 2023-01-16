<?php
require 'tag_actions.php';
require 'html_page.php';
html_header(title: 'Verify acoount', styled: 'form.css', scripted: true);

if (isset($_GET['tag']) and tag_check($_GET['tag'], 'verify')): ?>
    <div class="form-content">
        <h1> Verify account </h1>
        <div class="form-outline">
            <form action="/api/verify.php" method="POST">
                <?php
                require "form_elements.php";
                form_submit('Activate');
                ?>
            </form>
        </div>
    </div>
<?php
else: ?>
    <p> this link doesn't seem quite right</p>

<?php
endif;
html_footer();
?>
