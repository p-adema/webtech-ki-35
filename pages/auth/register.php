<?php
require 'html_page.php';
auth_redirect(if_auth: '/auth/logout');
html_header(title: 'Register', authentication: true, styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Register </h1>
        <div class="form-outline">
            <form action="/api/register" method="POST">
                <?php
                form_input('name', 'Username');
                form_input('email', 'Email', type: 'email');
                form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_input('re_pwd', 'Repeat password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_input('full_name', 'Full name (optional)');
                form_error();

                echo '<div class="form-btns">';
                display_text_link('Login', '/auth/login');
                form_submit(text: 'Register');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
