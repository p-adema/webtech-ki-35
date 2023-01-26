<?php
require 'html_page.php';
auth_redirect(if_not_auth: '/upload/auth');
html_header(title: 'Upload new video', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Upload video </h1>
        <div class="form-outline">
            <form action="/api/upload/video" method="POST">
                <?php
                form_upload('video', 'Video (.mp4 format required)', 'Upload video', '.mp4', 'video_file');
                form_upload('thumbnail', 'Thumbnail (.jpg format, 16:9 aspect ratio)', 'Upload thumbnail', '.jpg', 'image');
                form_input('title', 'Title');
                form_input_paragraph('description', 'Description');
                form_dropdown('subject', 'Subject', 'Select subject', SUBJECTS);
                form_price();

                form_error();
                echo '<div class="form-btns form-btns-down">';
                form_submit(text: 'Upload video', extra_cls: 'med-btn');
                text_link('Go back', '/upload/');
                echo '</div>';
                form_upload_progress();
                ?>
            </form>
        </div>
    </div>

<?php html_footer();
