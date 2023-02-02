<?php
require 'html_page.php';
require 'video_functionality.php';
require 'video_sidebar.php';
require 'comments_components.php';
require_once 'course_components.php';
html_header(title: 'Video', styled: true, scripted: true);



$tag = $_GET['tag'] ?? '';
$video_info = get_video_data($tag);
$video_id = get_course_id($tag);
$rating = get_rating_info($video_id);
$ratings = 0;

if (count($rating) == 0) {
    $video_has_ratings = false;
    $score = '';
} else {
    $score = number_format($rating[1], 1);
    $ratings = $rating[0];
    $video_has_ratings = true;
}

if ($_SESSION['auth']){
    $user_id = $_SESSION['uid'];
    if (has_course($tag, $user_id)){
        $has_video = '';
    } else{
        $has_video = 'not_have_video';
    }
} else {
    $has_video = 'not_have_video';
}

if (isset($_GET['tag']) and $video_info !== false and !$video_info['deleted']):
    $stars = $video_info['rating'] ? ('perm-star-' . $video_info['rating']) : 'stars-empty';
    ?>

    <div class="video-page-flexbox">
        <div class="sub-video-flexbox">
            <div class="video-and-description-big-box">
                <div class="video-and-description">
                    <div class="video-outline">
                        <div class="video" id="video" data-tag="<?php echo $_GET['tag'] ?>">
                            <?php if (($_SESSION['auth'] and owns_video($_SESSION['uid'], $tag)) or video_cost($tag)) { ?>
                                <video width="600" controls>
                                    <source src="/resources/videos/<?php echo $tag; ?>.mp4" type="video/mp4">
                                    Your browser does not support HTML video.
                                </video>
                            <?php } else { ?>
                                <div class="paid-video">
                                    <span class="paid-video-text">This is a premium video, please purchase it first before watching </span>
                                    <form class="shop" id="add">
                                        <?php
                                        $cart_add = '<span class="material-symbols-outlined">add_shopping_cart</span>';
                                        form_submit(text: "$cart_add Add to cart", extra_cls: 'long-btn');
                                        form_error('item');
                                        form_error();
                                        ?>
                                    </form>
                                    <form class="shop" id="cart">
                                        <?php
                                        $cart_go = '<span class="material-symbols-outlined">shopping_cart_checkout</span>';
                                        form_submit(text: "$cart_go Go to cart", extra_cls: 'long-btn form-submit-blue');
                                        form_error('item');
                                        form_error();
                                        ?>
                                    </form>
                                </div>
                            <?php } ?>
                            <?php if ($video_has_ratings) { ?>
                            <span class="video-name"><?php echo $video_info['name'] ?></span>
                            <div class="stars <?php echo "$stars $has_video" ?>"></div>
                                <p style="margin: 0"> ★<?php echo " $score <br> $ratings", $ratings !== 1 ? ' ratings' : ' rating' ?></p>
                            <div id="log"></div>

                            <?php }else {?>
                            <span class="video-name"><?php echo $video_info['name'] ?></span>
                            <div class="stars <?php echo "$stars $has_video" ?>"></div>
                             <div id="log"></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="description">
                        <button class="collapsible">
                            <span class="view-count"><?php echo $video_info['views'] ?> views</span>
                            <span class="upload-date">Posted <?php echo relative_time($video_info['upload_date']) ?> </span>
                        </button>
                        <div class="content">
                            <span class="video-info"><br><?php echo $video_info['description'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="comments-wrapper">
                <span class="comments-title"> Comments </span>
                <?php echo render_comment_form($_GET['tag'], false); ?>
                <div class="top"></div>
                <div class="comments" tag="<?php echo $_GET['tag'] ?>"></div>
                <div class="bottom"></div>
            </div>
        </div>
        <?php
        $success = video_sidebar($tag);
        echo "<span id='sidebar-load-success' tag='$success' style='display: none'></span>" ?>
    </div>

<?php else: ?>

    <link rel='stylesheet' href='/styles/form.css' type='text/css'/>

    <div class="form-content">
        <h1> Invalid link </h1>
        <div class="form-outline">
            <form>
                <p> The video at this link is missing or deleted </p>
                <?php
                echo '<div class="form-btns">';
                text_link('Go back home', '/');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php endif;

html_footer();
