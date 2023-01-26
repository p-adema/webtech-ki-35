<?php
require 'html_page.php';
html_header(title: 'Gift', styled: 'form.css', scripted: true);

ensure_session();

if ($_SESSION['auth']) {
    if (is_admin($_SESSION['uid'])) {
        ?>
        <div class="form-content">
            <h1>Gift something</h1>
            <div class="form-outline">
                <form action="/api/admin/gift" method="POST">
                    <?php
                    form_input('user', 'User to receive gift');
                    form_input('item-tag', 'Item-tag to be gifted');
                    form_error();

                    echo '<div class="form-btns">';
                    form_submit();
                    echo '</div>';
                    ?>
                </form>
            </div>
        </div>
    <?php }
    else {
        echo "You do not have the permissions to be on this page.";
    }
} else {
    echo 'You do not seem to be logged in.';
}


html_footer();
