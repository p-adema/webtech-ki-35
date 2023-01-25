<?php
require 'html_page.php';
require 'video_functionality.php';
require 'video_sidebar.php';
require 'comments_components.php';
html_header(title: 'Video', styled: true, scripted: true);

$tag = $_GET['tag'];
$video_info = get_video_data($tag);

if (isset($_GET['tag']) and $video_info !== false): ?>

    <div class="video-page-flexbox">
        <div class="test">
        <div class="video-and-description-big-box">
            <div class="video-and-description">
                <div class="video-outline">
                    <div class="video">
                        <?php if (($_SESSION['auth'] and owns_video($_SESSION['uid'], $tag)) or video_cost($tag)) { ?>
                            <video width="600" controls>
                                <source src="/videos/<?php echo $tag; ?>.mp4" type="video/mp4">
                                Your browser does not support HTML video.
                            </video>
                        <?php } else { ?>
                            <div class="paid-video">
                                <span class="paid-video-text">This is a paid video, purchase it first to watch.</span>
                                <form class="shop" id="add">
                                    <?php
                                    form_submit(text: 'Add to cart', extra_cls: 'long-btn');
                                    form_error('item');
                                    form_error();
                                    ?>
                                </form>
                                <form class="shop" id="cart">
                                    <?php
                                    form_submit(text: 'Go to cart', extra_cls: 'long-btn form-submit-blue');
                                    form_error('item');
                                    form_error();
                                    ?>
                                </form>
                            </div>
                        <?php } ?>
                        <span class="video-name"><?php echo $video_info['name'] ?></span>
                        <div class="stars stars-empty"></div>
                        <div id="log"></div>
                    </div>
                </div>
                <div class="description">
                    <button class="collapsible">
                        <span class="view-count"><?php echo $video_info['views'] ?> views</span>
                        <span class="upload-date">Posted <?php since_upload($video_info['upload_date']) ?> ago </span>
                    </button>
                    <div class="content hide">
                        <span class="video-info"><br><?php echo $video_info['description'] ?></span>
                    </div>
                </div>
            </div>
        </div>
            <form class="comment-submit" action="/api/courses/video.php" method="POST">
                <?php
                form_input('message', 'Comment', placeholder: 'Type your comment');
                form_error();

                echo '<div class="comment-btn">';
                form_submit();
                echo '</div>';
                ?>
            </form>
        <div class="comments-wrapper">
            <span class="comments-title"> Comments </span>
            <div class="comments" tag="<?php echo $_GET['tag'] ?>"></div>
        </div>
        </div>
        <?php
        $success = video_sidebar($tag) ;
        echo "<span id='sidebar-load-success' tag='$success' style='display: none'></span>"?>

    </div>


<?php else: ?>
    <span>This link doesn't seem quite right.</span>
<?php endif;

html_footer();
