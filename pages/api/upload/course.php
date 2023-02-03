<?php
require "html_page.php";

$errors = [
    'title' => [],
    'description' => [],
    'subject' => [],
    'price' => [],
    'tags' => [],
    'submit' => []
];
/** @noinspection DuplicatedCode */
$valid = true;

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$subject = $_POST['subject'] ?? '';
$free_raw = $_POST['free'] ?? '';
$price = $_POST['price'] ?? '';
$tags = $_POST['tags'] ?? [];


if (empty($title)) {
    $errors['title'][] = 'Please provide a course title';
    $valid = false;
} elseif (strlen(htmlspecialchars($title)) > 100) {
    $errors['title'][] = 'Please provide a shorter course title (100 characters max)';
    $valid = false;
}

if (empty($description)) {
    $errors['description'][] = 'Please provide a course description';
    $valid = false;
} elseif (strlen(htmlspecialchars($description)) > 4096) {
    $errors['description'][] = 'Please provide a shorter course description (4096 characters max)';
    $valid = false;
}

if (empty($subject)) {
    $errors['subject'][] = 'Please provide a course subject';
    $valid = false;
} elseif (!in_array($subject, SUBJECTS)) {
    $errors['subject'][] = 'Please provide a valid course subject';
    $valid = false;
}

if (empty($free_raw)) {
    $free = null;
    $errors['free'][] = 'Please declare whether the course is free or paid';
    $valid = false;
} else {
    if ($free_raw === 'yes') {
        $free = 1;
        $price = 0;
    } else {
        $free = 0;
        if (empty($price) and $price != 0) {
            $errors['price'][] = 'Please provide a course price';
            $valid = false;
        } elseif (!is_numeric($price)) {
            $errors['price'][] = 'Please provide a numerical course price';
            $valid = false;
        } elseif ($price == 0) {
            $errors['price'][] = 'Please provide a non-zero course price';
            $valid = false;
        }
    }
}

if (empty($tags)) {
    $errors['tags'][] = 'Please provide the tags of the videos to be added';
    $valid = false;
}

ensure_session();
session_write_close();
if (!$_SESSION['auth']) {
    $errors['submit'][] = 'You must be logged in to create a course';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

require_once "pdo_write.php";


require_once "tag_actions.php";

$course_tag = tag_create();

try {
    $pdo_write = new_pdo_write();
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error';
    api_fail("Couldn't connect to database", $errors);
}

$sql_valid_vid = "SELECT id FROM videos v WHERE tag = :tag AND uploader = :uid";
$prep_valid_vid = prepare_write($sql_valid_vid);
foreach ($tags as $tag) {
    $data_valid_vid = [
        'tag' => $tag,
        'uid' => $_SESSION['uid']
    ];
    $prep_valid_vid->execute($data_valid_vid);
    if ($prep_valid_vid->fetch() === false) {
        $valid = false;
        $errors['tags'][] = "The video (" . htmlspecialchars($tag) . ") is invalid or not created by you";
    }
}

$sql_new_vid = "SELECT course_tag FROM db.course_videos WHERE video_tag = :tag;";
$prep_new_vid = prepare_write($sql_new_vid);
foreach ($tags as $tag) {
    $prep_new_vid->execute(['tag' => $tag]);
    if ($prep_new_vid->fetch() !== false) {
        $valid = false;
        $errors['tags'][] = "The video (" . htmlspecialchars($tag) . ") is already in a course";
    }
}

if (!$valid) {
    api_fail('Please provide corect video tags', $errors);
}
$preview_thumbnail = '/var/www/html/resources/thumbnails/' . $tags[0] . '.jpg';
$course_thumbnail = '/var/www/html/resources/thumbnails/' . $course_tag . '.jpg';
if (!copy($preview_thumbnail, $course_thumbnail)) {
    api_fail("Couldn't add thumbnail");
}

$sql_item = "INSERT INTO db.items (tag, type, price)
             VALUES (:tag, 'course', :price);";
$prep_item = prepare_write($sql_item);
$data_item = [
    'tag' => $course_tag,
    'price' => $price
];

if (!$prep_item->execute($data_item)) {
    $errors['submit'][] = 'Internal server error';
    api_fail("Couldn't create item", $errors);
}

$sql_course = "INSERT INTO db.courses (db.courses.tag, db.courses.name, db.courses.description, db.courses.subject, db.courses.creator, db.courses.free)
            VALUES (:tag, :title, :description, :subject, :uid, :free)";
$prep_course = prepare_write($sql_course);
$data_course = [
    'tag' => $course_tag,
    'title' => htmlspecialchars($title),
    'description' => str_replace(PHP_EOL, '<br>', htmlspecialchars($description)),
    'subject' => $subject,
    'uid' => $_SESSION['uid'],
    'free' => $free
];

if (!$prep_course->execute($data_course)) {
    $errors['submit'][] = 'Internal server error';
    api_fail("Couldn't create course", $errors);
}

$sql_c_vid = "INSERT INTO db.course_videos (db.course_videos.video_tag, db.course_videos.course_tag, db.course_videos.`order`)
              SELECT :v_tag, :c_tag, :index";
$prep_c_vid = prepare_write($sql_c_vid);

$index = 0;
foreach ($tags as $tag) {
    $data_c_vid = [
        'v_tag' => $tag,
        'c_tag' => $course_tag,
        'index' => $index++
    ];
    $prep_c_vid->execute($data_c_vid);
}

$sql_own = "INSERT INTO db.ownership (item_tag, user_id, origin)
            VALUES (:tag, :uid, 'owner');";
$prep_own = prepare_write($sql_own);
$data_own = [
    'tag' => $course_tag,
    'uid' => $_SESSION['uid']
];

if (!$prep_own->execute($data_own)) {
    $errors['submit'][] = 'Internal server error';
    api_fail("Couldn't grant course", $errors);
}

$message = "Course successfully created! <br> You can find your course <br> <a href='/courses/course/$course_tag'> at this link </a>";
api_succeed($message, $errors);
