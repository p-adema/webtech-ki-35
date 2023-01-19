<?php

function get_id(string $tag, PDO $PDO): int|false
{
    $sql_tag = 'SELECT id FROM db.items WHERE (tag = :tag)';
    $p_tag = $PDO->prepare($sql_tag);
    $p_tag->execute(['tag' => $tag]);
    return $p_tag->fetch(PDO::FETCH_ASSOC);
}

function get_comments_item(int $id, PDO $PDO): array
{
    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score FROM comments as c
            INNER JOIN users u on c.commenter_id = u.id
            WHERE c.item_id = :item AND c.reply_tag IS NULL';
    $prepared = $PDO->prepare($sql);
    $prepared->execute(['item' => $id]);
    return $prepared->fetchAll(PDO::FETCH_ASSOC);
}

function render_comment(array $comment): string
{
    return "
<div class='comment-wrapper comment-top' id='{$comment['tag']}'>
    <div class='head'>
        <span class='comment-username'> {$comment['name']} </span>
        <span class='comment-date'> {$comment['date']} </span>
    </div>
    <div class='comment-text-wrapper'>
        <span class='comment-text'> {$comment['text']} </span>
</div>
</div>
    ";
}

function render_show_replies(): string
{
    return "
<div class='toggle-replies-wrapper'>
    <button class='show-replies'> Show replies </button>
</div>
    ";
}

function render_hide_replies(): string
{
    return "
<div class='toggle-replies-wrapper'>
    <button class='show-replies'> Hide replies </button>
</div>
    ";
}

function render_show_more(): string
{
    return "
<div class='show-more-wrapper'>
    <button class='show-more'> Show replies </button>
</div>
    ";
}
