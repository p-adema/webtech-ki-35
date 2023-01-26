<?php

require_once 'rating_functionality.php';
require_once 'video_functionality.php';

function render_best_videos_subject_page($subject): void
{
    $videos = best_videos_of_genre($subject);
    $popular_video = $videos[0];
    $popular_video_data = get_video_data($popular_video['tag']);
    $name_of_popular_uploader = name_of_uploader($popular_video_data['uploader']);
    
    echo "
        <div class='best-header-box'>
            <span class='best-video-header'>Recommended video</span>
        </div>
        <div class='best-video-outline'>
            <a href='/courses/video?tag={$popular_video['tag']}'>
                <img class='thumbnail'
                src='/resources/thumbnails/{$popular_video['tag']}.jpg'
                alt='Your browser does not support this image type.'>
            </a>
            <div class='best-video-info'>
                <span class='best-video-name'>{$popular_video_data['name']}</span>
                <span class='best-video-creator'>By $name_of_popular_uploader</span>
            </div>
        </div>
    ";

    echo "<div class='all-videos-header-box'><span class='all-videos-header'>All videos</span></div>";

    echo "<div class='formal-box'><div class='all-videos'>";

    foreach (array_slice($videos, 1) as $video) {
        $video_data = get_video_data($video['tag']);
        $name_of_uploader = name_of_uploader($video_data['uploader']);

        echo "
        <div class='normal-video-outline'>
            <a href='/courses/video?tag={$video['tag']}'>
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