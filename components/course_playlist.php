<?php

function render_playlist_items($course_tag, $video_number): string
{
    require_once "pdo_write.php";
    $sql = 'SELECT  video_tag, `order` FROM db.course_videos WHERE course_tag = :course_tag;';
    $data = ['course_tag' => htmlspecialchars($course_tag)];

    $sql_prep = prepare_write($sql);

    if (!$sql_prep->execute($data)) {
        return "Server error ID=39504427 die()";
    }
    $all_videos = $sql_prep->fetchAll();
    usort($all_videos, function ($a, $b) {
        return $a['order'] - $b['order'];
    });
    $html = '';
    for ($order = 0; $order < count($all_videos); $order++) {
        $sql = 'SELECT  name FROM db.videos WHERE tag = :tag;';
        $data = ['tag' => htmlspecialchars($all_videos[$order]['video_tag'])];

        $sql_prep = prepare_write($sql);

        if (!$sql_prep->execute($data)) {
            return "Server error ID=39504427 die()";
        }
        $video_name = $sql_prep->fetch();
        $video_name = $video_name['name'];
        $video_tag = $all_videos[$order]['video_tag'];
        if ($order == $video_number) {
            $html .= "
<div class='video-block' id='current-video-playing' > 
    <img src='/resources/thumbnails/$video_tag.jpg' class='thumbnail' alt='Video thumbnail'> 
    <p>$video_name</p>
</div>";
        } else {
            $html .= "
<a href='/courses/video/$video_tag'>
    <div class='video-block' id='video_scroll_$order'> 
        <img src='/resources/thumbnails/$video_tag.jpg' class='thumbnail' alt='Video thumbnail'> 
        <p>$video_name</p>
    </div>
</a>";
        }

    }
    return $html;

}

function display_course_playlist(string $video_tag): bool
{
    require_once "pdo_write.php";

    $sql_video = 'SELECT course_tag, `order`  FROM db.course_videos WHERE video_tag = :video_tag;';
    $data_video = ['video_tag' => $video_tag];

    $sql_prep = prepare_write($sql_video);
    if (!$sql_prep->execute($data_video)) {
        return false;
    }
    $video_info = $sql_prep->fetch();
    if (empty($video_info)) {
        return false;
    }

    $sql = 'SELECT (name)  FROM db.courses WHERE (tag = :course_tag);';
    $data_course = ['course_tag' => $video_info['course_tag']];
    $sql_prep = prepare_write($sql);

    if (!$sql_prep->execute($data_course)) {
        return false;
    }
    $course_name = $sql_prep->fetch();
    $course_name = $course_name['name'];
    $course_tag = $video_info['course_tag'];

    $sql = 'SELECT u.name FROM courses as c 
                INNER join users u on c.creator = u.id
                WHERE c.tag = :course_tag;';
    $sql_prep = prepare_write($sql);
    if (!$sql_prep->execute($data_course)) {
        return false;
    }
    $creator_name = $sql_prep->fetch();
    $creator_name = $creator_name['name'];
    $playlist_items = render_playlist_items($video_info['course_tag'], $video_info['order']);

    echo "
<div class='course-playlist'> 
    <div class='course-block-around'> 
        <a href='/courses/course/$course_tag'> 
            <div class='course-block'> 
            <img src='/resources/thumbnails/$course_tag.jpg' class='thumbnail' alt='Video thumbnail'> 
            <p> 
                $course_name <br> <span class='author' >$creator_name</span>
            </p>
            </div> 
        </a>
    </div>
    <div class='big-video-block'>
        $playlist_items
    </div>
</div>";

    return $video_info['order'];
}
