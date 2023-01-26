<?php
require 'html_page.php';
require 'video_sidebar.php';
require_once 'rating_functionality.php';
require_once 'video_functionality.php';
require_once 'video_homepage_functionality.php';
html_header(title: 'Video Page', styled: true, scripted: 'ajax');

$popular_video_tag = get_popular_video();
?>
    <div class="every-everything">
        <div class="everything">
            <div class="best-video-big-box">
                <div class="best-video-small-box">
                    <div class="lots-of-things">
                        <div class="slogan-box">
                            <span class="slogan">Your knowledge can't grow outside the grove</span>
                        </div>
                        <div class="everything-best-video">
                            <div class="best-video-header-box">
                                <div class="best-video-header">Watch the best our creators have to offer!</div>
                            </div>
                            <div class="best-video-outline">
                                <a href="/courses/video?tag=<?php echo $popular_video_tag ?>">
                                    <img class="best-video"
                                         src="/resources/thumbnails/<?php echo $popular_video_tag ?>.jpg"
                                         alt="Your browser does not support this image type.">
                                </a>
                                <div class="best-video-info">
                                    <span class="best-video-name"><?php echo get_video_data($popular_video_tag)['name'] ?></span>
                                    <span class="best-video-creator">By <?php echo name_of_uploader(get_video_data($popular_video_tag)['uploader']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="genres">
                <div class="genre-box">
                    <span class="genre-header">Physics</span>
                    <div class="please-center">
                        <?php
                        render_genre_videos(best_videos_of_genre('physics'));
                        ?>
                    </div>
                </div>
                <div class="genre-box">
                    <span class="genre-header">Geography</span>
                    <div class="please-center">
                        <?php
                        render_genre_videos(best_videos_of_genre('geography'));
                        ?>
                    </div>
                </div>
                <div class="genre-box">
                    <span class="genre-header">Biology</span>
                    <div class="please-center">
                        <?php
                        render_genre_videos(best_videos_of_genre('biology'));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
# Search bar in de top-navbar verwerkt.
# Een aanbevolen video gebaseerd op populariteit in de afgelopen maand.
# Hieronder de verschillende genres van videos met daarbij een knop die je naar een aparte pagina van dat genre brengt.
# Hier horen ook 3 à 4 videos bij die het meest populair zijn in die categorie.

html_footer();
