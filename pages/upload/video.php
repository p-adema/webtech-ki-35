<?php
require 'html_page.php';
html_header(title: 'Upload new video', styled: 'form.css', scripted: false);
?>
    <div class="form-content">
        <h1> Upload video </h1>
        <div class="form-outline">
            <form action="/api/upload/video" method="POST">
                <?php
                form_input('name', 'Video name');
                form_error();

                echo '<div class="form-btns">';
                text_link('Go back', '/upload/');
                form_submit(text: 'Upload video', extra_cls: 'med-btn');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
