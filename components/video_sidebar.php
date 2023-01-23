<?php

function video_scroll($course_tag): string
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
            $html .= "<div class='video-block' id='$x'> 
    
                        <div class='course-thumbnail'></div> 
                        <p>$video_name</p>
                       </div>"; #Hier moet de thumbnail komen

        }
        return $html;
    }
    return '';
}

function video_sidebar($video_id): void
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
            echo "Server error ID=39504427 die()";
        }
        $video_info = $sql_prep->fetch();
        $sql = 'SELECT (video_tag)  FROM db.course_videos WHERE (course_tag = :course_tag) and (`order` = :video_number);';
        $data = ['course_tag' => htmlspecialchars($video_info['course_tag']),
            'video_number' => htmlspecialchars($video_info['order'] - 1)];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            echo "Server error ID=39504427 die()";
        }
        $previous_video = $sql_prep->fetch();
        $previous_video = $previous_video['video_tag'];
        $data = ['course_tag' => htmlspecialchars($video_info['course_tag']),
            'video_number' => htmlspecialchars($video_info['order'] + 1)];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            echo "Server error ID=39504427 die()";
        }
        $next_video = $sql_prep->fetch();
        $next_video = $next_video['video_tag'];

        $sql = 'SELECT (name)  FROM db.videos WHERE (tag = :video_tag);';
        $data = ['video_tag' => htmlspecialchars($previous_video)];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            echo "Server error ID=39504427 die()";
        }
        $previous_name = $sql_prep->fetch();
        $previous_name = $previous_name['name'];

        $data = ['video_tag' => htmlspecialchars($next_video)];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            echo "Server error ID=39504427 die()";
        }
        $next_name = $sql_prep->fetch();
        $next_name = $next_name['name'];

        $sql = 'SELECT (name)  FROM db.courses WHERE (tag = :course_tag);';
        $data = ['course_tag' => htmlspecialchars($video_info['course_tag'])];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            echo "Server error ID=39504427 die()";
        }
        $course_name = $sql_prep->fetch();
        $course_name = $course_name['name'];

        $html = "<div class='video-sidebar'> 
    <div class='course-block-around'> 
    <div class='course-block'> 
    <div class='course-thumbnail'></div>
    <p>$course_name <br> auteur</p>
    </div>
    </div>
    <div class='big-video-block'>" .
            video_scroll($video_info['course_tag']) . "
    
    </div>
    </div>
    
    
    
    </div>";
        video_scroll($video_info['course_tag']);
        echo $html;
    }
}





