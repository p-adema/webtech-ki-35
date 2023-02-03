<?php
require_once "pdo_read.php";

function get_owned_videos(string $query): array
{
    ensure_session();
    $sql = 'SELECT v.tag, v.name, u.name as uploader
            FROM videos v
                INNER JOIN ownership o on v.tag = o.item_tag and o.user_id = :uid
                LEFT JOIN purchases p on o.purchase_id = p.id
                LEFT JOIN db.gifts g on o.gift_id = o.id
                INNER JOIN users u on v.uploader = u.id
            WHERE v.name LIKE :query
            ORDER BY COALESCE(p.confirmation_time, g.confirmation_time) DESC
            LIMIT 50';
    $prep = prepare_readonly($sql);
    $data = [
        'uid' => $_SESSION['uid'] ?? '',
        'query' => '%' . htmlspecialchars($query) . '%'
    ];
    $prep->execute($data);
    return $prep->fetchAll();
}


function render_owned_videos(array $videos): string
{
    $rendered_videos = [];
    foreach ($videos as $video) {

        $rendered_videos[] = "
<div class='video-wrapper'>
    <div class='video-outline'>
        <a href='/courses/video/{$video['tag']}'>
            <img class='thumbnail'
            src='/resources/thumbnails/{$video['tag']}.jpg'
            alt='Your browser does not support this image type.'>
        </a>
        <div class='video-info'>
            <span>{$video['name']}</span>
            <span class='video-creator'>by {$video['uploader']}</span>
        </div>
    </div>
</div>
    ";
    }
    return join(PHP_EOL, $rendered_videos);
}

function display_no_videos(): void
{
    echo "<span class='no-videos'> You have no videos </span>";
}
