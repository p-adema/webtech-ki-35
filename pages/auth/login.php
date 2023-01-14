<?php
require 'html_page.php';
html_header(title: 'Log in', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Log in </h1>
        <div class="form-outline">
            <form action="/api/login.php" method="POST">
                <?php
        require "form_elements.php";
        form_input('name', 'Username or email');
        form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"current-password\"");
        form_submit();
        require "link.php";
        text_link('Register', '/auth/register.php');
        text_link('Forgot password', '/auth/forgot_password.php');

        ?>
    </form>
    </div>
</div>
<?php html_footer();
