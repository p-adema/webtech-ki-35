<?php

function calculate_rating($item_id): void
{
    require_once 'pdo_read.php';
    require_once 'pdo_write.php';

    $pdo_read = new_pdo_read();
    $pdo_write = new_pdo_write();

    $sql_read = 'SELECT rating FROM db.ratings WHERE item_id = :item_id';
    $sth_read = $pdo_read->prepare($sql_read);
    $sth_read->execute(['item_id' => $item_id]);

    $all_ratings = $sth_read->fetchAll(PDO::FETCH_ASSOC);
    $amount_of_ratings = count($all_ratings);
    $total_score = 0;

    foreach ($all_ratings as $rating) {
        $total_score += $rating['rating'];
    }

    $final_score = round(($total_score / $amount_of_ratings), 2);

    $sql_update = 'UPDATE db.items SET rating = :new_rating WHERE id = :item';
    $sth_update = $pdo_write->prepare($sql_update);
    $sth_update->execute(['new_rating' => $final_score, 'item' => $item_id]);
}

function best_videos_of_genre($genre): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT items.tag FROM items INNER JOIN videos ON items.tag = videos.tag WHERE videos.subject = :genre ORDER BY items.rating DESC';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['genre' => $genre]);

    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function get_popular_video(): string
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $sql = 'SELECT tag FROM db.items WHERE type = :video ORDER BY rating DESC';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['video' => 'video']);
    return $sth->fetch()['tag'];
}

function get_popular_course($subject): array|false
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT items.tag FROM items INNER JOIN courses ON items.tag = courses.tag WHERE courses.subject = :genre ORDER BY items.rating DESC';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['genre' => $subject]);

    return $sth->fetch();
}