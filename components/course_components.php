<?php

function get_course_info($tag): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT name, description, subject, creator, creation_date, views FROM db.courses WHERE tag = :tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['tag' => $tag]);

    return $sth->fetch(PDO::FETCH_ASSOC);
}

function course_creator($id): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT name, full_name FROM db.users WHERE id = :id';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['id' => $id]);

    return $sth->fetch(PDO::FETCH_ASSOC);
}

function get_videos($course): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT video_tag FROM db.course_videos WHERE course_tag = :course';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['course' => $course]);

    return $sth->fetchAll();
}

function render_thumbnails($videos): void
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT thumbnail FROM db.videos WHERE tag = :video';
    $sth = $pdo_read->prepare($sql);

    foreach ($videos as $video) {
        $sth->execute(['video' => $video['video_tag']]);
        $image_name = $sth->fetch();
        $thumbnail_url = '/videos/thumbnails/'.$image_name['thumbnail'].'.png';
        $video_url = 'video.php?tag='.$video['video_tag'];
        echo "<div class='thumbnail'><a href=$video_url><img class='thumbnail-image' src=$thumbnail_url alt='image of buck'></a><br>
                <span class='video-name'>{$video['video_tag']}</span></div>";
    }
}