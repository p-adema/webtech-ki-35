<?php
require 'html_page.php';
html_header(title: 'Create new course', styled: 'form.css', scripted: false);
?>
    <div class="form-content">
        <h1> Create course </h1>
        <div class="form-outline">
            <form action="/api/upload/course" method="POST">
                <?php
                form_input('name', 'Course name');
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
