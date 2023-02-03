<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/auth/login');
html_header(title: 'Hide comment', styled: 'form.css', scripted: true);

require 'admin_controls.php'; ?>

    <div class="form-content">
        <h1>Hide a comment</h1>
        <div class="form-outline">
            <form action="/api/admin/hide" method="POST" data-action="unhide">
                <?php
                if ($_SESSION['admin']) {
                    form_input('comment_tag', 'Tag of comment to be hidden');
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
