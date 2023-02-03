<?php

require "api_resolve.php";
require_once "pdo_read.php";
$errors = [
    'query' => [],
    'origin' => [],
    'sort' => [],
    'submit' => []
];
$valid = true;

$query = $_POST['query'] ?? '';
$origin = $_POST['origin'] ?? '';
$sort = $_POST['sort'] ?? '';


if ($query === '') {
    $errors['query'][] = 'Please provide a query';
    $valid = false;
}

if (empty($origin)) {
    $errors['origin'][] = 'Please submit an origin class';
    $valid = false;
}

if (empty($sort)) {
    $errors['sort'][] = 'Please provide a sort mode';
    $valid = false;
} elseif (!in_array($sort, ['views', 'rating', 'recent'])) {
    $errors['sort'][] = 'Please provide a valid sort mode';
    $valid = false;
}

if (!$valid) {
    api_fail('Please fill in all fields correctly', $errors);
}

$sql = match ($sort) {
    'views' => "SELECT COALESCE(v.tag, c.tag)   as tag,
       COALESCE(v.name, c.name) as name,
       i.type,
       COALESCE(v.free, c.free) as free,
       o.id                     as owned
FROM items i
         LEFT JOIN videos v on i.tag = v.tag
         LEFT JOIN courses c on i.tag = c.tag
         LEFT JOIN ownership o on i.tag = o.item_tag and o.user_id = :uid
WHERE (v.name LIKE :query
    OR c.name LIKE :query)
  AND NOT i.restricted
ORDER BY COALESCE(v.views, c.views) DESC
LIMIT 50",

    'rating' => "
SELECT COALESCE(v.tag, c.tag)   as tag,
       COALESCE(v.name, c.name) as name,
       i.type,
       COALESCE(v.free, c.free) as free,
       o.id                     as owned,
       AVG(r.rating)
FROM items i
         LEFT JOIN videos v on i.tag = v.tag
         LEFT JOIN courses c on i.tag = c.tag
         LEFT JOIN ownership o on i.tag = o.item_tag and o.user_id = :uid
         LEFT JOIN ratings r on i.id = r.item_id
WHERE (v.name LIKE :query
    OR c.name LIKE :query)
  AND NOT i.restricted
GROUP BY COALESCE(v.tag, c.tag), COALESCE(v.name, c.name), i.type, COALESCE(v.free, c.free), o.id
ORDER BY AVG(r.rating) DESC
LIMIT 50",

    'recent' => "
SELECT COALESCE(v.tag, c.tag)   as tag,
       COALESCE(v.name, c.name) as name,
       i.type,
       COALESCE(v.free, c.free) as free,
       o.id                     as owned
FROM items i
         LEFT JOIN videos v on i.tag = v.tag
         LEFT JOIN courses c on i.tag = c.tag
         LEFT JOIN ownership o on i.tag = o.item_tag and o.user_id = :uid
WHERE (v.name LIKE :query
    OR c.name LIKE :query)
  AND NOT i.restricted
ORDER BY COALESCE(v.upload_date, c.creation_date) DESC
LIMIT 50"
};

ensure_session();
$data = [
    'query' => '%' . htmlspecialchars($query) . '%',
    'uid' => $_SESSION['uid'] ?? 0
];

try {
    $prep = prepare_readonly($sql);
    $prep->execute($data);
    $results = $prep->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    api_fail("Couldn't perform search", ['submit' => "Couldn't perform search"]);
}

require_once "searchbar.php";
$results_rendered = [];
$any_owned = false;
$any_available = false;
$any = !empty($results);
foreach ($results as $result) {
    $any_available |= $result['owned'] | $result['free'];
    $any_owned |= $result['owned'];
    $results_rendered[] = render_search_result($result, htmlspecialchars($origin));
}

$response = [
    'html' => join(PHP_EOL, $results_rendered),
    'any_owned' => $any_owned,
    'any_available' => $any_available,
    'any' => $any
];
api_succeed('Search results retrieved', data: $response);
