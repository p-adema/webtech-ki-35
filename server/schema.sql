CREATE DATABASE IF NOT EXISTS db;
USE db;

CREATE TABLE `users`
(
    `id`         BIGINT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(128) UNIQUE NOT NULL,
    `email`      VARCHAR(128) UNIQUE NOT NULL,
    `password`   VARCHAR(256)        NOT NULL,
    `full_name`  VARCHAR(128)        NOT NULL,
    `membership` VARCHAR(10)         NOT NULL,
    PRIMARY KEY (id)
);
CREATE TABLE `addresses`
(
    `user_id`       BIGINT UNSIGNED NOT NULL,
    `country`       VARCHAR(100)    NOT NULL,
    `city`          VARCHAR(100)    NOT NULL,
    `zipcode`       VARCHAR(100)    NOT NULL,
    `street_number` INT             NOT NULL,
    `address`       VARCHAR(100)    NULL,
    PRIMARY KEY (user_id)
);

CREATE TABLE `videos`
(
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100)    NOT NULL,
    `description` VARCHAR(256)    NOT NULL,
    `subject`     VARCHAR(100)    NOT NULL,
    `location`    VARCHAR(256)    NOT NULL,
    `tag_1`       VARCHAR(100)    NULL,
    `tag_2`       VARCHAR(100)    NULL,
    `uploader`    BIGINT UNSIGNED NOT NULL,
    `upload_date` DATETIME        NOT NULL,
    `views`       BIGINT          NOT NULL,
    `price`       DOUBLE(2, 2)    NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (`uploader`) REFERENCES `users` (`id`)
);

CREATE TABLE `comments`
(
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `commenter_id` BIGINT UNSIGNED NOT NULL,
    `video_id`     BIGINT UNSIGNED NOT NULL,
    `text`         VARCHAR(1000)   NOT NULL,
    `date`         DATETIME        NOT NULL,
    `reply_id`     BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (`commenter_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`),
    FOREIGN KEY (`reply_id`) REFERENCES `comments` (`id`)
);

CREATE TABLE `ratings`
(
    `id`       BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rater_id` BIGINT UNSIGNED NOT NULL,
    `video_id` BIGINT UNSIGNED NOT NULL,
    `rating`   INT             NOT NULL,
    `text`     VARCHAR(1000)   NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (`rater_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`)
);

CREATE TABLE `balances`
(
    `user_id` BIGINT UNSIGNED NOT NULL,
    `balance` BIGINT          NOT NULL,
    PRIMARY KEY (user_id)
);
