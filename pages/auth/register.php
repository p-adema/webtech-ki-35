<?php
require 'html_page.php';
html_header(title: 'Register', styled: 'form.css', scripted: true);
?>
    <div id="form_container">
        <h1> Register </h1>
        <div id="helper-box">
            <form action="/api/register.php" method="POST">
                <?php
                require "form_elements.php";
                require "link.php";
                form_input('name', 'Username', 'username');
                form_input('email', 'Email', 'username@example.com', 'email');
                form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_input('full_name', 'Full name (optional)', 'User Name');
                form_submit();
                text_link('Login', '/auth/login.php');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
