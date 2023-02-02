INSERT INTO ratings (rater_id, item_id, rating)
SELECT u.id, i.id, (FLOOR(3 + RAND() * 3 - ((i.id ^ 2) % 10) / 10))
FROM items i
         LEFT OUTER JOIN users u on (FLOOR(RAND() * 10)) > 5
WHERE u.id > 100;
