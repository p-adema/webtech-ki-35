<?php

function get_course_info($tag): array|false
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

function get_video_names($videos): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT name FROM db.videos WHERE tag = :video_tag';
    $sth = $pdo_read->prepare($sql);

    $array = array();

    foreach ($videos as $video) {
        $sth->execute(['video_tag' => $video['video_tag']]);
        $array[$video['video_tag']] = $sth->fetch()['name'];
    }

    return $array;
}

function course_price($course_tag): string {
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT price FROM db.items WHERE tag = :course_tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['course_tag' => $course_tag]);
    $price = $sth->fetch();
    return $price['price'];
}

function has_course($course_tag, $user_id): bool {
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT id FROM db.course_ownership WHERE item_tag = :course_tag and user_id = :user_id';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['course_tag' => $course_tag, 'user_id' => $user_id]);
    $id = $sth->fetch();
    if (empty($id)) {
        return false;
    } else {
        return true;
    }
}

function display_course_videos($course_tag): void
{
    require_once "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {
    }
    if (isset($pdo_write)) {
        /** @noinspection DuplicatedCode */
        $sql = 'SELECT  video_tag, `order` FROM db.course_videos WHERE course_tag = :course_tag;';
        $data = ['course_tag' => $course_tag];

        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            echo "Server error ID=39504427";
        }
        $all_videos = $sql_prep->fetchAll();
        usort($all_videos, function ($a, $b) {
            return $a['order'] - $b['order'];
        });
        for ($x = 0; $x < count($all_videos); $x++) {
            $sql = 'SELECT  name FROM db.videos WHERE tag = :tag;';
            $data = ['tag' => ($all_videos[$x]['video_tag'])];

            $sql_prep = $pdo_write->prepare($sql);

            if (!$sql_prep->execute($data)) {
                echo "Server error ID=39504427 die()";
            }
            $video_name = $sql_prep->fetch();
            $video_name = $video_name['name'];
            $video_tag = $all_videos[$x]['video_tag'];
                echo "<a href='/courses/video/$video_tag'><div class='single-video-block'> 
    
                        <div class='thumbnail'><img class='thumbnail-picture' src='/resources/thumbnails/$video_tag.jpg'></div> 
                        <p>$video_name</p>
                       </div></a>";
            }

        }
    }

function get_course_id($course_tag): string {
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT id FROM db.items WHERE tag = :course_tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['course_tag' => $course_tag]);

    return $sth->fetch()['id'];
}

function get_rating_info($item_id): array{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT rating FROM db.ratings WHERE item_id = :item_id';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['item_id' => $item_id]);
    $ratings = $sth->fetchAll();
    $total_ratings = count($ratings);
    $score = 0;


    for ($x = 0; $x < $total_ratings; $x++) {
        $score += $ratings[$x]['rating'];
    }
    $score = $total_ratings === 0 ? 3 : $score/$total_ratings;


    return [$total_ratings, $score];
}