<?php
require 'tag_actions.php';
require 'html_page.php';
html_header(title: 'Change password', styled: 'form.css', scripted: true);

if (isset($_GET['tag']) and tag_check($_GET['tag'], 'password-reset')): ?>
<div class="form-content">
    <h1> Change password </h1>
    <div class="form-outline">
        <form action="/api/change_password_email.php" method="POST">
            <p> Fill in your new password bellow.</p>
            <?php
                require "form_elements.php";
                form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_input('password_repeated', 'Repeat Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_submit();
                ?>
            </form>
        </div>
    </div>
<?php
else: ?>
<p> this link doesnt seem quite right</p>

<?php
endif;
html_footer();
?>
