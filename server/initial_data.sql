INSERT INTO db.users (`name`, `email`, `password`, full_name, `verified`, `admin`)
VALUES ('site_admin', 'admin@edugrove.com', '$2y$10$kyL3zckjCYSMSfDtwW.1o.KcLtSY2rbiGbuWeJLXj2I8GsV.sJe1i',
        'Edu Grove', 1, true);
-- password = W%pC&X&F5nVBPX6!

INSERT INTO db.users (name, email, password, full_name, verified)
VALUES ('bunnyfan', 'bugs@bunny.com', '$2y$10$G3.BzVO9rX/S6hotrWDhmeZCOttaZFDxb9kF2YVzXlrQQAVXz/O0u',
        'Bunny Fanboy', 1);
-- password = ILoveBunni3s!

INSERT INTO db.users (name, email, password, full_name, verified)
VALUES ('crash_course', 'crash@course.com', '$2y$10$QAS04l8k94JPYbkUc1CfE.qmy0RHf2tJt6m2PrF5ZU26KhDwjmgOK',
        'Crash Course', 1);
-- password = 2CfL$tNa9EU2$fAk

INSERT INTO db.users (id, name, email, password, full_name, verified)
VALUES (4, 'iucn_nl', 'mail@iucn.nl', '$2y$10$CYulvltHQ1zcHTRci419leNcZGjXqxcfM1W2Jr7FjAvlYHAH.i.HC',
        'IUCN Nederland', 1);
-- password = DBF%B*@#xJ8hA!pt

INSERT db.items (tag, type, price)
VALUES ('example_paid', 'video', 10.00);

INSERT db.items (tag, type, price)
VALUES ('example_free', 'video', 0);

INSERT INTO db.videos (tag, name, description, subject, uploader, views, free)
SELECT 'example_paid', 'Giant Bunny', 'Look at this bunny!', 'biology', id, 0, false
FROM db.users
WHERE name = 'bunnyfan';

INSERT INTO db.videos (tag, name, description, subject, uploader, views)
SELECT 'example_free', 'Big Bunny', 'What a chonker!', 'biology', id, 0
FROM db.users
WHERE name = 'bunnyfan';

INSERT INTO db.balances (user_id, balance)
VALUES (1, 1000.00);

INSERT INTO db.balances (user_id, balance)
SELECT id, 100.00
FROM db.users
WHERE name = 'bunnyfan';

INSERT INTO db.comments (tag, commenter_id, item_id, text)
SELECT 'com1', id, 1, 'Insane rabbit!'
FROM db.users
WHERE `name` = 'bunnyfan';

INSERT INTO db.comments (tag, commenter_id, item_id, text)
SELECT 'com2', id, 1, 'Superb specimen...'
FROM db.users
WHERE `name` = 'bunnyfan';

INSERT INTO db.comments (tag, commenter_id, item_id, text, reply_tag)
SELECT 'com3', id, 1, 'I know right!', 'com1'
FROM db.users
WHERE `name` = 'bunnyfan';

INSERT INTO db.comments (tag, commenter_id, item_id, text, reply_tag)
SELECT 'com4', id, 1, 'Amazing', 'com3'
FROM db.users
WHERE `name` = 'bunnyfan';

INSERT INTO db.comments (tag, commenter_id, item_id, text, reply_tag)
SELECT 'com5', id, 1, 'Wonderful!', 'com3'
FROM db.users
WHERE `name` = 'bunnyfan';

INSERT INTO db.comments (tag, commenter_id, item_id, text)
SELECT 'com6', 1, 1, 'Har Har Har';

INSERT INTO db.scores (id, user_id, comment_tag, score)
VALUES (1, 1, 'com6', -1);
INSERT INTO db.scores (id, user_id, comment_tag, score)
VALUES (2, 2, 'com6', -1);
INSERT INTO db.scores (id, user_id, comment_tag, score)
VALUES (3, 2, 'com4', 1);