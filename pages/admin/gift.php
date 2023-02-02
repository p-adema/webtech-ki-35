<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Gift item', styled: 'form.css', scripted: true);

require 'admin_controls.php'; ?>

    <div class="form-content">
        <h1>Gift user an item</h1>
        <div class="form-outline">
            <form action="/api/admin/gift" method="POST">
                <?php
                if ($_SESSION['admin']) {
                    form_input('user', 'Username of reciever');
                    form_input('item-tag', 'Tag of gift item');
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
