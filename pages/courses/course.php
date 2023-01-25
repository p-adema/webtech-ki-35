<?php
require 'html_page.php';
require 'relative_time.php';
require 'course_components.php';
require 'video_functionality.php';
html_header(title: 'Course', styled: true, scripted: true);

$course_tag = $_GET['tag'];
if ($_SESSION['auth']) {
    $user_id = $_SESSION['uid'];
    $has_course = has_course($course_tag, $user_id);
} else {
    $has_course = false ;
}
$course_id = get_course_id($course_tag);
$cart = new Cart;
$course_in_cart = in_array($course_id, $cart->ids());
$course_info = get_course_info($course_tag);
$course_creator = course_creator($course_info['creator']);
$time_since = time_since($course_info['creation_date']);
$videos = get_videos($course_tag);
$video_names = get_video_names($videos);
?>
    <div class="course-page">
        <div class="information-block">
            <div class="title-box"> <p id="course-title"> <?php echo $course_info['name'] ?></p>
            </div>
            <div class="author-box">
            <p id="author"> Author: <br> <?php echo $course_creator['name'] ?> </p>
            </div>

                <?php if ($has_course) :?>
                <div class="ratings-box">
                        <div class="stars-empty stars"> </div>
                    <p> Rating </p>
                </div>
                <?php else: ?>
                <div class="ratings-box"> <p> Stars <br> rating</p> </div>
                <?php endif;?>


            <div class="description-box">
                <p id="views-and-time"> <?php echo $course_info['views'] ?> views <?php echo $time_since ?> ago</p>
                <p id="description"><?php echo $course_info['description'] ?> </p>
            </div>
            <div class="video-information">
                <p id="subject"> Subject: <br> <?php echo $course_info['subject'] ?></p>
                <p id="total-videos"> This course contains <?php echo count($videos) ?> videos</p>
            </div>
            <?php
            if ($has_course):
                ?>
                <div class="course-buying">
                    <p id="course-owned"> You own this course</p>
                </div>
            <?php
            else:
                ?>
                <div class="course-buying">
                    <p id="price"> <?php
                        if (course_price($course_tag) == 0) {
                            echo 'This course is free';
                        } else {
                            $html = 'The price of this course is ' . course_price($course_tag) . ' euro.';
                            echo $html;
                        } ?>
                    </p>
                </div>
                <div class="stretch-box"> </div>
                <div class="add-to-cart-box">
                    <?php if ($course_in_cart):?>
                <form class="shop" id="cart" style="display: block">
                    <?php
                    form_submit(text: 'Go to cart', extra_cls: 'long-btn form-submit-blue');
                    form_error('item');
                    form_error();
                    ?>
                </form>
            <?php else: ?>


                    <form class="shop" id="add">
                        <?php
                        form_submit("Add to cart <span
                                    class='material-symbols-outlined'>add_shopping_cart</span>", extra_cls: 'long-btn');
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
            <?php endif; ?>
                    <div class="course_tag" tag="<?php echo $_GET['tag'] ?>"></div>

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


<?php

//echo get_video_watch_amount(2, 'example_paid');

//To do: Allow users to save their courses.

html_footer();
