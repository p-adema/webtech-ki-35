<?php
require 'html_page.php';
require 'relative_time.php';
require 'course_components.php';
require 'video_functionality.php';
html_header(title: 'Course', styled: true, scripted: true);

$course_tag = $_GET['tag'] ?? '';
if ($_SESSION['auth']) {
    $user_id = $_SESSION['uid'];
    $has_course = user_owns_item($user_id, $course_tag);
} else {
    $has_course = false;
}
$course_info = get_course_info($course_tag);
if (isset($_GET['tag']) and $course_info !== false and !$course_info['restricted']):
    $course_id = get_item_id($course_tag);
    $cart = new Cart;
    $course_in_cart = in_array($course_id, $cart->ids());
    $course_creator = course_creator($course_info['creator']);
    $videos = get_videos($course_tag);
    $video_names = get_video_names($videos);
    $rating = get_rating_info($course_id);
    $score = 0;
    $ratings = 0;
    $course_has_ratings = true;
    $course_creation_date = explode(' ', $course_info['creation_date']);
    if (count($rating) == 0) {
        $course_has_ratings = false;
    } else {
        $score = number_format($rating[1], 1);
        $ratings = $rating[0];
    }
    $cart = new Cart();
    $tag = $_GET['tag'] ?? '';
    $video_info = get_video_data($tag);
    ?>
    <div class="course-page">
        <div class="information-block">
            <div class="title-box"><p id="course-title"> <?php echo $course_info['name'] ?></p>
                <p id="author"> <?php echo $course_creator['name'] ?> </p>
            </div>
            <div class="stretch-box-1"></div>

            <div class="video-information">
                <p id="description"><?php echo $course_info['description'] ?> </p>
                <p id="total-videos"> This course contains
                    <?php $count = count($videos);
                    echo $count, $count === 1 ? ' video' : 'videos'; ?> </p>
                <p id="since">Creation date: <?php echo $course_creation_date[0] ?> </p>
                <p id="views-and-time"> <?php echo number_format($course_info['views'], 0, ''), $course_info['views'] !== 1 ? ' total views' : ' total view' ?> </p>

            </div>
            <div class="stretch-box-2"></div>

            <?php
            if ($has_course):
                if ($course_has_ratings):
                    ?>
                    <div class="ratings-box">
                        <p class="star-score"> ★<?php echo "$score" ?> <br></p>
                        <div class="stars-empty stars"></div>
                        <p class="total-ratings"><?php echo number_format($ratings, 0, ''), $ratings !== 1 ? ' ratings' : ' rating' ?></p>
                    </div>
                <?php else: ?>
                    <div class="ratings-box">
                        <div class="stars-empty stars"></div>
                        <p class="total-ratings">This course does not have ratings </p>
                    </div>
                <?php
                endif;
            else:
                if ($course_has_ratings): ?>
                    <div class="ratings-box">
                        <p class="star-score"> ★<?php echo "$score" ?> <br></p>
                        <p class="total-ratings"><?php echo number_format($ratings, 0, ''), $ratings !== 1 ? ' ratings' : ' rating' ?></p>
                    </div>

                <?php else: ?>
                    <div class="ratings-box">
                        <p class="total-ratings">This course does not have ratings </p></div>
                <?php
                endif;
            endif;
            ?>


            <div class="stretch-box-3"></div>
            <?php
            if ($has_course):
                ?>
                <div class="course-buying-box">
                    <p id="course-owned"> You own this course</p>
                </div>
            <?php
            else:
                ?>
                <div class="course-buying-box">
                    <div class="course-buying">
                        <p id="price"> <?php
                            if (course_price($course_tag) == 0) {
                                echo 'This course is free';
                            } else {
                                $html = "The price of this course <br> is  " . number_format($cart->tag_price($tag), 2) . ' euro.';
                                echo $html;
                            } ?>
                        </p>
                    </div>
                    <div class="add-to-cart-box">
                        <?php if ($course_in_cart): ?>
                            <form class="shop" id="cart" style="display: block;">
                                <?php
                                $cart_go = '<span class="material-symbols-outlined">shopping_cart_checkout</span>';
                                form_submit(text: "$cart_go Go to cart", extra_cls: 'long-btn form-submit-blue');

                                ?>
                            </form>
                        <?php else: ?>


                            <form class="shop" id="add">
                                <?php
                                $cart_add = '<span class="material-symbols-outlined">add_shopping_cart</span>';
                                form_submit("$cart_add Add to cart", extra_cls: 'long-btn');
                                ?>
                            </form>
                            <form class="shop" id="cart">
                                <?php
                                $cart_go = '<span class="material-symbols-outlined">shopping_cart_checkout</span>';
                                form_submit(text: "$cart_go Go to cart", extra_cls: 'long-btn form-submit-blue');

                                ?>
                            </form>
                        <?php endif; ?>
                        <div class="course_tag" data-tag="<?php echo $_GET['tag'] ?>"></div>

                    </div>
                </div>

            <?php
            endif;
            ?>
        </div>

        <div class="video-block">
            <div class="video-border">
                <?php display_course_videos($course_tag) ?>

            </div>

        </div>

    </div>

<?php else: ?>

    <link rel='stylesheet' href='/styles/form.css' type='text/css'>

    <div class="form-content">
        <h1> Invalid link </h1>
        <div class="form-outline">
            <form>
                <p> The course at this link is missing or restricted </p>
                <?php
                echo '<div class="form-btns">';
                display_text_link('Go back home', '/');
                echo '</div>';
                ?>
            </form>
        </div>
    </div>

<?php endif;

html_footer();
