INSERT INTO db.users (name, email, password, full_name, membership, join_date, verified)
VALUES ('bunnyfan', 'bugs@bunny.com', '$2y$10$G3.BzVO9rX/S6hotrWDhmeZCOttaZFDxb9kF2YVzXlrQQAVXz/O0u', 'Bunny Fanboy',
        DEFAULT, DEFAULT, 1); # password = ILoveBunni3s!

INSERT INTO db.videos (tag, name, description, subject, uploader, views, price)
SELECT 'example', 'Bunny', 'Look at this bunny!', 'nature', id, 0, 10.00
FROM db.users
WHERE name = 'bunnyfan';
