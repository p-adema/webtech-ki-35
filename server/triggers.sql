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

CREATE DEFINER = 'triggers' TRIGGER videos_new_watch
    AFTER INSERT
    ON watches
    FOR EACH ROW
    UPDATE db.videos v SET v.views = v.views + 1 WHERE v.tag = NEW.video_tag;

CREATE DEFINER = 'triggers' TRIGGER courses_new_watch
    AFTER INSERT
    ON watches
    FOR EACH ROW
    UPDATE courses c
        INNER JOIN course_videos cv on c.tag = cv.course_tag
        SET c.views = c.views + 1
        WHERE cv.video_tag = NEW.video_tag;






DELIMITER //
CREATE
    DEFINER = 'triggers' PROCEDURE course_ownership_add(
    IN user_id BIGINT UNSIGNED,
    IN course_tag CHAR(64),
    IN origin ENUM ('purchase', 'gift', 'owner'),
    IN purchase_id BIGINT UNSIGNED,
    IN gift_id BIGINT UNSIGNED
)
BEGIN
    INSERT INTO db.ownership (`item_tag`, `user_id`, `origin`, `purchase_id`, `gift_id`)
    SELECT video_tag, user_id, origin, purchase_id, gift_id
    FROM db.course_videos cv
    WHERE cv.course_tag = course_tag;
    INSERT INTO ownership (`item_tag`, `user_id`, `origin`, `purchase_id`, `gift_id`)
    VALUES (course_tag, user_id, origin, purchase_id, gift_id);
END //

CREATE PROCEDURE resolve_purchase(
    IN purchase_tag CHAR(64)
)
BEGIN
    #     DECLARE emailAddress varchar(100) DEFAULT "";
    DECLARE done INT DEFAULT FALSE;
    DECLARE course_tag CHAR(64);
    DEClARE cursor_courses
        CURSOR FOR
        SELECT i.tag
        FROM purchase_items p
                 INNER JOIN items i on p.item_id = i.id
        WHERE purchase_id = @purchase_id
          AND type = 'course';
    DECLARE CONTINUE
        HANDLER
        FOR NOT FOUND
        SET done = TRUE;

    SELECT amount, request_time, user_id, purchase_id
    INTO @amount, @request_time, @user_id, @purchase_id
    FROM db.transactions_pending
    WHERE (url_tag = purchase_tag);

    INSERT INTO db.transaction_log(user_id, amount, request_time)
    VALUES (@user_id, @amount, @request_time);

    DELETE FROM db.transactions_pending WHERE (url_tag = purchase_tag);

    UPDATE db.balances SET balance = balance - @amount WHERE (user_id = @user_id);

    UPDATE db.purchases
    SET confirmed         = TRUE,
        confirmation_time = NOW()
    WHERE id = @purchase_id;

    INSERT INTO ownership (item_tag, user_id, origin, purchase_id)
    SELECT i.tag, @user_id, 'purchase', @purchase_id
    FROM purchase_items AS P
             INNER JOIN items i on P.item_id = i.id
    WHERE purchase_id = @purchase_id
      AND type = 'video';

    OPEN cursor_courses;
    own_course:
    LOOP
        FETCH cursor_courses INTO course_tag;
        IF done THEN
            LEAVE own_course;
        END IF;
        CALL course_ownership_add(
                @user_id,
                course_tag,
                'purchase',
                @purchase_id,
                null
            );
    END LOOP own_course;
    CLOSE cursor_courses;

END//
DELIMITER ;
