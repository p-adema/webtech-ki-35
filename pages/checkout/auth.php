<?php
require 'html_page.php';
auth_redirect(if_auth: '/checkout/billing');
html_header(title: 'Create an account', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Authenticate </h1>
        <div class="form-outline">
            <form action="/api/register" method="POST">
                <p> Please register or log in to continue with payment </p>
                <?php
                echo '<div class="form-btns">';
                text_link('Login', '/auth/login');
                form_submit(text: 'Register');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>
<?php html_footer();
