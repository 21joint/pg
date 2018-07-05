/*
    Admin Panel Changes:
    - Disabled Social Login and Sign-up Plugin 4.9.4p5
*/

ALTER TABLE `engine4_users`
ADD `gg_gender` int NOT NULL DEFAULT '3' AFTER `gg_following_count`,
ADD `gg_age_range` text NULL AFTER `gg_gender`,
ADD `gg_age_range_set` datetime NULL AFTER `gg_age_range`;

UPDATE `engine4_core_pages` 
SET `layout` = 'default-auth' WHERE `engine4_core_pages`.`page_id` = 9,

