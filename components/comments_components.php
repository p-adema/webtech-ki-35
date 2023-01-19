<?php
require_once "relative_time.php";

function get_id(string $tag, PDO $PDO): int|false
{
    $sql_tag = 'SELECT id FROM db.items WHERE (tag = :tag)';
    $p_tag = $PDO->prepare($sql_tag);
    $p_tag->execute(['tag' => $tag]);
    return $p_tag->fetch(PDO::FETCH_ASSOC)['id'];
}

function count_replies(string $tag, PDO $PDO): bool
{
    $sql = 'SELECT COUNT(*) AS replies FROM comments WHERE reply_tag = :tag';
    $prepared = $PDO->prepare($sql);
    $prepared->execute(['tag' => $tag]);
    return $prepared->fetch(PDO::FETCH_ASSOC)['replies'];
}

function get_comments_item(int $id, PDO $PDO): array
{
    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score FROM comments as c
            INNER JOIN users u on c.commenter_id = u.id
            WHERE c.item_id = :item AND c.reply_tag IS NULL';
    $prepared = $PDO->prepare($sql);
    $prepared->execute(['item' => $id]);
    $comments = $prepared->fetchAll(PDO::FETCH_ASSOC);
    foreach ($comments as &$comment) {
        $comment['replies'] = count_replies($comment['tag'], $PDO);
    }
    return $comments;
}

function get_replies_comment(string $tag, PDO $PDO): array {
    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score FROM comments as c
            INNER JOIN users u on c.commenter_id = u.id
            WHERE c.reply_tag = :tag';

    $prepared = $PDO->prepare($sql);
    $prepared->execute(['tag' => $tag]);
    $comments = $prepared->fetchAll(PDO::FETCH_ASSOC);
    foreach ($comments as &$comment) {
        $comment['replies'] = count_replies($comment['tag'], $PDO);
    }
    return $comments;
}

function render_comment(array $comment, bool $is_reply = false): string
{
    $ago = time_since($comment['date']);
    $class = $is_reply ? 'comment-top' : 'comment-reply';
    $replies = render_show_replies($comment['replies'], $comment['tag']);
    return "
<div class='comment-wrapper' id='{$comment['tag']}'>
    <div class='comment $class'>
        <div class='head'>
            <span class='comment-username'> {$comment['name']} </span>
            <span class='head-space'></span>
            <span class='comment-date'> $ago ago </span>
        </div>
        <div class='comment-text-wrapper'>
            <span class='comment-text'> {$comment['text']} </span>
        </div>
    </div>
    $replies
</div>
<div class='comment-replies-wrapper' id='replies-{$comment['tag']}'></div>
    ";
}

function render_show_replies($count, $tag): string
{
    $word = $count > 1 ? 'replies' : 'reply';
    return $count > 0 ? "
<div class='toggle-replies-wrapper'>
    <button class='show-replies' query='$tag'> Show $count $word </button>
</div>
    " : '';
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
