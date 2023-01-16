<?php
require 'html_page.php';
html_header(title: 'Register', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Register </h1>
        <div class="form-outline">
            <form action="/api/register.php" method="POST">
                <?php
                require "form_elements.php";
                require "link.php";
                form_input('name', 'Username');
                form_input('email', 'Email', type: 'email');
                form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                form_input('full_name', 'Full name (optional)');
                form_error();
                echo '<div class="form-btns">';
                text_link('Login', '/auth/login.php');
                form_submit();
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
