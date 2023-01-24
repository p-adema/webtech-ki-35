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
    `verified`   BOOLEAN                 DEFAULT FALSE  NOT NULL,
    `admin`      BOOLEAN                 DEFAULT FALSE  NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `billing_information`
(
    `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`       BIGINT UNSIGNED NOT NULL,
    `legal_name`    VARCHAR(256)    NOT NULL,
    `country`       VARCHAR(100)    NOT NULL,
    `city`          VARCHAR(100)    NOT NULL,
    `zipcode`       VARCHAR(100)    NOT NULL,
    `street_number` VARCHAR(100)    NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`)
);

CREATE TABLE `items`
(
    `id`    BIGINT UNSIGNED          NOT NULL AUTO_INCREMENT,
    `tag`   CHAR(64) UNIQUE          NOT NULL,
    `type`  ENUM ('video', 'course') NOT NULL,
    `price` DECIMAL(5, 2) DEFAULT 0  NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `videos`
(
    `id`          BIGINT UNSIGNED               NOT NULL AUTO_INCREMENT,
    `tag`         CHAR(64) UNIQUE               NOT NULL,
    `name`        VARCHAR(100)                  NOT NULL,
    `free`        BOOLEAN         DEFAULT TRUE  NOT NULL,
    `description` VARCHAR(256)                  NOT NULL,
    `subject`     VARCHAR(100)                  NOT NULL,
    `uploader`    BIGINT UNSIGNED               NOT NULL,
    `upload_date` DATETIME        DEFAULT NOW() NOT NULL,
    `views`       BIGINT UNSIGNED DEFAULT 0     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`uploader`) REFERENCES `users` (`id`),
    FOREIGN KEY (`tag`) REFERENCES `items` (`tag`)
);

CREATE TABLE `courses`
(
    `id`            BIGINT UNSIGNED               NOT NULL AUTO_INCREMENT,
    `tag`           CHAR(64) UNIQUE               NOT NULL,
    `name`          VARCHAR(100)                  NOT NULL,
    `description`   VARCHAR(256)                  NOT NULL,
    `subject`       VARCHAR(100)                  NOT NULL,
    `creator`       BIGINT UNSIGNED               NOT NULL,
    `creation_date` DATETIME        DEFAULT NOW() NOT NULL,
    `views`         BIGINT UNSIGNED DEFAULT 0     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`creator`) REFERENCES `users` (`id`),
    FOREIGN KEY (`tag`) REFERENCES `items` (`tag`)
);

CREATE TABLE `course_videos`
(
    `id`         BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `video_tag`  CHAR(64)          NOT NULL,
    `course_tag` CHAR(64)          NOT NULL,
    `order`      SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`video_tag`) REFERENCES `videos` (`tag`),
    FOREIGN KEY (`course_tag`) REFERENCES `courses` (`tag`)
);

CREATE TABLE `comments`
(
    `id`           BIGINT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `tag`          CHAR(64) UNIQUE        NOT NULL,
    `commenter_id` BIGINT UNSIGNED        NOT NULL,
    `item_id`      BIGINT UNSIGNED        NOT NULL,
    `text`         VARCHAR(1000)          NOT NULL,
    `date`         DATETIME DEFAULT NOW() NOT NULL,
    `reply_tag`    CHAR(64)               NULL,
    `score`        BIGINT   DEFAULT 0     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`commenter_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
    FOREIGN KEY (`reply_tag`) REFERENCES `comments` (`tag`)
);

CREATE TABLE `scores`
(
    `id`          BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `user_id`     BIGINT UNSIGNED   NOT NULL,
    `comment_tag` CHAR(64)          NOT NULL,
    `score`       TINYINT DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES users (`id`),
    FOREIGN KEY (`comment_tag`) REFERENCES comments (`tag`)
);

