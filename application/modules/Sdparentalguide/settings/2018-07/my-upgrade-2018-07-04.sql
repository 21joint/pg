ALTER TABLE `engine4_users`
ADD `gg_gender` int NOT NULL DEFAULT '' AFTER `gg_following_count`,
ADD `gg_age_range` text NULL AFTER `gg_gender`,
ADD `gg_age_range_set` datetime NULL AFTER `gg_age_range`;