<?php
require 'html_page.php';

html_header(title: 'Forgot password', styled: 'auth/register.css', scripted: true);
?>
<body>
<div id="form_container">
    <h1> Forgot password </h1>
    <div id="helper-box">
        <form action="/api/forgot_password.php" method="POST">
            <p> Fill in your email to reset password.</p>
        <?php
        require "form_elements.php";
        form_input('email', 'Email', 'username@example.com', 'email');
        form_submit('reset password');
        ?>
        </form>
    </div>
<?php


html_footer();
