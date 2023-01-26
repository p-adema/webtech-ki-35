<?php

function best_videos_genre($genre): array
{
    require_once 'pdo_read.php';

    $sql = 'SELECT id FROM db.videos WHERE subject = :genre ORDER BY '
}