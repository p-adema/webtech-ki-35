<?php

require_once 'video_functionality.php';

function render_courses(): void
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT tag FROM db.items WHERE type = :course ORDER BY rating DESC';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['course' => 'course']);

    $courses = $sth->fetchAll(PDO::FETCH_ASSOC);

    $best_course = $courses[0];
    $best_course_info = get_course_info($best_course['tag']);
    $best_creator_name = user_name_from_id($best_course_info['creator']);

    echo "
        <div class='best-header-box'>
            <span class='best-course-header'>Recommended course</span>
        </div>
            <div class='course'>
                <a href='/courses/course/{$best_course['tag']}'>
                    <img class='thumbnail' src='/resources/thumbnails/{$best_course['tag']}.jpg'>
                </a>
                <div class='best-course-info'>
                <span class='best-course-name'>{$best_course_info['name']}</span>
                <span class='best-course-creator'>By $best_creator_name</span>
                </div>
            </div>
    ";
    echo "<div class='all-courses-header-box'><span class='all-courses-header'>All courses</span></div>";

    echo "<div class='formal-box'><div class='all-courses'>";

    foreach (array_slice($courses, 1) as $course) {
        $course_info = get_course_info($course['tag']);
        $creator_name = user_name_from_id($course_info['creator']);
        echo "
                <div class='course'>
                    <a href='/courses/course/{$course['tag']}'>
                        <img class='thumbnail' src='/resources/thumbnails/{$course['tag']}.jpg'>
                    </a>
                    <div class='course-info'>
                        <span class='course-name'>{$course_info['name']}</span>
                        <span class='course-creator'>By $creator_name</span>
                    </div>
                </div>
        ";
    }

    echo "</div></div>";
}

function get_course_info($course_tag): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT name, description, subject, creator, creation_date, views FROM db.courses WHERE tag = :course_tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['course_tag' => $course_tag]);

    return $sth->fetch();
}
