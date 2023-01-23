<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Log out', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Log out </h1>
        <div class="form-outline">
            <form action="/api/logout" method="POST">
                <?php
                form_submit(text: 'Confirm log out', extra_cls: 'long-btn');
                form_error();
                text_link('Go back to home', '/');
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
