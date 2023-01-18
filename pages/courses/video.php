<?php
require 'html_page.php';
require 'video_functionality.php';
html_header(title: 'Video', styled: true, scripted: false);

$tag = $_GET['tag'];
$video_info = get_video_data($tag);

if (isset($_GET['tag']) and $video_info !== false): ?>
    <body>
    <div class="video-outline">
        <div class="video">
            <video width="600" controls>
                <source src="/videos/<?php echo $tag; ?>.mp4" type="video/mp4">
                Your browser does not support HTML video.
            </video>
        </div>
    </div>
    </body>
    <?php

else:?> <span>This link doesn't seem quite right.</span>
<?php endif;

html_footer();
