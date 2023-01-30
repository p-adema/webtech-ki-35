<?php

require_once 'rating_functionality.php';



function render_genre_videos($videos): void
{
    echo "<div class='genre-overall-box disable-scrollbars'>";
    foreach ($videos as $video) {
        $video_name = get_video_data($video['tag'])['name'];
        $video_uploader = name_of_uploader(get_video_data($video['tag'])['uploader']);
        echo "
        <div class='genre-video-big-box'>
            <div class='genre-video-outline'>
                <a href='/courses/video?tag={$video['tag']}'>
                    <img class='best-video' src='/resources/thumbnails/{$video['tag']}.jpg'
                        alt='Your browser does not support this image type.'>
                </a>
            </div>
            <div class='genre-video-info'>
                <span class='genre-video-name'>$video_name</span>
                <span class='space-inbetween'></span>
                <span class='genre-video-creator'> by $video_uploader</span>
            </div>
        </div>
        ";
    }
    echo "</div>";
}