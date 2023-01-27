<?php
require 'html_page.php';
require_once 'courses_browse_functionality.php';
html_header(title: 'Browse Courses', styled: true, scripted: false);

?>
    <div class="header-box"><span class="header">Browse our courses</span></div>
    <div class="box">
        <?php render_courses(); ?>
    </div>

<?php
html_footer();
