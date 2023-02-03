<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Restrict item', styled: 'form.css', scripted: true);

require 'admin_controls.php'; ?>

    <div class="form-content">
        <h1>Restrict an item</h1>
        <div class="form-outline">
            <form action="/api/admin/restrict" method="POST" data-action="restrict">
                <?php
                if ($_SESSION['admin']) {
                    form_input('item_tag', 'Tag of item to be restricted');
                    form_error();

                    echo '<div class="form-btns">';
                    display_text_link('Go back', '/admin/');
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
