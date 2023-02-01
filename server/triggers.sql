-- Update the score of a comment on a new rating
CREATE DEFINER = 'triggers' TRIGGER comments_new_score
    AFTER INSERT
    ON scores
    FOR EACH ROW
    UPDATE comments
    SET score = score + NEW.score
    WHERE comments.tag = NEW.comment_tag;

-- Update the score of a comment on a changed rating
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

-- Resolve ownership of a course and its videos
CREATE
    DEFINER = 'triggers' PROCEDURE course_ownership_add(
    IN user_id BIGINT UNSIGNED,
    IN course_tag CHAR(64),
    IN origin ENUM ('purchase', 'gift', 'owner'),
    IN purchase_id BIGINT UNSIGNED,
    IN gift_id BIGINT UNSIGNED
)
BEGIN
    INSERT INTO ownership (`item_tag`, `user_id`, `origin`, `purchase_id`, `gift_id`)
    SELECT video_tag, user_id, origin, purchase_id, gift_id
    FROM course_videos cv
    WHERE cv.course_tag = course_tag;

    INSERT INTO ownership (`item_tag`, `user_id`, `origin`, `purchase_id`, `gift_id`)
    VALUES (course_tag, user_id, origin, purchase_id, gift_id);
END //

-- Resolve a standard purchase
CREATE PROCEDURE resolve_purchase(
    IN purchase_tag CHAR(64)
)
BEGIN
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
    FROM transactions_pending
    WHERE (url_tag = purchase_tag);

    INSERT INTO transaction_log(user_id, amount, request_time)
    VALUES (@user_id, @amount, @request_time);

    DELETE FROM transactions_pending WHERE (url_tag = purchase_tag);

    UPDATE balances SET balance = balance - @amount WHERE (user_id = @user_id);

    UPDATE purchases
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
END //

-- Resolve an admin gift
CREATE
    DEFINER = 'triggers' PROCEDURE resolve_gift(
    IN admin_uid BIGINT UNSIGNED,
    IN receiving_uid BIGINT UNSIGNED,
    IN itm_id BIGINT UNSIGNED,
    IN itm_tag CHAR(64)
)
BEGIN
    INSERT INTO gifts (`item_id`, `user_id`, `admin_id`)
    VALUES (itm_id, receiving_uid, admin_uid);

    SELECT last_insert_id() INTO @gift_id;

    CASE (SELECT type FROM items WHERE id = itm_id)
        WHEN 'video' THEN INSERT INTO ownership (`item_tag`, `user_id`, `origin`, `gift_id`)
                          VALUES (itm_tag, receiving_uid, 'gift', @gift_id);
        WHEN 'course' THEN CALL course_ownership_add(receiving_uid, itm_tag, 'gift', null, @gift_id);
        END CASE;
END //

-- Resolve account verification, given an email tag
CREATE
    DEFINER = 'triggers' PROCEDURE resolve_account(
    IN verification_tag CHAR(64)
)
BEGIN
    SELECT user_id
    INTO @user_id
    FROM emails_pending
    WHERE url_tag = verification_tag
      AND type = 'verify';

    UPDATE users u
    SET u.verified = 1
    WHERE u.id = @user_id;

    DELETE
    FROM emails_pending p
    WHERE p.url_tag = verification_tag;

    INSERT INTO balances (user_id, balance)
    VALUES (@user_id, 100.00);
END //

DELIMITER ;
