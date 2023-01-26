<?php
require 'html_page.php';
html_header(title: 'Upload content', styled: 'form.css', scripted: true);
?>
    <div class="form-content">
        <h1> Upload content </h1>
        <div class="form-outline">
            <form>
                <p> Upload a video or create a course </p>
                <?php
                echo '<div class="form-btns">';
                text_link('Create course', '/upload/course');
                form_submit(text: 'Upload video', extra_cls: 'med-btn');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>
<?php html_footer();
