<?php
require 'html_page.php';
auth_redirect(if_auth: '/upload/');
html_header(title: 'Create an account', authentication: true, styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Authenticate </h1>
        <div class="form-outline">
            <form>
                <p> Please register or log in to upload videos </p>
                <?php
                echo '<div class="form-btns">';
                display_text_link('Login', '/auth/login');
                form_submit(text: 'Register');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>
<?php html_footer();
