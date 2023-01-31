<?php

require "api_resolve.php";
require_once "pdo_read.php";
$errors = [
    'query' => [],
    'origin' => [],
    'submit' => []
];
$valid = true;

$query = $_POST['query'] ?? '';
$origin = $_POST['origin'] ?? '';


if ($query === '') {
    $errors['query'][] = 'Please provide a query';
    $valid = false;
}

if (empty($origin)) {
    $errors['origin'][] = 'Please submit an origin class';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

$sql = 'SELECT COALESCE(v.tag, c.tag) as tag, COALESCE(v.name, c.name) as name, i.type
        FROM items i
        LEFT JOIN videos v on i.tag = v.tag
        LEFT JOIN courses c on i.tag = c.tag
        WHERE MATCH(v.name) AGAINST(:query) OR MATCH(c.name) AGAINST(:query)';
$data = [
    'query' => htmlspecialchars($query),
];

try {
    $pdo_read = new_pdo_read();
    $prep = $pdo_read->prepare($sql);
    $prep->execute($data);
    $results = $prep->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    api_fail("Couldn't perform search", ['submit' => "Couldn't perform search"]);
}
require_once "searchbar.php";
$results_rendered = [];
foreach ($results as $result) {
    $results_rendered[] = render_search_result($result, htmlspecialchars($origin));
}

$response = ['html' => join(PHP_EOL, $results_rendered)];
api_succeed('Search results retrieved', data: $response);
