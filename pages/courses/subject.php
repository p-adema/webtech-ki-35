<?php
require 'html_page.php';
require_once 'rating_functionality.php';
require_once 'subject_page_functionality.php';
require_once 'courses_browse_functionality.php';
html_header(title: 'Subject', styled: true, scripted: false);

ensure_session();

$subject = $_GET['tag'];

if (!empty($subject) and ($subject === 'physics' or $subject === 'geography' or $subject === 'biology')) {

    $subject_header = ucfirst($subject);

?>
    <div class="header-box">
        <div class="header">
            <span><?php echo $subject_header ?></span>
        </div>
    </div>
    <div class="box">
        <?php render_best_videos_subject_page($subject); ?>
    </div>

<?php
}
else {
    echo "This link does not seem quite right.";
}
html_footer();
