<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/upload/auth');
html_header(title: 'Create new course', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Create course </h1>
        <div class="form-outline">
            <form action="/api/upload/course" method="POST">
                <?php
                form_input('name', 'Course name');
                form_input_paragraph('description', 'Description');
                form_dropdown('subject', 'Subject', 'Select subject', SUBJECTS);
                form_price();

                form_sortable('videos', 'Course videos', []);

                form_error();
                echo '<div class="form-btns">';
                text_link('Go back', '/upload/');
                form_submit(text: 'Create course', extra_cls: 'med-btn');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
