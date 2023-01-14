<?php
require 'html_page.php';
html_header(title: 'Change password', styled: 'form.css', scripted: false);
?>
<body>
<div class="form_content">
    <h1> Change password </h1>
    <div class="form-outline">
        <form action="/api/change_password.php" method="POST">
            <p> Fill in your new password bellow.</p>
        <?php
        require "form_elements.php";
        form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
        form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
        ?>
        </form>
    </div>
<?php

html_footer();
?>
