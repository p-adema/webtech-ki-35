<?php
require 'html_page.php';
html_header(title: 'Login', styled: 'auth/register.css', scripted: true);
?>
<div id="form_container">
    <h1> Login </h1>
    <form action="/api/login.php" method="POST">
        <?php
        require "form_elements.php";
        form_input('name', 'Username');
        form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"current-password\"");
        form_submit();
        require "link.php";
        text_link('Register', '/auth/register.php');

        ?>
    </form>
</div>
<?php html_footer();
