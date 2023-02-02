<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Delete item', styled: 'form.css', scripted: true);

require 'admin_controls.php'; ?>

    <div class="form-content">
        <h1>Delete an item</h1>
        <div class="form-outline">
            <form action="/api/admin/delete" method="POST">
                <?php
                if ($_SESSION['admin']) {
                    form_input('item_tag', 'Tag of item to be deleted');
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
