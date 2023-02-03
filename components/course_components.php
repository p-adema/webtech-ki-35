<?php

/**
 * Gets the full information array for a course
 * @param string $course_tag courses.tag
 * @return array|false name, description, subject, creator, creation_date, views, restricted
 */
function get_course_info(string $course_tag): array|false
{
    require_once 'pdo_read.php';


    $sql = 'SELECT name, description, subject, creator, creation_date, views, restricted 
            FROM db.courses c
                INNER JOIN items i on c.tag = i.tag
            WHERE c.tag = :tag';

    $sth = prepare_readonly($sql);
    $sth->execute(['tag' => $course_tag]);

    return $sth->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param int $user_id users.id
 * @return array name, full_name
 */
function user_names_from_id(int $user_id): array
{
    require_once 'pdo_read.php';


    $sql = 'SELECT name, full_name FROM db.users WHERE id = :id';
    $sth = prepare_readonly($sql);
    $sth->execute(['id' => $user_id]);

    return $sth->fetch(PDO::FETCH_ASSOC);
}

/**
 * Gets the item tags of all videos in a course
 * @param string $course_tag courses.tag
 * @return array of videos.tag
 */
function course_video_tags(string $course_tag): array
{
    require_once 'pdo_read.php';


    $sql = 'SELECT video_tag FROM db.course_videos WHERE course_tag = :course';
    $sth = prepare_readonly($sql);
    $sth->execute(['course' => $course_tag]);

    return $sth->fetchAll();
}

/**
 * @param array $video_tags Array of arrays with key 'video_tag' as videos.tag
 * @return array Mapping video.tag => video.name
 */
function videos_get_names(array $video_tags): array
{
    require_once 'pdo_read.php';


    $sql = 'SELECT name FROM db.videos WHERE tag = :video_tag';
    $sth = prepare_readonly($sql);

    $video_tag_to_name = [];

    foreach ($video_tags as $video_info) {
        $sth->execute(['video_tag' => $video_info['video_tag']]);
        $video_tag_to_name[$video_info['video_tag']] = $sth->fetch()['name'];
    }

    return $video_tag_to_name;
}

/**
 * @param string $course_tag courses.tag
 * @return float Price of the course
 */
function course_price_from_tag(string $course_tag): float
{
    require_once 'pdo_read.php';


    $sql = 'SELECT price FROM db.items WHERE tag = :course_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['course_tag' => $course_tag]);
    $price = $sth->fetch();
    return $price['price'];
}

/**
 * @param int $user_id users.id
 * @param string $item_tag items.tag
 * @return bool Item is free or user owns item
 */
function user_can_access_item(int $user_id, string $item_tag): bool
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
    $sth->execute(['course_tag' => $item_tag, 'user_id' => $user_id]);
    $owned = $sth->fetch();
    return $owned['free'] or $owned['owned'];
}

/** Display all videos of a course
 * @param string $course_tag courses.tag
 */
function display_course_videos(string $course_tag): void
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

/**
 * @param string $item_tag items.tag
 * @return int items.id
 */
function item_id_where_tag(string $item_tag): int
{
    require_once 'pdo_read.php';


    $sql = 'SELECT id FROM db.items WHERE tag = :course_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['course_tag' => $item_tag]);
    $result = $sth->fetch();
    return $result['id'];
}

/**
 * @param int $item_id items.id
 * @return array [total ratings, average rating]
 */
function get_rating_info(int $item_id): array
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