CREATE TABLE `ratings`
(
    `id`       BIGINT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `rater_id` BIGINT UNSIGNED        NOT NULL,
    `item_id`  BIGINT UNSIGNED        NOT NULL,
    `rating`   TINYINT UNSIGNED       NOT NULL,
    `text`     VARCHAR(1000)          NULL,
    `date`     DATETIME DEFAULT NOW() NOT NULL,
    `score`    BIGINT   DEFAULT 0     NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (`rater_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
);

CREATE TABLE `balances`
(
    `id`      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `balance` DECIMAL(10, 2)  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES users (`id`)
);

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

CREATE TABLE `purchases`
(
    `id`                BIGINT UNSIGNED AUTO_INCREMENT,
    `url_tag`           CHAR(64)              NOT NULL,
    `amount`            DECIMAL(5, 2)         NOT NULL,
    `user_id`           BIGINT UNSIGNED       NOT NULL,
    `info_id`           BIGINT UNSIGNED       NOT NULL,
    `request_time`      DATETIME              NOT NULL,
    `confirmation_time` DATETIME              NULL,
    `confirmed`         BOOLEAN DEFAULT FALSE NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`url_tag`),
    FOREIGN KEY (`info_id`) REFERENCES db.billing_information (`id`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`)
);

CREATE TABLE `purchase_items`
(
    `id`          BIGINT UNSIGNED AUTO_INCREMENT,
    `purchase_id` BIGINT UNSIGNED NOT NULL,
    `item_id`     BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`purchase_id`) REFERENCES db.purchases (`id`)
);

CREATE TABLE `gifts`
(
    `id`                BIGINT UNSIGNED AUTO_INCREMENT,
    `url_tag`           CHAR(64)               NOT NULL,
    `item_id`           BIGINT UNSIGNED        NOT NULL,
    `user_id`           BIGINT UNSIGNED        NOT NULL,
    `admin_id`          BIGINT UNSIGNED        NOT NULL,
    `confirmation_time` DATETIME DEFAULT NOW() NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`url_tag`),
    FOREIGN KEY (`item_id`) REFERENCES db.items (`id`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`),
    FOREIGN KEY (`admin_id`) REFERENCES db.users (`id`)
);

CREATE TABLE `ownership`
(
    `id`          BIGINT UNSIGNED           NOT NULL AUTO_INCREMENT,
    `item_tag`    CHAR(64)                  NOT NULL,
    `user_id`     BIGINT UNSIGNED           NOT NULL,
    `origin`      ENUM ('purchase', 'gift') NOT NULL,
    `purchase_id` BIGINT UNSIGNED           NULL,
    `gift_id`     BIGINT UNSIGNED           NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`item_tag`) REFERENCES db.items (`tag`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`),
    FOREIGN KEY (`purchase_id`) REFERENCES db.purchases (`id`),
    FOREIGN KEY (`gift_id`) REFERENCES db.gifts (`id`)
);

CREATE TABLE `transactions_pending`
(
    `id`           BIGINT UNSIGNED AUTO_INCREMENT,
    `amount`       DECIMAL(5, 2)                 NOT NULL,
    `url_tag`      CHAR(64)                      NOT NULL,
    `user_id`      BIGINT UNSIGNED               NOT NULL,
    `request_time` DATETIME        DEFAULT NOW() NOT NULL,
    `to_id`        BIGINT UNSIGNED DEFAULT 1     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`url_tag`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`)
);

CREATE TABLE `transaction_log`
(
    `id`           BIGINT UNSIGNED AUTO_INCREMENT,
    `user_id`      BIGINT UNSIGNED               NOT NULL,
    `amount`       DECIMAL(5, 2)                 NOT NULL,
    `request_time` DATETIME                      NOT NULL,
    `payment_time` DATETIME        DEFAULT NOW() NOT NULL,
    `to_id`        BIGINT UNSIGNED DEFAULT 1     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES db.users (`id`)
);

CREATE TABLE `item_tags`
(
    `id`      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `item_id` BIGINT UNSIGNED NOT NULL,
    `tag`     VARCHAR(16),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`item_id`) REFERENCES db.items (`id`)
);

CREATE USER 'triggers';
GRANT ALL PRIVILEGES ON db.* TO 'triggers';

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
