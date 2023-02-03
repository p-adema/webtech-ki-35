<?php

function get_course_info($tag): array|false
{
    require_once 'pdo_read.php';


    $sql = 'SELECT name, description, subject, creator, creation_date, views, restricted 
            FROM db.courses c
                INNER JOIN items i on c.tag = i.tag
            WHERE c.tag = :tag';

    $sth = prepare_readonly($sql);
    $sth->execute(['tag' => $tag]);

    return $sth->fetch(PDO::FETCH_ASSOC);
}

function course_creator($id): array
{
    require_once 'pdo_read.php';


    $sql = 'SELECT name, full_name FROM db.users WHERE id = :id';
    $sth = prepare_readonly($sql);
    $sth->execute(['id' => $id]);

    return $sth->fetch(PDO::FETCH_ASSOC);
}

function get_videos($course): array
{
    require_once 'pdo_read.php';


    $sql = 'SELECT video_tag FROM db.course_videos WHERE course_tag = :course';
    $sth = prepare_readonly($sql);
    $sth->execute(['course' => $course]);

    return $sth->fetchAll();
}

function get_video_names($videos): array
{
    require_once 'pdo_read.php';


    $sql = 'SELECT name FROM db.videos WHERE tag = :video_tag';
    $sth = prepare_readonly($sql);

    $array = array();

    foreach ($videos as $video) {
        $sth->execute(['video_tag' => $video['video_tag']]);
        $array[$video['video_tag']] = $sth->fetch()['name'];
    }

    return $array;
}

function course_price($course_tag): string
{
    require_once 'pdo_read.php';


    $sql = 'SELECT price FROM db.items WHERE tag = :course_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['course_tag' => $course_tag]);
    $price = $sth->fetch();
    return $price['price'];
}

function user_owns_item($user_id, $course_tag): bool
{
    require_once 'pdo_read.php';


    $sql = '
SELECT COALESCE(v.free, c.free) as free, o.id as owned
FROM items i
    LEFT JOIN videos v on i.tag = v.tag
    LEFT JOIN db.courses c on i.tag = c.tag
    LEFT JOIN ownership o on i.tag = o.item_tag AND user_id = :user_id
WHERE i.tag = :course_tag
';
    $sth = prepare_readonly($sql);
    $sth->execute(['course_tag' => $course_tag, 'user_id' => $user_id]);
    $owned = $sth->fetch();
    return $owned['free'] or $owned['owned'];
}

function display_course_videos($course_tag): void
{
    require_once "pdo_write.php";

    $sql = 'SELECT  video_tag, `order` FROM db.course_videos WHERE course_tag = :course_tag;';
    $data = ['course_tag' => $course_tag];

    $sql_prep = prepare_write($sql);

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

        $sql_prep = prepare_write($sql);

        if (!$sql_prep->execute($data)) {
            echo "Server error ID=39504427 die()";
        }
        $video_name = $sql_prep->fetch();
        $video_name = $video_name['name'];
        $video_tag = $all_videos[$x]['video_tag'];
        echo "<a href='/courses/video/$video_tag'><div class='single-video-block'> 
    
                        <div class='thumbnail'><img class='thumbnail-picture' src='/resources/thumbnails/$video_tag.jpg' alt='Video thumbnail'></div> 
                        <p class='thumbnail-text' >$video_name</p>
                       </div></a>";
    }

}

function get_item_id($item_tag): string
{
    require_once 'pdo_read.php';


    $sql = 'SELECT id FROM db.items WHERE tag = :course_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['course_tag' => $item_tag]);
    $result = $sth->fetch();
    return $result['id'];
}

function get_rating_info($item_id): array
{
    require_once 'pdo_read.php';


    $sql = 'SELECT rating FROM db.ratings WHERE item_id = :item_id';
    $sth = prepare_readonly($sql);
    $sth->execute(['item_id' => $item_id]);
    $ratings = $sth->fetchAll();
    $total_ratings = count($ratings);
    $score = 0;
    if ($total_ratings == 0) {
        return [];
    } else {
        for ($x = 0; $x < $total_ratings; $x++) {
            $score += $ratings[$x]['rating'];
        }
        $score = $score / $total_ratings;


        return [$total_ratings, $score];
    }


}
