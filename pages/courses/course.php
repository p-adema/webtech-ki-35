<?php
require 'html_page.php';
require 'relative_time.php';
require 'course_components.php';
require 'video_functionality.php';
html_header(title: 'Course', styled: true, scripted: false);

$course_tag = $_GET['tag'];

$course_info = get_course_info($course_tag);
$course_creator = course_creator($course_info['creator']);
$time_since = time_since($course_info['creation_date']);
$videos = get_videos($course_tag);
$video_names = get_video_names($videos);
?>
    <body>
    <div class="main-video-box">
        <div class='course-header'>
            <div class="title-and-subject">
                <span class='title'><?php echo $course_info['name'] ?></span>
                <div class="subject">
                    <span class="subject-name"> <?php echo $course_info['subject'] ?><br></span>
                </div>
            </div>
            <div class="information">
                <div class="creation">
                    <span class='created-by'>Created by: </span>
                    <span class="creator"><?php echo $course_creator['name'] ?></span>
                </div>
            </div>
            <div class="description">
                <span class='description'><?php echo $course_info['description'] ?><br></span>
                <span class='creation-date'>Created: <?php echo $time_since ?> ago<br></span>
            </div>
        </div>
        <div class="thumbnail-outline">
            <div class='thumbnail-box'>
                <?php foreach ($video_names as $name) {
                    echo $name;
                } ?>
            </div>
        </div>
    </div>
    <div class="view-box">
        <span class='views'>Views: <?php echo $course_info['views'] ?></span>
    </div>
    </body>

<?php

//echo get_video_watch_amount(2, 'example_paid');

//To do: Allow users to save their courses.

html_footer();
