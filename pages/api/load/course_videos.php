<?php
require "api_resolve.php";
require_once "pdo_read.php";
$errors = [
    'query' => [],
    'added' => [],
    'submit' => []
];
$valid = true;

$query = $_POST['query'] ?? '';

if (empty($query)) {
    $errors['query'][] = 'Please provide a query';
    $valid = false;
}

$added = $_POST['added'] ?? [];

if (!is_array($added)) {
    $errors['added'][] = 'Please provide added videos as a list';
    $valid = false;
}

ensure_session();

if (!$_SESSION['auth']) {
    $errors['submit'][] = 'Please log in first to create a course';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

$sql = 'SELECT `name`, `tag` FROM db.videos
        WHERE MATCH(`name`) AGAINST(:query) AND uploader = :uid ORDER BY views DESC';
$data = [
    'query' => htmlspecialchars($query),
    'uid' => $_SESSION['uid']
];

try {
    $pdo_read = new_pdo_read();
    $prep = $pdo_read->prepare($sql);
    $prep->execute($data);
    $videos = $prep->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    api_fail("Couldn't perform search", ['submit' => "Couldn't perform search"]);
}
require_once "form_elements.php";
$videos_rendered = [];
$add_icon = "<span class='sortable-icon query-result material-symbols-outlined'> add </span>";
foreach ($videos as $video) {
    if (!in_array($video['tag'], $added)) {
        $videos_rendered[] = form_sortable_item('query', 0, $video, 'query-result', $add_icon, false, false);
    }
}

$response = ['html' => join(PHP_EOL, $videos_rendered)];
api_succeed('Videos retrieved', data: $response);
