<?php
require_once "relative_time.php";
require_once 'tag_actions.php';

function item_id_from_tag(string $tag, PDO $PDO): int|false
{
    $sql_tag = 'SELECT id FROM db.items WHERE (tag = :tag)';
    $p_tag = $PDO->prepare($sql_tag);
    $p_tag->execute(['tag' => $tag]);
    return $p_tag->fetch(PDO::FETCH_ASSOC)['id'];
}

function get_comments_item(int $id, PDO $PDO): array
{
    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score, COALESCE(s.score, 0) as user_score, COUNT(r.id) as replies 
            FROM comments as c
                INNER JOIN users u on c.commenter_id = u.id
                LEFT JOIN scores s on c.tag = s.comment_tag and s.user_id = :uid
                LEFT JOIN comments r on c.tag = r.reply_tag
            WHERE c.item_id = :item AND c.reply_tag IS NULL
            GROUP BY c.tag, u.name, u.full_name, c.date, c.text, c.score, COALESCE(s.score, 0)
            ORDER BY c.score DESC';
    ensure_session();
    $data = [
        'item' => $id,
        'uid' => $_SESSION['uid'] ?? null
    ];

    $prep = $PDO->prepare($sql);
    $prep->execute($data);
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

function get_replies_comment(string $tag, PDO $PDO): array
{
    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score, COALESCE(s.score, 0) as user_score, COUNT(r.id) as replies 
            FROM comments as c
                INNER JOIN users u on c.commenter_id = u.id
                LEFT JOIN scores s on c.tag = s.comment_tag and s.user_id = :uid
                LEFT JOIN comments r on c.tag = r.reply_tag
            WHERE c.reply_tag = :tag
            GROUP BY c.tag, u.name, u.full_name, c.date, c.text, c.score, COALESCE(s.score, 0)
            ORDER BY c.score DESC';
    ensure_session();
    $data = [
        'tag' => $tag,
        'uid' => $_SESSION['uid'] ?? null
    ];

    $prep = $PDO->prepare($sql);
    $prep->execute($data);
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

function render_comment(array $comment, bool $is_reply = false): string
{
    $rating_class_down = '';
    $rating_class_up = '';

    if ($comment['user_score'] === 1) {
        $rating_class_up = 'pressed';
    } else if ($comment['user_score'] === -1) {
        $rating_class_down = 'pressed';
    }

    $ago = time_since($comment['date']);
    $class = $is_reply ? 'comment-top' : 'comment-reply';
    $replies = render_show_replies($comment['replies'], $comment['tag']);
    $new_reply = render_comment_form($comment['tag'], true);
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
        <div class='comment-reactions-wrapper' data-tag='{$comment['tag']}'>
            <div class='comment-reactions-gap-s'></div><span class='comment-reactions-up material-symbols-outlined $rating_class_up'>thumb_up</span><div class='comment-reactions-gap-s'></div><span class='comment-reactions-down material-symbols-outlined $rating_class_down'>thumb_down</span><div class='comment-reactions-gap-l'></div><div class='comment-reactions-reply-box'><span class='comment-reactions-reply material-symbols-outlined'>reply</span></div>
        </div>
    </div>
    <div class='comment-new-reply-wrapper' id='new-reply-{$comment['tag']}' style='max-height: 0;'>
        $new_reply
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
    } else {

        $sql_update = 'UPDATE db.scores SET score = :new_rating WHERE (user_id = :user) AND (comment_tag = :comment)';
        $sth_update = $pdo_write->prepare($sql_update);
        $sth_update->execute(['new_rating' => $rating, 'user' => $user_id, 'comment' => $comment_id]);
    }
}

function add_comment(string $comment_text, string $item_tag, $reply_tag = null): string|false
{
    require_once 'pdo_write.php';
    require_once 'pdo_read.php';

    $uid = $_SESSION['uid'];
    $comment_tag = tag_create();
    $pdo_write = new_pdo_write();


    $sql_id = 'SELECT id FROM db.items WHERE (tag = :tag)';
    $prep_id = $pdo_write->prepare($sql_id);
    $prep_id->execute(['tag' => $item_tag]);

    $item = $prep_id->fetch();

    if ($item === false) {
        return false;
    }

    $item_id = $item['id'];

    $sql_comment = 'INSERT INTO db.comments (tag, commenter_id, item_id, text, date, reply_tag, score) 
            VALUES (:tag, :uid, :video_id, :comment, DEFAULT, :reply, DEFAULT)';
    $prep_comment = $pdo_write->prepare($sql_comment);
    $data_comment = [
        'tag' => $comment_tag,
        'uid' => $uid,
        'video_id' => $item_id,
        'comment' => htmlspecialchars($comment_text),
        'reply' => $reply_tag
    ];

    if (!$prep_comment->execute($data_comment)) {
        return false;
    }

    return $comment_tag;
}

function get_comment_id($comment_tag): int
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT id FROM db.comments WHERE tag = :comment_tag';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['comment_tag' => $comment_tag]);

    return $sth->fetch()['id'];
}

function get_comment_info($comment_id, int $replies): array
{
    require_once 'pdo_read.php';

    $pdo_read = new_pdo_read();

    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score FROM comments as c
            INNER JOIN users u on c.commenter_id = u.id
            WHERE c.id = :comment';
    $sth = $pdo_read->prepare($sql);
    $sth->execute(['comment' => $comment_id]);

    $info = $sth->fetch(PDO::FETCH_ASSOC);
    $info['replies'] = $replies;
    $info['user_score'] = 0;

    return $info;
}

function render_comment_form(string $tag, bool $reply): string
{
    require_once 'pdo_read.php';

    if ($_SESSION['auth']) {
        $uid = $_SESSION['uid'];

        $pdo_read = new_pdo_read();
        $sql = 'SELECT name FROM db.users WHERE id = :uid';
        $sth = $pdo_read->prepare($sql);
        $sth->execute(['uid' => $uid]);

        $name = $sth->fetch()['name'];
        $head = "<div class='head'>
                <span class='comment-username'> $name </span>
                <span class='head-space'></span>
            </div>";
        $wrapper_class = $reply ? '' : 'comment-new-top';
        $reply = $reply ? 'yes' : 'no';
    } else {
        $head = "<span class='headless-label'> Add comment: </span>";
        $wrapper_class = $reply ? '' : 'comment-new-top-headless';
        $reply = $reply ? 'yes' : 'no';
    }
    $auth = $_SESSION['auth'] ? 'yes' : 'no';

    return "
        <div class='comment-wrapper comment $wrapper_class'>
            $head
            <div class='form-wrapper'>
                <form class='new-comment' action='/api/courses/add_comment.php' method='POST' data-tag='$tag'>
                    <div id='comment-$tag-group' class='form-group form-group-comment'>
                        <label for='comment-$tag-text'></label>
                        <textarea
                                id='comment-$tag-text'
                                name='comment-text'
                                placeholder='Write a comment...'
                                data-auth='$auth'></textarea>
                        <span id='comment-text-error' class='form-error'> No error </span>
                    </div>
                    <div class='comment-button-wrapper'>
                        <button class='comment-button' data-auth='$auth' data-reply='$reply'> Post </button>
                    </div>
                </form>
            </div>
        </div>
        ";
}
