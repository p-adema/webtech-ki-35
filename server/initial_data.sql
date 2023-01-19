INSERT INTO db.users (id, name, email, password, full_name, membership, join_date, verified, admin)
VALUES (1, 'site_admin', 'admin@edugrove.com', '$2y$10$kyL3zckjCYSMSfDtwW.1o.KcLtSY2rbiGbuWeJLXj2I8GsV.sJe1i',
        'Edu Grove',
        'member', DEFAULT, 1, true);

-- password = W%pC&X&F5nVBPX6!

INSERT INTO db.balances (user_id, balance)
SELECT 1, 1000.00;

INSERT INTO db.users (name, email, password, full_name, membership, join_date, verified)
VALUES ('bunnyfan', 'bugs@bunny.com', '$2y$10$G3.BzVO9rX/S6hotrWDhmeZCOttaZFDxb9kF2YVzXlrQQAVXz/O0u', 'Bunny Fanboy',
        DEFAULT, DEFAULT, 1);

-- password = ILoveBunni3s!

INSERT db.items (tag, type, price)
VALUES ('example_paid', 'video', 10.00);

INSERT db.items (tag, type, price)
VALUES ('example_free', 'video', 0);

INSERT INTO db.videos (tag, name, description, subject, uploader, views, free)
SELECT 'example_paid', 'Bunny', 'Look at this bunny!', 'biology', id, 0, false
FROM db.users
WHERE name = 'bunnyfan';

INSERT INTO db.videos (tag, name, description, subject, uploader, views)
SELECT 'example_free', 'Bunny', 'What a chonker!', 'biology', id, 0
FROM db.users
WHERE name = 'bunnyfan';

INSERT INTO db.balances (user_id, balance)
SELECT id, 100.00
FROM db.users
WHERE name = 'bunnyfan';

INSERT INTO db.transactions_pending (amount, url_tag, user_id, request_time)
SELECT 40.00, 'Hallo', id, '2023-01-18 13:09:15'
FROM db.users
WHERE name = 'bunnyfan';



INSERT INTO db.transaction_log (user_id, amount, request_time)
SELECT id, 30.00, '2023-01-18 13:09:15'
FROM db.users
WHERE name = 'bunnyfan'
