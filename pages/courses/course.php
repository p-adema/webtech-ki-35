<?php
require 'html_page.php';
require 'relative_time.php';
require 'course_components.php';
require 'video_functionality.php';
html_header(title: 'Course', styled: true, scripted: false);

$course_tag = 'course_paid';

$course_info = get_course_info($course_tag);
$course_creator = course_creator($course_info['creator']);
$time_since = time_since($course_info['creation_date']);
$videos = get_videos($course_tag);

$html = "
<body>
    <h1 class='title'>{$course_info['name']}</h1>
    <span class='creator'>Created by: {$course_creator['full_name']} also known as {$course_creator['name']}<br></span>
    <span class='subject'>Subject: {$course_info['subject']}<br></span>
    <span class='description'>{$course_info['description']}<br></span>
    <span class='creation'>Created: $time_since ago<br></span>
    <span class='views'>Views: {$course_info['views']}</span>
</body>
";

echo $html; ?>

    <div class="thumbnail-outline">
        <div class='thumbnail-box'>
            <?php render_thumbnails($videos); ?>
        </div>
    </div>

<?php

echo get_video_watch_amount(2, 'example_paid');
echo "%";

//To do: Allow users to save their courses.

html_footer();
