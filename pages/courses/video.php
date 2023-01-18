<?php
require 'html_page.php';
html_header(title: 'Video', styled: true, scripted: false);

?>

    <body>
    <div class="video-outline">
        <div class="video">
            <video width="600" controls>
                <source src="/videos/example.mp4" type="video/mp4">
                Your browser does not support HTML video.
            </video>
        </div>
    </div>
    </body>

<?php
html_footer();
