<?php

require_once 'rating_functionality.php';
require_once 'video_functionality.php';
require_once 'courses_browse_functionality.php';

function render_best_videos_subject_page($subject): void
{
    $videos = best_videos_of_genre($subject);
    $popular_video = $videos[0];
    $popular_video_data = get_video_data($popular_video['tag']);
    $name_of_popular_uploader = user_name_from_id($popular_video_data['uploader']);
    $course = get_popular_course($subject);

    echo "<div class='course-and-video'>";

    if (!empty($course)) {

        $course_info = get_course_info($course['tag']);
        $creator = user_name_from_id($course_info['creator']);

        echo "
    <div class='course-box'>
        <div class='best-header-box-course'>
            <span class='best-video-header'>Recommended course</span>
        </div>
        <div class='text-and-course'>
            <div class='best-video-outline'>
                <a href='/courses/course/{$course['tag']}'>
                    <img class='thumbnail'
                    src='/resources/thumbnails/{$course['tag']}.jpg'
                    alt='Your browser does not support this image type.'>
                </a>
                <div class='best-video-info'>
                    <span class='best-video-name'>{$course_info['name']}</span>
                    <span class='best-video-creator'>By $creator </span>
                </div>
            </div>
            <div class='empty-space-course'></div>
            <div class='course-text-outline'><span class='course-text'>Users like you love this $subject course!</span></div>
        </div>
    </div>
    ";
    }

    else {
        echo "
        <div class='course-box'>
        <div class='best-header-box'>
            <span class='best-video-header'>Recommended course</span>
        </div>
        <div class='best-video-outline'>
            <div class='no-course-box'><span class='no-course'>Sorry, no courses found for this subject</span></div>    
            <div class='best-video-info'>
                <span class='best-video-name'>No course found</span>
            </div>
        </div>
    </div>
    ";
    }

    echo "
    <div class='empty-space'></div>
    <div class='best-box'>    
        <div class='best-header-box'>
            <span class='best-video-header'>Recommended video</span>
        </div>
        <div class='best-video-outline'>
            <a href='/courses/video/{$popular_video['tag']}'>
                <img class='thumbnail'
                src='/resources/thumbnails/{$popular_video['tag']}.jpg'
                alt='Your browser does not support this image type.'>
            </a>
            <div class='best-video-info'>
                <span class='best-video-name'>{$popular_video_data['name']}</span>
                <span class='best-video-creator'>By $name_of_popular_uploader</span>
            </div>
        </div>
    </div>    
    ";

    echo "</div>";

    echo "<div class='all-videos-header-box'><span class='all-videos-header'>All videos</span></div>";

    echo "<div class='formal-box'><div class='all-videos'>";

    foreach (array_slice($videos, 1) as $video) {
        $video_data = get_video_data($video['tag']);
        $name_of_uploader = user_name_from_id($video_data['uploader']);

        echo "
        <div class='normal-video-outline'>
            <a href='/courses/video/{$video['tag']}'>
                <img class='thumbnail'
                src='/resources/thumbnails/{$video['tag']}.jpg'
                alt='Your browser does not support this image type.'>
            </a>
            <div class='normal-video-info'>
                <span class='normal-video-name'>{$video_data['name']}</span>
                <span class='normal-video-creator'>By $name_of_uploader</span>
            </div>
        </div>
    ";
    }

    echo "</div></div>";
}
