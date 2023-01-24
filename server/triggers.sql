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
