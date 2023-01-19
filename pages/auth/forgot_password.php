<?php
require 'html_page.php';
html_header(title: 'Forgot password', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Forgot password </h1>
        <div class="form-outline">
            <form action="/api/forgot_password.php" method="POST">
                <p> Fill in your email to reset password.</p>
                <?php
                form_input('email', 'Email', 'username@example.com', 'email');
                form_error();
                form_submit('Reset password', 'long-btn');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
