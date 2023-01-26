<?php
require 'html_page.php';
auth_redirect(if_auth: '/auth/logout');
html_header(title: 'Log in', authentication: true, styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> <?php echo $_SESSION['last-page']?> </h1>
        <div class="form-outline">
            <form action="/api/login" method="POST" tag="<?php echo $_SESSION['last-page']?>">
                <?php
                form_input('name', 'Username or email');
                form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"current-password\"");
                form_error();

                echo '<div class="form-btns">';
                text_link('Register', '/auth/register');
                form_submit('Log in');
                echo '</div>';

                text_link('Forgot password?', '/auth/forgot_password', 'forgot-password-box');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
