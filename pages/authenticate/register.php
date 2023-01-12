<!DOCTYPE html>
<html lang='en'>

<body>
<?php
require 'html_header.php';
html_header(title: 'Register', styled: true, scripted: true);
?>

<div>
    <h1> Register </h1>
    <form action="/api/register.php" method="POST">
        <?php
        require "form_elements.php";
        form_input('name', 'Username', 'username');
        form_input('email', 'Email', 'username@example.com', 'email');
        form_input('password', 'Password', '', 'password');
        form_input('full name', 'Full name (optional)', 'User Name');
        form_submit()
        ?>
    </form>
</div>
</body>
</html>
