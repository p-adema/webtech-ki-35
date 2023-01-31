<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Ban user', styled: 'form.css', scripted: true);

require 'admin_controls.php'; ?>

    <div class="form-content">
        <h1>Unban a user</h1>
        <div class="form-outline">
            <form action="/api/admin/unban" method="POST">
                <?php
                if (is_admin($_SESSION['uid'])) {
                    form_input('user', 'Username to be unbanned');
                    form_error();

                    echo '<div class="form-btns">';
                    text_link('Go back', '/admin/');
                    form_submit();
                    echo '</div>';
                } else {
                    echo "<p>Insufficient privileges</p>";
                }
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
