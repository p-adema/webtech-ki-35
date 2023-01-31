<?php

require 'api_resolve.php';
require 'video_functionality.php';

ensure_session();

$errors = [
    'tag' => []
    ];

$check = false;
$valid = true;

$tag = $_POST['tag'] ?? '';

if (empty($tag)) {
    $valid = false;
    $errors['tag'][] = 'Please provide a valid tag';
}

else {
    $pdo_read = new_pdo_read();

    $sql = 'SELECT name FROM db.videos WHERE (tag = :video_tag)';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['tag' => $tag]);

    $check = $sth->fetch();

    if (!$check) {
        $valid = false;
        $errors['tag'][] = 'Please provide a valid tag';
    }

}

if (!$valid) {
    api_fail('Please use a correct and working link', $errors);
}

else {
    api_succeed('This tag is in use!');
}
