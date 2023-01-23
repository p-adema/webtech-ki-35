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

function get_replies_comment(string $tag, PDO $PDO): array
{
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

function render_comment(array $comment, int|null $score,  bool $is_reply = false): string
{
    $rating_class_down = '';
    $rating_class_up = '';

    if ($score === 1) {
        $rating_class_up = 'pressed';
    }
    else if ($score === -1) {
        $rating_class_down = 'pressed';
    }

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
        <div class='comment-reactions-wrapper' tag='{$comment['tag']}'>
            <div class='comment-reactions-gap-s'></div><span class='comment-reactions-up material-symbols-outlined $rating_class_up'>thumb_up</span><div class='comment-reactions-gap-s'></div><span class='comment-reactions-down material-symbols-outlined $rating_class_down'>thumb_down</span><div class='comment-reactions-gap-l'></div><span class='comment-reactions-reply material-symbols-outlined'>reply</span>
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
    <button class='show-replies' query='$tag' count='$count $word'> Show $count $word </button>
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

function change_comment_score($rating, $comment_id, $user_id): void
{
    require_once 'pdo_write.php';
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();
    $pdo_write = new_pdo_write();

    $sql_read = 'SELECT score FROM db.scores WHERE user_id = :user AND comment_tag = :comment';
    $sth_read = $pdo_read->prepare($sql_read);
    $sth_read->execute(['user' => $user_id, 'comment' => $comment_id]);

    $score_content = $sth_read->fetch()['score'];

    if (empty($score_content)) {

        $sql_new = 'INSERT INTO db.scores (user_id, comment_tag, score) VALUES (:user, :comment, :rating)';
        $sth_new = $pdo_write->prepare($sql_new);
        $sth_new->execute(['user' => $user_id, 'comment' => $comment_id, 'rating' => $rating]);
    }

    else {

        $sql_update = 'UPDATE db.scores SET score = :new_rating WHERE (user_id = :user) AND (comment_tag = :comment)';
        $sth_update = $pdo_write->prepare($sql_update);
        $sth_update->execute(['new_rating' => $rating, 'user' => $user_id, 'comment' => $comment_id]);
    }
}

function get_main_votes($item_id): array
{
    require_once 'pdo_read.php';

    ensure_session();

    $uid = $_SESSION['uid'];
    $pdo_read = new_pdo_read();

    $video_comments_sql = 'SELECT tag FROM db.comments WHERE item_id = :item_id AND reply_tag IS NULL';
    $video_comments_sth = $pdo_read->prepare($video_comments_sql);
    $video_comments_sth->execute(['item_id' => $item_id]);

    $data = $video_comments_sth->fetchAll(PDO::FETCH_DEFAULT);

    $user_votes_sql = 'SELECT score FROM db.scores WHERE (user_id = :user) AND (comment_tag = :comment)';
    $user_votes_sth = $pdo_read->prepare($user_votes_sql);

    $votes_array = array();

    foreach ($data as $item) {
        $user_votes_sth->execute(['user' => $uid, 'comment' => $item['tag']]);
        $comment_score = $user_votes_sth->fetch();
        if ($comment_score !== false) {
            $votes_array[$item['tag']] = $comment_score['score'];
        }
        else {
            $votes_array[$item['tag']] = 0;
        }
    }

    return $votes_array;
}

function get_reaction_votes($comment_id): array
{
    require_once 'pdo_read.php';

    ensure_session();

    $uid = $_SESSION['uid'];
    $pdo_read = new_pdo_read();

    $comment_comments_sql = 'SELECT tag FROM db.comments WHERE reply_tag = :comment_id';
    $comment_comments_sth = $pdo_read->prepare($comment_comments_sql);
    $comment_comments_sth->execute(['comment_id' => $comment_id]);

    $data = $comment_comments_sth->fetchAll(PDO::FETCH_DEFAULT);

    $user_votes_sql = 'SELECT score FROM db.scores WHERE (user_id = :user) AND (comment_tag = :comment)';
    $user_votes_sth = $pdo_read->prepare($user_votes_sql);

    $votes_array = array();

    foreach ($data as $item) {
        $user_votes_sth->execute(['user' => $uid, 'comment' => $item['tag']]);
        $comment_score = $user_votes_sth->fetch();
        if ($comment_score !== false) {
            $votes_array[$item['tag']] = $comment_score['score'];
        }
        else {
            $votes_array[$item['tag']] = 0;
        }
    }

    return $votes_array;
}