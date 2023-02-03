<?php
require "html_page.php";
require_once "tag_actions.php";

$errors = [
    'title' => [],
    'description' => [],
    'subject' => [],
    'free' => [],
    'price' => [],
    'video' => [],
    'thumbnail' => [],
    'submit' => []
];
$valid = true;

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$subject = $_POST['subject'] ?? '';
$free_raw = $_POST['free'] ?? '';
$price = $_POST['price'] ?? '';

if (empty($title)) {
    $errors['title'][] = 'Please provide a video title';
    $valid = false;
} elseif (strlen(htmlspecialchars($title)) > 100) {
    $errors['title'][] = 'Please provide a shorter video title (100 characters max)';
    $valid = false;
}

if (empty($description)) {
    $errors['description'][] = 'Please provide a video description';
    $valid = false;
} elseif (strlen(htmlspecialchars($description)) > 256) {
    $errors['description'][] = 'Please provide a shorter video description (256 characters max)';
    $valid = false;
}

if (empty($subject)) {
    $errors['subject'][] = 'Please provide a video subject';
    $valid = false;
} elseif (!in_array($subject, SUBJECTS)) {
    $errors['subject'][] = 'Please provide a valid video subject';
    $valid = false;
}

if (empty($free_raw)) {
    $free = null;
    $errors['free'][] = 'Please declare whether the video is free or paid';
    $valid = false;
} else {
    if ($free_raw === 'yes') {
        $free = 1;
        $price = 0;
    } else {
        $free = 0;
        if (empty($price)) {
            $errors['price'][] = 'Please provide a video price';
            $valid = false;
        } elseif (!is_numeric($price)) {
            $errors['price'][] = 'Please provide a numerical video price';
            $valid = false;
        }
    }
}
ensure_session();
session_write_close();
if (!$_SESSION['auth']) {
    $errors['submit'][] = 'You must be logged in to upload a video';
    $valid = false;
}

if (!isset($_FILES['video'])) {
    $errors['video'][] = 'Please submit a video';
    $valid = false;
}

if (!isset($_FILES['thumbnail'])) {
    $errors['thumbnail'][] = 'Please submit a thumbnail';
    $valid = false;
}

if (!$valid) {
    api_fail('Please properly fill in all fields', $errors);
}

$video_tmp = $_FILES["video"]["tmp_name"];
$thumbnail_tmp = $_FILES["thumbnail"]["tmp_name"];

if (mime_content_type($video_tmp) !== 'video/mp4') {
    $errors['video'][] = 'Please submit a mp4 video';
    $valid = false;
} elseif ($_FILES["video"]["size"] > 1e8) {
    $errors['video'][] = 'Please submit a smaller video';
    $valid = false;
}

if (mime_content_type($thumbnail_tmp) !== 'image/jpeg') {
    $errors['thumbnail'][] = 'Please submit a jpg thumbnail';
    $valid = false;
} elseif ($_FILES["thumbnail"]["size"] > 1e5) {
    $errors['thumbnail'][] = 'Please submit a smaller thumbnail';
    $valid = false;
}

if (!$valid) {
    api_fail('Please submit valid files', $errors);
}

$item_tag = tag_create();
$video_target = $_SERVER['DOCUMENT_ROOT'] . '/resources/videos/' . $item_tag . '.mp4';
$thumbnail_target = $_SERVER['DOCUMENT_ROOT'] . '/resources/thumbnails/' . $item_tag . '.jpg';

if (!move_uploaded_file($video_tmp, $video_target)) {
    $errors['submit'][] = 'Internal file error (video)';
    $valid = false;
} elseif (!move_uploaded_file($thumbnail_tmp, $thumbnail_target)) {
    unlink($video_target);
    $errors['submit'][] = 'Internal file error (thumbnail)';
    $valid = false;
}

if (!$valid) {
    api_fail('Internal file error', $errors);
}

require_once "pdo_write.php";

try {
    $pdo_write = new_pdo_write();
} catch (PDOException $e) {
    $errors['submit'][] = 'Internal server error';
    unlink($video_target);
    unlink($thumbnail_target);
    api_fail("Couldn't connect to database", $errors);
}
$sql_item = "INSERT INTO db.items (tag, type, price)
             VALUES (:tag, 'video', :price);";
$prep_item = $pdo_write->prepare($sql_item);
$data_item = [
    'tag' => $item_tag,
    'price' => $price
];

if (!$prep_item->execute($data_item)) {
    $errors['submit'][] = 'Internal server error';
    unlink($video_target);
    unlink($thumbnail_target);
    api_fail("Couldn't add item", $errors);
}

$sql_vid = "INSERT INTO db.videos (tag, name, description, subject, uploader, free)
            VALUES (:tag, :title, :description, :subject, :uid, :free)";
$prep_vid = $pdo_write->prepare($sql_vid);
$data_vid = [
    'tag' => $item_tag,
    'title' => htmlspecialchars($title),
    'description' => htmlspecialchars($description),
    'subject' => $subject,
    'uid' => $_SESSION['uid'],
    'free' => $free
];

if (!$prep_vid->execute($data_vid)) {
    $errors['submit'][] = 'Internal server error';
    unlink($video_target);
    unlink($thumbnail_target);
    api_fail("Couldn't add video", $errors);
}

$sql_own = "INSERT INTO db.ownership (item_tag, user_id, origin)
            VALUES (:tag, :uid, 'owner');";
$prep_own = $pdo_write->prepare($sql_own);
$data_own = [
    'tag' => $item_tag,
    'uid' => $_SESSION['uid']
];

if (!$prep_own->execute($data_own)) {
    $errors['submit'][] = 'Internal server error';
    unlink($video_target);
    unlink($thumbnail_target);
    api_fail("Couldn't grant video", $errors);
}

$message = "Video successfully added! <br> You can find your video <br> <a href='/courses/video/$item_tag'> at this link </a>";
api_succeed($message, $errors);
