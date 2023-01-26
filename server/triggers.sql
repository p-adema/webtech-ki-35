CREATE DEFINER = 'triggers' TRIGGER comments_new_score
    AFTER INSERT
    ON scores
    FOR EACH ROW
    UPDATE comments
    SET score = score + NEW.score
    WHERE comments.tag = NEW.comment_tag;

CREATE DEFINER = 'triggers' TRIGGER comments_changed_score
    AFTER UPDATE
    ON scores
    FOR EACH ROW
    UPDATE comments
    SET score = score + NEW.score - OLD.score
    WHERE comments.tag = NEW.comment_tag;

CREATE DEFINER = 'triggers' TRIGGER course_ownership_add
    AFTER INSERT
    ON course_ownership
    FOR EACH ROW
    INSERT INTO ownership (item_tag, user_id, origin, purchase_id, gift_id)
    SELECT video_tag, NEW.user_id, NEW.origin, NEW.purchase_id, NEW.gift_id
    FROM course_videos
    WHERE course_tag = NEW.item_tag;
