<?php
require 'html_page.php';
require 'admin_controls.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Administration', styled: 'form.css', scripted: 'ajax');

?>
    <div class="form-content">
        <h1>Administrative controls</h1>
        <div class="form-outline">
            <form action="/api/admin/gift" method="POST">
                <?php
                if ($_SESSION['admin']) {
                    echo '<p> Select an action: </p>';

                    echo '<div class="form-btns form-btns-down form-btns-spaced">';
                    text_link('Sudo as a user', '/admin/sudo');
                    text_link('Unban a user', '/admin/unban');
                    text_link('Ban a user', '/admin/ban');
                    text_link('Delete an item', '/admin/delete');
                    text_link('Gift an item', '/admin/gift');
                    echo '</div>';

                } else {
                    echo "<p>Insufficient privileges</p>";
                }
                ?>
            </form>
        </div>
    </div>
<?php html_footer();
