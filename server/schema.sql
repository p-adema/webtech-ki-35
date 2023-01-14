CREATE DATABASE IF NOT EXISTS db;
USE db;

CREATE TABLE `users`
(
    `id`         BIGINT UNSIGNED                        NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(128) UNIQUE                    NOT NULL,
    `email`      VARCHAR(128) UNIQUE                    NOT NULL,
    `password`   VARCHAR(256)                           NOT NULL,
    `full_name`  VARCHAR(128)                           NULL,
    `membership` ENUM ('none', 'member') DEFAULT 'none' NOT NULL,
    `join_date`  DATETIME                DEFAULT NOW()  NOT NULL,
    PRIMARY KEY (`id`) # TODO: Add email verified
);

CREATE TABLE `billing_information`
(
    `user_id`       BIGINT UNSIGNED   NOT NULL,
    `legal_name`    VARCHAR(256)      NOT NULL,
    `country`       VARCHAR(100)      NOT NULL,
    `city`          VARCHAR(100)      NOT NULL,
    `zipcode`       VARCHAR(100)      NOT NULL,
    `street_number` SMALLINT UNSIGNED NOT NULL,
    `address`       VARCHAR(100)      NULL,
    PRIMARY KEY (`user_id`)
);

CREATE TABLE `videos`
(
    `id`          BIGINT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100)           NOT NULL,
    `description` VARCHAR(256)           NOT NULL,
    `subject`     VARCHAR(100)           NOT NULL,
    `uploader`    BIGINT UNSIGNED        NOT NULL,
    `upload_date` DATETIME DEFAULT NOW() NOT NULL,
    `views`       BIGINT UNSIGNED        NOT NULL,
    `price`       DECIMAL(5, 2)          NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`uploader`) REFERENCES `users` (`id`)
);

CREATE TABLE `comments`
(
    `id`           BIGINT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `commenter_id` BIGINT UNSIGNED        NOT NULL,
    `video_id`     BIGINT UNSIGNED        NOT NULL,
    `text`         VARCHAR(1000)          NOT NULL,
    `date`         DATETIME DEFAULT NOW() NOT NULL,
    `reply_id`     BIGINT UNSIGNED        NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`commenter_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`),
    FOREIGN KEY (`reply_id`) REFERENCES `comments` (`id`)
);

CREATE TABLE `ratings`
(
    `id`       BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `rater_id` BIGINT UNSIGNED  NOT NULL,
    `video_id` BIGINT UNSIGNED  NOT NULL,
    `rating`   TINYINT UNSIGNED NOT NULL,
    `text`     VARCHAR(1000)    NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (`rater_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`)
);

CREATE TABLE `balances`
(
    `user_id` BIGINT UNSIGNED NOT NULL,
    `balance` DECIMAL(10, 2)  NOT NULL,
    PRIMARY KEY (`user_id`)
);
# localhost/verify?tag=fweuifg374ugf37egf3uyfgeuyferuyfg
CREATE TABLE `emails_pending`
(
    `id`           BIGINT UNSIGNED AUTO_INCREMENT,
    `type`         ENUM ('verify', 'password-reset') NOT NULL,
    `url_tag`      CHAR(64)                          NOT NULL,
    `user_id`      BIGINT UNSIGNED                   NOT NULL,
    `request_time` DATETIME DEFAULT NOW()            NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`url_tag`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`)
);

CREATE TABLE `transactions_pending`
(
    `id`           BIGINT UNSIGNED AUTO_INCREMENT,
    `amount`       DECIMAL(5, 2)          NOT NULL,
    `url_tag`      CHAR(64)               NOT NULL,
    `user_id`      BIGINT UNSIGNED        NOT NULL,
    `request_time` DATETIME DEFAULT NOW() NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`url_tag`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`)
);

CREATE TABLE `transaction_log`
(
    `id`           BIGINT UNSIGNED AUTO_INCREMENT,
    `user_id`      BIGINT UNSIGNED        NOT NULL,
    `amount`       DECIMAL(5, 2)          NOT NULL,
    `request_time` DATETIME               NOT NULL,
    `payment_time` DATETIME DEFAULT NOW() NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`)
);

CREATE TABLE `video_tags`
(
    `video_id` BIGINT UNSIGNED NOT NULL,
    `tag`      VARCHAR(16),
    FOREIGN KEY (`video_id`) REFERENCES db.videos (`id`)
);
