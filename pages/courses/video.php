<?php
require 'html_page.php';
require 'video_functionality.php';
html_header(title: 'Video', styled: true, scripted: false);

$tag = $_GET['tag'];
$video_info = get_video_data($tag);

if (isset($_GET['tag']) and $video_info !== false): ?>
    <div class="video-and-description-big-box">
        <span class="logo">TempLogo</span>
        <div class="video-and-description">
            <div class="video-outline">
                <div class="video">
                    <video width="600" controls>
                        <source src="/videos/<?php echo $tag; ?>.mp4" type="video/mp4">
                        Your browser does not support HTML video.
                    </video>
                    <span class="video-name"><?php echo $video_info['name'] ?></span>
                </div>
            </div>
            <div class="description">
                <div class="views-and-when">
                    <span class="view-count"><?php echo $video_info['views'] ?> views</span>
                    <span class="upload-date">Posted <?php since_upload($video_info['upload_date']) ?> ago </span>
                </div>
                <span class="video-info"><?php echo $video_info['description'] ?></span>
            </div>
        </div>
    </div>

    <div class="comments">

    </div>
<?php

else:?> <span>This link doesn't seem quite right.</span>
<?php endif;

html_footer();
