<!DOCTYPE html>
<html lang='en'>

<?php
require 'html_header.php';
html_header(title: 'Register', styled: true, scripted: true);
?>
<body>
<div id="form_container">
    <h1> Register </h1>
    <form action="/api/register.php" method="POST">
        <?php
        require "form_elements.php";
        form_input('name', 'Username', 'username');
        form_input('email', 'Email', 'username@example.com', 'email');
        form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
        form_input('full_name', 'Full name (optional)', 'User Name');
        form_submit()
        ?>
    </form>
</div>
</body>
</html>
