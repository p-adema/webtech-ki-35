<!DOCTYPE html>
<html lang='en'>

<?php
require 'html_header.php';
html_header(title: 'Login', styled: 'auth/register.css', scripted: true);
?>
<body>
<div id="form_container">
    <h1> Login </h1>
    <form action="/api/login.php" method="POST">
        <?php
        require "form_elements.php";
        form_input('name', 'Username');
        form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"current-password\"");
        form_submit()
        ?>
    </form>
</div>
</body>
</html>
