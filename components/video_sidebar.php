<?php

function video_sidebar($video_id): string {
    $html = '';
    if (isset($pdo_write)) {
        /** @noinspection DuplicatedCode */
        $sql = 'SELECT (course_tag, `order`)  FROM db.course_videos WHERE (video_tag = :video_tag);';
        $data = ['video_tag' => htmlspecialchars($video_id)];

        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            return "Server error ID=39504427 die()";
        }
        $video_info = $sql_prep->fetch();
        $sql = 'SELECT (video_tag)  FROM db.course_videos WHERE (course_tag = :course_tag) and (`order` = :video_number);';
        $data = ['course_tag' => htmlspecialchars($video_info['course_tag']),
                'video_number' => htmlspecialchars($video_info['order'] - 1)];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            return "Server error ID=39504427 die()";
        }
        $previous_video = $sql_prep->fetch();
        $previous_video = $previous_video['video_tag'];
        $data = ['course_tag' => htmlspecialchars($video_info['course_tag']),
            'video_number' => htmlspecialchars($video_info['order'] + 1)];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            return "Server error ID=39504427 die()";
        }
        $next_video = $sql_prep->fetch();
        $next_video = $next_video['video_tag'];

        $sql = 'SELECT (name)  FROM db.videos WHERE (course_tag = :course_tag);';
        $data = ['course_tag' => htmlspecialchars($previous_video)];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            return "Server error ID=39504427 die()";
        }
        $previous_name = $sql_prep->fetch();
        $previous_name = $previous_name['name'];

        $data = ['course_tag' => htmlspecialchars($next_video)];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            return "Server error ID=39504427 die()";
        }
        $next_name = $sql_prep->fetch();
        $next_name = $next_name['name'];

        $sql = 'SELECT (name)  FROM db.courses WHERE (tag = :course_tag);';
        $data = ['course_tag' => htmlspecialchars($video_info['course_tag'])];
        $sql_prep = $pdo_write->prepare($sql);

        if (!$sql_prep->execute($data)) {
            return "Server error ID=39504427 die()";
        }
        $course_name = $sql_prep->fetch();
        $course_name = $course_name['name'];

        $html = "<div> class='video_sidebar'
        <div id='course'> 
        <span class='course_thumbnail'> </span>
        <p>$course_name</p></div>
        
        </div>";
    }
    return '<p>$course_name</p>';


}