<?php

function video_scroll($course_tag, $video_number): string
{
    require_once "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {
        $errors['submit'][] = 'Internal server error (unable to connect to database)';
        $valid = false;
    }
    if (isset($pdo_write)) {
        /** @noinspection DuplicatedCode */
        $sql = 'SELECT  video_tag, `order` FROM db.course_videos WHERE course_tag = :course_tag;';
        $data = ['course_tag' => htmlspecialchars($course_tag)];

        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            return "Server error ID=39504427 die()";
        }
        $all_videos = $sql_prep->fetchAll();
        usort($all_videos, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        $html = '';
        for ($x = 0; $x < count($all_videos); $x++) {
            $sql = 'SELECT  name FROM db.videos WHERE tag = :tag;';
            $data = ['tag' => htmlspecialchars($all_videos[$x]['video_tag'])];

            $sql_prep = $pdo_write->prepare($sql);

            if (!$sql_prep->execute($data)) {
                return "Server error ID=39504427 die()";
            }
            $video_name = $sql_prep->fetch();
            $video_name = $video_name['name'];
            $video_tag = $all_videos[$x]['video_tag'];
            if ($x == $video_number) {
                $html .= "<div class='video-block' id='current-video-playing' > 
   
                        <div class='thumbnail'></div> 
                        <p>$video_name</p>
                       </div>"; #Hier moet de thumbnail komen
            } else {
                $html .= "<a href='/courses/video/$video_tag'><div class='video-block' id='video_scroll_$x'> 
    
                        <div class='thumbnail'></div> 
                        <p>$video_name</p>
                       </div></a>";
            }

        }
        return $html;
    }
    return '';
}

function video_sidebar($video_id): bool
{
    require_once "pdo_write.php";
    try {
        $pdo_write = new_pdo_write(err_fatal: false);
    } catch (PDOException) {
        $errors['submit'][] = 'Internal server error (unable to connect to database)';
        $valid = false;
    }
    $html = '';
    if (isset($pdo_write)) {
        /** @noinspection DuplicatedCode */
        $sql = 'SELECT course_tag, `order`  FROM db.course_videos WHERE video_tag = :video_tag;';
        $data = ['video_tag' => htmlspecialchars($video_id)];

        $sql_prep = $pdo_write->prepare($sql);
        if (!$sql_prep->execute($data)) {
            return false;
        }
        $video_info = $sql_prep->fetch();
        if (empty($video_info)){
            return false;
        }

        $sql = 'SELECT (name)  FROM db.courses WHERE (tag = :course_tag);';
        $data = ['course_tag' => htmlspecialchars($video_info['course_tag'])];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            return false;
        }
        $course_name = $sql_prep->fetch();
        $course_name = $course_name['name'];
        $course_tag = $video_info['course_tag'];

        $sql = 'SELECT u.name FROM courses as c 
                INNER join users u on c.creator = u.id
                WHERE c.tag = :course_tag;';
        $data = ['course_tag' => $video_info['course_tag']];
        $sql_prep = $pdo_write->prepare($sql);
        if (!$sql_prep->execute($data)) {
            return false;
        }
        $creator_name = $sql_prep->fetch();
        $creator_name = $creator_name['name'];

        $html = "<div class='video-sidebar'> 
    <div class='course-block-around'> 
    <a href='/'> <div class='course-block'> 
    <div class='thumbnail'></div>
    <p>$course_name <br> <span class='author' >$creator_name</span></p>
    </div> </a>
    </div>
    <div class='big-video-block'>" .
            video_scroll($video_info['course_tag'], $video_info['order']) . "
    
    </div>
    </div>
    
    
    
    </div>";
        video_scroll($video_info['course_tag'], $video_info['1']);
        echo $html;
    }
    return true;
}





