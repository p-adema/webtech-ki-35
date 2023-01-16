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
                echo '<div class="form-btns">';
                require "link.php";
                text_link('Register', '/auth/register.php');
                form_submit('Log in');
                echo '</div>';

                ?>
            </form>
        </div>
    </div>
<?php html_footer();
