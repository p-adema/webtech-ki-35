<?php
require 'tag_actions.php';
require 'html_page.php';
html_header(title: 'Change password', styled: 'form.css', scripted: true);
?>

    <div class="form-content">
        <h1> Change password </h1>
        <div class="form-outline">
            <form action="/api/reset_password" method="POST">
                <?php if (isset($_SESSION['url_tag']) and $_SESSION['url_tag_type'] === 'password-reset'): ?>
                    <p> Fill in your new password below.</p>
                    <?php
                    form_input('password', 'Password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                    form_input('password_repeated', 'Repeat password', type: 'password', input_attrs: "autocomplete=\"new-password\"");
                    form_error();
                    form_submit();
                    ?>
                <?php else: ?>
                    <p> This link doesn't seem quite right. </p>
                    <a href="/"> Go back to home </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

<?php html_footer();
