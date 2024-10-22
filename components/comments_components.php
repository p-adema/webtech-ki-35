<?php
require_once "relative_time.php";
require_once 'tag_actions.php';

/**
 * @param string $item_tag items.tag
 * @return int|false items.id
 */
function item_id_given_tag(string $item_tag): int|false
{
    $sql_tag = 'SELECT id FROM db.items WHERE (tag = :tag)';
    $p_tag = prepare_readonly($sql_tag);
    $p_tag->execute(['tag' => $item_tag]);
    return $p_tag->fetch(PDO::FETCH_ASSOC)['id'];
}

/**
 * @param int $item_id items.id
 * @return array c.tag, u.name, u.full_name, c.date, c.text, c.score, user_score, replies, c.hidden
 */
function get_comments_item(int $item_id): array
{
    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score, COALESCE(s.score, 0) as user_score, COUNT(r.id) as replies, c.hidden
            FROM comments as c
                INNER JOIN users u on c.commenter_id = u.id
                LEFT JOIN scores s on c.tag = s.comment_tag and s.user_id = :uid
                LEFT JOIN comments r on c.tag = r.reply_tag
            WHERE c.item_id = :item AND c.reply_tag IS NULL
            GROUP BY c.tag, u.name, u.full_name, c.date, c.text, c.score, COALESCE(s.score, 0)
            ORDER BY c.score DESC';
    ensure_session();
    $data = [
        'item' => $item_id,
        'uid' => $_SESSION['uid'] ?? null
    ];

    $prep = prepare_readonly($sql);
    $prep->execute($data);
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param string $comment_tag comments.tag
 * @return array c.tag, u.name, u.full_name, c.date, c.text, c.score, user_score, replies, c.hidden
 */
function get_replies_comment(string $comment_tag): array
{
    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score, COALESCE(s.score, 0) as user_score, COUNT(r.id) as replies , c.hidden
            FROM comments as c
                INNER JOIN users u on c.commenter_id = u.id
                LEFT JOIN scores s on c.tag = s.comment_tag and s.user_id = :uid
                LEFT JOIN comments r on c.tag = r.reply_tag
            WHERE c.reply_tag = :tag
            GROUP BY c.tag, u.name, u.full_name, c.date, c.text, c.score, COALESCE(s.score, 0)
            ORDER BY c.score DESC';
    ensure_session();
    $data = [
        'tag' => $comment_tag,
        'uid' => $_SESSION['uid'] ?? null
    ];

    $prep = prepare_readonly($sql);
    $prep->execute($data);
    return $prep->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Renders a comment for further use
 * @param array $comment Comment information array
 * @param bool $is_reply Whether the comment is a reply or not
 * @return string Comment HTML
 */
function render_comment(array $comment, bool $is_reply = false): string
{
    $rating_class_down = '';
    $rating_class_up = '';

    if ($comment['user_score'] === 1) {
        $rating_class_up = 'pressed';
    } else if ($comment['user_score'] === -1) {
        $rating_class_down = 'pressed';
    }

    $ago = relative_time($comment['date']);
    $class = $is_reply ? 'comment-top' : 'comment-reply';
    $replies = render_show_replies($comment['replies'], $comment['tag']);
    $new_reply = render_add_comment_form($comment['tag'], true);
    if ($_SESSION['admin']) {
        $text = $comment['text'];
        $admin_icon = $comment['hidden'] ? 'visibility_off' : 'visibility';
        $admin_class = $comment['hidden'] ? 'hidden' : '';
        $admin_hide = "<span class='comment-admin-hide material-symbols-outlined' data-tag='{$comment['tag']}'>$admin_icon</span>";
    } else {
        $admin_class = '';
        $text = $comment['hidden'] ? "<span class='comment-text-hidden'> This comment has been hidden by an administrator </span>" : $comment['text'];
        $admin_hide = '';
    }
    return "
<div class='comment-wrapper' id='{$comment['tag']}'>
    <div class='comment $class $admin_class'>
        <div class='head'>
            <span class='comment-username'> {$comment['name']} </span>
            <span class='head-space'></span>
            <span class='comment-date'> $ago </span>
            $admin_hide
        </div>
        <div class='comment-text-wrapper'>
            <span class='comment-text'> $text </span>
        </div>
        <div class='comment-reactions-wrapper' data-tag='{$comment['tag']}'>
            <div class='comment-reactions-gap-s'></div><span class='comment-reactions-up material-symbols-outlined $rating_class_up'>thumb_up</span><div class='comment-reactions-gap-s'></div><span class='comment-reactions-down material-symbols-outlined $rating_class_down'>thumb_down</span><div class='comment-reactions-gap-l'></div><div class='comment-reactions-reply-box'><span class='comment-reactions-reply material-symbols-outlined'>reply</span></div>
        </div>
    </div>
    <div class='comment-new-reply-wrapper' id='new-reply-{$comment['tag']}' style='max-height: 0;'>
        $new_reply
    </div>
    <div class='new-reply-slot' id='new-reply-slot-{$comment['tag']}'></div>
    $replies
</div>
<div class='comment-replies-wrapper' id='replies-{$comment['tag']}' style='max-height: 0'></div>
    ";
}

/**
 * Renders a show replies button
 * @param int $count How many replies there are
 * @param string $tag The comment tag that replies should be loaded from
 * @return string HTML of the button
 */
function render_show_replies(int $count, string $tag): string
{
    $word = $count > 1 ? 'replies' : 'reply';
    return $count > 0 ? "
<div class='toggle-replies-wrapper'>
    <button class='show-replies' data-query='$tag' count='$count $word'> Show $count $word </button>
</div>
    " : '';
}

/**
 * @param int $score Comment score, either -1, 0 or 1
 * @param string $comment_tag tag of the comment to be scored
 * @param int $user_id User that is scoring
 */
function change_comment_score(int $score, string $comment_tag, int $user_id): void
{
    require_once 'pdo_write.php';
    require_once 'pdo_read.php';

    $sql_read = 'SELECT score FROM db.scores WHERE user_id = :user AND comment_tag = :comment';
    $sth_read = prepare_readonly($sql_read);
    $sth_read->execute(['user' => $user_id, 'comment' => $comment_tag]);

    $score_content = $sth_read->fetch()['score'];

    if (empty($score_content)) {

        $sql_new = 'INSERT INTO db.scores (user_id, comment_tag, score) VALUES (:user, :comment, :rating)';
        $sth_new = prepare_write($sql_new);
        $sth_new->execute(['user' => $user_id, 'comment' => $comment_tag, 'rating' => $score]);
    } else {

        $sql_update = 'UPDATE db.scores SET score = :new_rating WHERE (user_id = :user) AND (comment_tag = :comment)';
        $sth_update = prepare_write($sql_update);
        $sth_update->execute(['new_rating' => $score, 'user' => $user_id, 'comment' => $comment_tag]);
    }
}

/**
 * Add a comment
 * @param string $comment_text Body of the comment
 * @param string $item_tag Item tag of the video the comment should be posted on
 * @param string|null $reply_tag Optional: comment tag of the comment to be replied to
 * @return string|false On success, the tag of the newly created comment. On error, false
 */
function add_comment(string $comment_text, string $item_tag, ?string $reply_tag = null): string|false
{
    require_once 'pdo_write.php';
    require_once 'pdo_read.php';

    $uid = $_SESSION['uid'];
    $comment_tag = tag_create();

    $sql_id = 'SELECT id FROM db.items WHERE (tag = :tag)';
    $prep_id = prepare_write($sql_id);
    $prep_id->execute(['tag' => $item_tag]);

    $item = $prep_id->fetch();

    if ($item === false) {
        return false;
    }

    $item_id = $item['id'];

    $sql_comment = 'INSERT INTO db.comments (tag, commenter_id, item_id, text, date, reply_tag, score) 
            VALUES (:tag, :uid, :video_id, :comment, DEFAULT, :reply, DEFAULT)';
    $prep_comment = prepare_write($sql_comment);
    $data_comment = [
        'tag' => $comment_tag,
        'uid' => $uid,
        'video_id' => $item_id,
        'comment' => str_replace(PHP_EOL, '<br>', htmlspecialchars($comment_text)),
        'reply' => $reply_tag
    ];

    if (!$prep_comment->execute($data_comment)) {
        return false;
    }

    return $comment_tag;
}

/**
 * @param string $comment_tag comments.tag
 * @return int comments.id
 */
function comment_id_from_tag(string $comment_tag): int
{
    require_once 'pdo_read.php';


    $sql = 'SELECT id FROM db.comments WHERE tag = :comment_tag';
    $sth = prepare_readonly($sql);
    $sth->execute(['comment_tag' => $comment_tag]);

    return $sth->fetch()['id'];
}

/**
 * Creates a full infomration array for a comment
 * @param int $comment_id comments.id
 * @param int $replies The number of replies (added to the array)
 * @return array c.tag, u.name, u.full_name, c.date, c.text, c.score, c.hidden, replies
 */
function get_comment_info(int $comment_id, int $replies): array
{
    require_once 'pdo_read.php';


    $sql = 'SELECT c.tag, u.name, u.full_name, c.date, c.text, c.score, c.hidden FROM comments as c
            INNER JOIN users u on c.commenter_id = u.id
            WHERE c.id = :comment';
    $sth = prepare_readonly($sql);
    $sth->execute(['comment' => $comment_id]);

    $info = $sth->fetch(PDO::FETCH_ASSOC);
    $info['replies'] = $replies;
    $info['user_score'] = 0;

    return $info;
}

/**
 * Renders a form to add a new comment for further use
 * @param string $comment_tag comments.tag
 * @param bool $is_reply Whether this form represents a direct comment, or a reply
 * @return string Form HTML
 */
function render_add_comment_form(string $comment_tag, bool $is_reply): string
{
    require_once 'pdo_read.php';

    if ($_SESSION['auth']) {
        $uid = $_SESSION['uid'];

        $sql = 'SELECT name FROM db.users WHERE id = :uid';
        $sth = prepare_readonly($sql);
        $sth->execute(['uid' => $uid]);

        $name = $sth->fetch()['name'];
        $head = "<div class='head'>
                <span class='comment-username'> $name </span>
                <span class='head-space'></span>
            </div>";
        $wrapper_class = $is_reply ? '' : 'comment-new-top';
    } else {
        $head = "<span class='headless-label'> Add comment: </span>";
        $wrapper_class = $is_reply ? '' : 'comment-new-top-headless';
    }
    $placeholder = $is_reply ? 'Write a reply...' : 'Write a comment...';
    $is_reply = $is_reply ? 'yes' : 'no';
    $auth = $_SESSION['auth'] ? 'yes' : 'no';

    return "
        <div class='comment-wrapper comment $wrapper_class'>
            $head
            <div class='form-wrapper'>
                <form class='new-comment' data-tag='$comment_tag' data-reply='$is_reply'>
                    <div id='comment-$comment_tag-group' class='form-group form-group-comment'>
                        <label for='comment-$comment_tag-text'></label>
                        <textarea
                                id='comment-$comment_tag-text'
                                name='comment-text'
                                placeholder='$placeholder'
                                data-auth='$auth'></textarea>
                        <span id='comment-text-error' class='form-error'> No error </span>
                    </div>
                    <div class='comment-button-wrapper'>
                        <button class='comment-button' data-auth='$auth'> Post </button>
                    </div>
                </form>
            </div>
        </div>
        ";
}
