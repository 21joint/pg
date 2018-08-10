/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jul 5, 2018
 */



/*** Queries 09 Jan 2018 ***/

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_badge_badges',	'sdparentalguide',	'Badge View',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"manage\",\"action\":\"badges\"}',	'sdparentalguide_admin_main_badges',	'',	1,	0,	1),
('sdparentalguide_admin_badge_users',	'sdparentalguide',	'User View',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"manage\",\"action\":\"badge-users\"}',	'sdparentalguide_admin_main_badges',	'',	1,	0,	2);

ALTER TABLE `engine4_users`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;

ALTER TABLE `engine4_sitereview_listingtypes`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;

DROP TABLE IF EXISTS `engine4_gg_user_badges`;
CREATE TABLE `engine4_gg_user_badges` (
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`badge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `engine4_gg_badges`;
CREATE TABLE `engine4_gg_badges` (
  `badge_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `level` int(11) NOT NULL,
  `listingtype_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`badge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `engine4_gg_user_badges`
ADD `assignedbadge_id` int NOT NULL AUTO_INCREMENT UNIQUE FIRST;
ALTER TABLE `engine4_gg_user_badges` DROP PRIMARY KEY, ADD PRIMARY KEY( `assignedbadge_id`);
ALTER TABLE `engine4_gg_user_badges` ADD `owner_id` int NULL DEFAULT '0';
ALTER TABLE `engine4_gg_badges` ADD `owner_id` int NULL DEFAULT '0';




/*** Queries2 21 Jan 2018 ***/

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_main_topics',	'sdparentalguide',	'Topics',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"topics\",\"action\":\"index\"}',	'sdparentalguide_admin_main',	'',	1,	0,	6);

DROP TABLE IF EXISTS `engine4_gg_topics`;
CREATE TABLE `engine4_gg_topics` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` longtext,
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `search` tinyint(4) NOT NULL DEFAULT '1',
  `body` longtext,
  `listingtype_id` int(11) DEFAULT '0',
  `category_id` int(11) DEFAULT '0',
  `subcategory_id` int(11) DEFAULT '0',
  `approved` tinyint(4) NOT NULL DEFAULT '1',
  `question_count` int(11) NOT NULL DEFAULT '0',
  `listing_count` int(11) NOT NULL DEFAULT '0',
  `follow_count` int(11) NOT NULL DEFAULT '0',
  `view_count` int(11) NOT NULL DEFAULT '0',
  `featured` int(11) NOT NULL DEFAULT '0',
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `cover_id` int(11) NOT NULL DEFAULT '0',
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*** Queries3 26 Jan 2018 ***/

INSERT INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`, `processes`, `semaphore`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES
('User Credibility Maintenance',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Credibility',	300,	1,	0,	0,	0,	0,	0,	0,	0,	0,	0);

ALTER TABLE `engine4_users`
ADD `gg_contribution` int NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_activities` int(11) NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_contribution_updated` tinyint NULL DEFAULT '0';

ALTER TABLE `engine4_gg_topics`
ADD `custom` tinyint NULL DEFAULT '1';

UPDATE engine4_sitereview_listingtypes SET featured_color = '#ffd819';




/*** Queries4 03 Feb 2018 ***/

ALTER TABLE `engine4_users`
DROP `gg_contribution`,
DROP `gg_activities`,
DROP `gg_contribution_updated`;

ALTER TABLE `engine4_users`
ADD `gg_contribution` int NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_activities` int(11) NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_contribution_updated` tinyint NULL DEFAULT '0';




/*** Queries4.9.4p7 ***/

DROP TABLE IF EXISTS `engine4_gg_user_preferences`;
CREATE TABLE `engine4_gg_user_preferences` (
  `preference_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `listingtype_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`preference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT IGNORE INTO `engine4_user_signup` (`class`, `order`, `enable`) VALUES
('Sdparentalguide_Plugin_Signup_Interests',	6,	1);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('user_profile_preferences',	'sdparentalguide',	'User Preferences',	'Sdparentalguide_Plugin_Menus::editPreferences',	'',	'user_profile',	'',	1,	0,	10);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('user_edit_preferences',	'sdparentalguide',	'User Preferences',	'Sdparentalguide_Plugin_Menus::editPreferences',	'',	'user_edit',	'',	1,	0,	4);

ALTER TABLE `engine4_sitereview_listings`
ADD `gg_sequence` int(11) NULL DEFAULT '1',
ADD `gg_score` int(11) NULL DEFAULT '1' AFTER `gg_sequence`;

ALTER TABLE `engine4_sitereview_listings`
ADD INDEX `gg_score` (`gg_score`),
ADD INDEX `gg_sequence` (`gg_sequence`);

ALTER TABLE `engine4_sitehashtag_tags`
ADD `topic_id` int(11) NOT NULL AFTER `tag_id`,
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;

DROP TABLE IF EXISTS `engine4_gg_listing_topics`;
CREATE TABLE `engine4_gg_listing_topics` (
  `listingtopic_id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `gg_dt_created` datetime NULL,
  `gg_dt_lastmodified` datetime NULL,
  `gg_user_created` int NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) NULL,
  `gg_ip_lastmodified` varbinary(16) NULL,
  PRIMARY KEY (`listingtopic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



/*** Queries4.9.4p8 ***/

DROP TABLE IF EXISTS `engine4_gg_site_activities`;
CREATE TABLE `engine4_gg_site_activities` (
  `site_activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `is_member` int(11) NOT NULL,
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`site_activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `engine4_gg_search_activity`;
CREATE TABLE `engine4_gg_search_activity` (
  `search_activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `search_text` varchar(128) NOT NULL,
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`search_activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `engine4_gg_family_member`;
CREATE TABLE `engine4_gg_family_member` (
  `family_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` int(11) NOT NULL DEFAULT '3',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`family_member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `engine4_sdparentalguide_relationships`;
CREATE TABLE `engine4_sdparentalguide_relationships` (
  `relationship_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  PRIMARY KEY (`relationship_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `engine4_sdparentalguide_relationships` (`relationship_id`, `title`) VALUES
(1,	'Spouse'),
(2,	'Child'),
(3,	'Other');

ALTER TABLE `engine4_core_tags`
ADD `topic_id` int NOT NULL DEFAULT '0';

INSERT IGNORE INTO `engine4_user_signup` (`class`, `order`, `enable`) VALUES
('Sdparentalguide_Plugin_Signup_Family',	7,	1);




/*** Queries4.9.4p9 ***/

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('user_profile_familymembers',	'sdparentalguide',	'Family Members',	'Sdparentalguide_Plugin_Menus::editFamilyMembers',	'',	'user_profile',	'',	1,	0,	11);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('user_edit_familymembers',	'sdparentalguide',	'Family Members',	'Sdparentalguide_Plugin_Menus::editFamilyMembers',	'',	'user_edit',	'',	1,	0,	5);




/*** Queries4.10.1 ***/

ALTER TABLE `engine4_gg_topics`
ADD `badges` tinyint(4) NULL DEFAULT '0';

ALTER TABLE `engine4_gg_badges`
ADD `topic_id` int(11) NOT NULL AFTER `listingtype_id`;

ALTER TABLE `engine4_users`
ADD `gg_mvp` tinyint(4) NOT NULL DEFAULT '0',
ADD `gg_expert` tinyint(4) NOT NULL DEFAULT '0' AFTER `gg_mvp`;

ALTER TABLE `engine4_sitereview_listings`
ADD `gg_author_product_rating` smallint NOT NULL DEFAULT '0';

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
VALUES( 'sdparentalguide_admin_main_search', 'sdparentalguide', 'Search', '', '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"search\",\"action\":\"index\"}', 'sdparentalguide_admin_main', '', '1', '0', '7');

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
VALUES ('sdparentalguide_admin_search_activity', 'sdparentalguide', 'Search Activity', '', '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"search\",\"action\":\"activity\"}', 'sdparentalguide_admin_main_search', NULL, '1', '0', '1');

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
VALUES ('sdparentalguide_admin_search_alias', 'sdparentalguide', 'Search Alias', '', '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"search\",\"action\":\"index\"}', 'sdparentalguide_admin_main_search', NULL, '1', '0', '1');

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
VALUES ('sdparentalguide_admin_search_analytics', 'sdparentalguide', 'Search Analytics', '', '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"search\",\"action\":\"analytics\"}', 'sdparentalguide_admin_main_search', NULL, '1', '0', '3');

CREATE TABLE `engine4_gg_search_terms` (
  `searchterm_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL
);

ALTER TABLE `engine4_gg_search_terms`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;

CREATE TABLE `engine4_gg_search_terms_aliases` (
  `searchtermsalias_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `searchterm_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
);

ALTER TABLE `engine4_gg_search_terms_aliases`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;

DROP TABLE IF EXISTS `engine4_gg_search_analytics`;
CREATE TABLE `engine4_gg_search_analytics` (
  `searchanalytic_id` int(11) NOT NULL AUTO_INCREMENT,
  `search_term` varchar(128) NOT NULL,
  `count` int(11) NOT NULL,
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`searchanalytic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



/*** Queries4.10.1p1 ***/

DROP TABLE IF EXISTS `engine4_gg_listing_ratings`;
CREATE TABLE `engine4_gg_listing_ratings` (
  `listingrating_id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `review_rating` smallint(6) NOT NULL DEFAULT '0',
  `product_rating` smallint(6) NOT NULL DEFAULT '0',
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`listingrating_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `engine4_gg_search_analytics`;
CREATE TABLE `engine4_gg_search_analytics` (
  `searchanalytic_id` int(11) NOT NULL AUTO_INCREMENT,
  `search_term` varchar(128) NOT NULL,
  `count` int(11) NOT NULL,
  `gg_dt_created` datetime DEFAULT NULL,
  `gg_dt_lastmodified` datetime DEFAULT NULL,
  `gg_user_created` int(11) NOT NULL DEFAULT '0',
  `gg_user_lastmodified` int(11) NOT NULL DEFAULT '0',
  `gg_guid` varchar(128) DEFAULT NULL,
  `gg_ip_lastmodified` varbinary(16) DEFAULT NULL,
  PRIMARY KEY (`searchanalytic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

UPDATE `engine4_core_menuitems` SET
`name` = 'sdparentalguide_admin_search_analytics',
`label` = 'Search Analytics',
`plugin` = '',
`params` = '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"search\",\"action\":\"analytics\"}',
`menu` = 'sdparentalguide_admin_main_search',
`submenu` = NULL,
`enabled` = '1',
`custom` = '0',
`order` = '3'
WHERE `name` = 'sdparentalguide_admin_search_new';



/*** Queries4.10.3 May 16, 2018 ***/

ALTER TABLE `engine4_users`
CHANGE `gg_credibility` `gg_contribution` int(11) NULL DEFAULT '0' AFTER `gg_ip_lastmodified`,
CHANGE `gg_credibility_updated` `gg_contribution_updated` tinyint(4) NULL DEFAULT '0' AFTER `gg_activities`;


ALTER TABLE `engine4_users`
ADD `gg_contribution_level` int NOT NULL DEFAULT '0',
ADD `gg_review_count` int NOT NULL DEFAULT '0' AFTER `gg_contribution_level`,
ADD `gg_question_count` int NOT NULL DEFAULT '0' AFTER `gg_review_count`,
ADD `gg_guide_count` int NOT NULL DEFAULT '0' AFTER `gg_question_count`,
ADD `gg_bronze_count` int NOT NULL DEFAULT '0' AFTER `gg_guide_count`,
ADD `gg_silver_count` int NOT NULL DEFAULT '0' AFTER `gg_bronze_count`,
ADD `gg_gold_count` int NOT NULL DEFAULT '0' AFTER `gg_silver_count`,
ADD `gg_platinum_count` int NOT NULL DEFAULT '0' AFTER `gg_gold_count`,
ADD `gg_expert_bronze_count` int NOT NULL DEFAULT '0' AFTER `gg_platinum_count`,
ADD `gg_expert_silver_count` int NOT NULL DEFAULT '0' AFTER `gg_expert_bronze_count`,
ADD `gg_expert_gold_count` int NOT NULL DEFAULT '0' AFTER `gg_expert_silver_count`,
ADD `gg_expert_platinum_count` int NOT NULL DEFAULT '0' AFTER `gg_expert_gold_count`,
ADD `gg_followers_count` int NOT NULL DEFAULT '0' AFTER `gg_expert_platinum_count`,
ADD `gg_following_count` int NOT NULL DEFAULT '0' AFTER `gg_followers_count`;

ALTER TABLE `engine4_users`
CHANGE `gg_expert` `gg_expert_count` int NOT NULL DEFAULT '0' AFTER `gg_mvp`;

ALTER TABLE `engine4_gg_assigned_badges`
RENAME TO `engine4_gg_user_badges`;

ALTER TABLE `engine4_gg_user_badges`
ADD `profile_display` tinyint NULL DEFAULT '1';

ALTER TABLE `engine4_gg_badges`
ADD `profile_display` tinyint NULL DEFAULT '0',
ADD `type` varchar(32) NULL AFTER `profile_display`;

ALTER TABLE `engine4_gg_badges`
ADD `description` longtext COLLATE 'utf8_unicode_ci' NOT NULL AFTER `name`;

ALTER TABLE `engine4_gg_badges`
DROP `listingtype_id`;













/*** Started here for TST DB ***/

/*** my-upgrade 2018-05-23 + 2018-05-21 ***/

/* Delete unused Widgets */
DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = 111;
DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = 112;

/* Update */
UPDATE `engine4_core_content` SET `name` = 'sdparentalguide.header' WHERE `engine4_core_content`.`content_id` = 110;
UPDATE `engine4_core_content` SET `params` = '[\"\"]' WHERE `engine4_core_content`.`content_id` = 110;

/* footer */
UPDATE `engine4_core_content` SET `name` = 'sdparentalguide.footer' WHERE `engine4_core_content`.`content_id` = 210;
UPDATE `engine4_core_content` SET `params` = '[\"\"]' WHERE `engine4_core_content`.`content_id` = 210;




/*** Queries4.10.3p1 May 24, 2018 ***/

ALTER TABLE `engine4_users`
ADD `gg_reviews_count` int(11) NOT NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_questions_count` int(11) NOT NULL DEFAULT '0';

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_main_jobs',	'sdparentalguide',	'Admin Jobs',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"manage\",\"action\":\"jobs\"}',	'sdparentalguide_admin_main',	'',	1,	0,	2);

UPDATE `engine4_core_menuitems` SET
`order` = '3'
WHERE `name` = 'sdparentalguide_admin_main_onboarding';

UPDATE `engine4_core_menuitems` SET
`order` = '4',
`label` = 'Listings'
WHERE `name` = 'sdparentalguide_admin_main_listings';

UPDATE `engine4_core_menuitems` SET
`order` = '5'
WHERE `name` = 'sdparentalguide_admin_main_badges';

UPDATE `engine4_core_menuitems` SET
`order` = '6',
`label` = 'Users'
WHERE `name` = 'sdparentalguide_admin_main_featuredusers';

UPDATE `engine4_core_menuitems` SET
`order` = '7'
WHERE `name` = 'sdparentalguide_admin_main_topics';

UPDATE `engine4_core_menuitems` SET
`order` = '8'
WHERE `name` = 'sdparentalguide_admin_main_search';

DROP TABLE IF EXISTS `engine4_gg_tasks`;
CREATE TABLE `engine4_gg_tasks` (
  `task_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `module` varchar(128) NOT NULL DEFAULT '',
  `plugin` varchar(128) NOT NULL,
  `per_page` int(11) unsigned NOT NULL DEFAULT '50',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `plugin` (`plugin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `engine4_gg_tasks` (`task_id`, `title`, `module`, `plugin`, `per_page`, `enabled`) VALUES
(1,	'Recalculate All Contribution Points',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Contribution',	50,	0),
(2,	'Recalculate All Following',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Following',	50,	1),
(3,	'Recalculate Contribution Level',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_ContributionLevel',	50,	0),
(4,	'Recalculate Review Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Reviews',	50,	1),
(5,	'Recalculate Question Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Questions',	50,	1),
(6,	'Recalculate Guide Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Guide',	50,	0),
(7,	'Recalculate Badge Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Badges',	50,	1);

DELETE FROM `engine4_core_menuitems`
WHERE `menu` = 'sdparentalguide_admin_main' AND ((`name` = 'sdparentalguide_admin_main_onboarding'));



/*** Queries4.10.3p2 May 26, 2018 ***/

UPDATE `engine4_gg_tasks` SET
`enabled` = '1'
WHERE `plugin` = 'Sdparentalguide_Plugin_Task_Contribution';
UPDATE `engine4_gg_tasks` SET
`enabled` = '1'
WHERE `plugin` = 'Sdparentalguide_Plugin_Task_ContributionLevel';

ALTER TABLE `engine4_sitecredit_badges`
ADD `gg_contribution_level` int(11) NOT NULL DEFAULT '0',
ADD `gg_level_id` int(11) NOT NULL DEFAULT '0' AFTER `gg_contribution_level`;




/*** Queries4.10.3p3 May 30, 2018 ***/

UPDATE `engine4_core_menuitems` SET
`params` = '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"search\",\"action\":\"activity\"}'
WHERE `name` = 'sdparentalguide_admin_main_search';

INSERT INTO `engine4_gg_tasks` (`title`, `module`, `plugin`, `per_page`, `enabled`) VALUES
('Recalculate Search Analytics',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_SearchAnalytics',	50,	1);

ALTER TABLE `engine4_gg_topics`
DROP `listingtype_id`,
DROP `category_id`,
DROP `subcategory_id`;

ALTER TABLE `engine4_gg_topics`
ADD `name_plural` varchar(128) COLLATE 'utf8_general_ci' NULL AFTER `name`;

UPDATE engine4_gg_topics SET name_plural = name;
DROP TABLE `engine4_gg_listing_topics`;

ALTER TABLE `engine4_users`
DROP `gg_questions_count`;
ALTER TABLE `engine4_users`
DROP `gg_reviews_count`;
ALTER TABLE `engine4_users`
DROP `gg_expert_count`;



/*** Queries4.10.3p4 Jun 11, 2018 ***/

ALTER TABLE `engine4_sitereview_listingtypes`
ADD `gg_topic_id` int NULL DEFAULT '0';

ALTER TABLE `engine4_sitecredit_credits`
ADD `gg_topic_id` int NULL DEFAULT '0';

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
VALUES ('sdparentalguide_admin_main_contribution', 'sdparentalguide', 'Contribution', NULL, '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"contribution\",\"action\":\"index\"}', 'sdparentalguide_admin_main', NULL, '1', '0', '9');

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_main_contribution_members',	'sdparentalguide',	'Members By Topic',	NULL,	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"contribution\",\"action\":\"members\"}',	'sdparentalguide_admin_main_contribution',	NULL,	1,	0,	2),
('sdparentalguide_admin_main_contribution_transaction',	'sdparentalguide',	'All Transaction',	NULL,	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"contribution\",\"action\":\"index\"}',	'sdparentalguide_admin_main_contribution',	NULL,	1,	0,	1);

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_main_levels',	'sdparentalguide',	'Levels',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"level\",\"action\":\"manage\"}',	'sdparentalguide_admin_main',	'',	1,	0,	6);




/*** Queries4.10.3p5 Jun 12, 2018 ***/

ALTER TABLE `engine4_sitereview_listingtypes`
ADD FOREIGN KEY (`gg_topic_id`) REFERENCES `engine4_gg_topics` (`topic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;



/*** my-upgrade 2018-05-19 ***/

UPDATE `engine4_core_settings` SET `value` = 'AIzaSyArhMEAv68P_uDYcRkiuTmab1XmaIxOUKo' WHERE `name` = 'seaocore.google.map.key';




/*** my-upgrade 2018-07-05 ***/

ALTER TABLE `engine4_sitereview_listings` ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_gg_topics` ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_core_comments` ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_ggcommunity_questions` ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_storage_files` ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_ggcommunity_answers` ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';


/***************my-upgrade-2018-07-04*****************/

ALTER TABLE `engine4_users`
ADD `gg_gender` int NOT NULL DEFAULT '3' AFTER `gg_following_count`,
ADD `gg_age_range` text NULL AFTER `gg_gender`,
ADD `gg_age_range_set` datetime NULL AFTER `gg_age_range`;

UPDATE `engine4_core_pages` 
SET `layout` = 'default-auth' WHERE `engine4_core_pages`.`page_id` = 9;

UPDATE `engine4_core_pages` 
SET `layout` = 'default-auth' WHERE `engine4_core_pages`.`page_id` = 11;

UPDATE `engine4_core_pages` 
SET `layout` = 'default-auth' WHERE `engine4_core_pages`.`page_id` = 12;

UPDATE `engine4_core_pages` 
SET `layout` = 'default-auth' WHERE `engine4_core_pages`.`page_id` = 13;


/***************my-upgrade-2018-07-13*****************/

ALTER TABLE `engine4_core_search`
ADD FULLTEXT `title_keywords_hidden` (`title`, `keywords`, `hidden`);

ALTER TABLE `engine4_core_search`
ADD `topic_id` int NULL DEFAULT '0',
ADD `creation_date` datetime NULL AFTER `topic_id`,
ADD `modified_date` datetime NULL AFTER `creation_date`;


/***************my-upgrade-2018-07-18*****************/



UPDATE engine4_user_membership SET creation_date = "2018-07-16 00:00:00";


/***************my-upgrade-2018-07-18*****************/


ALTER TABLE `engine4_users`
ADD `gg_answer_count` int NOT NULL DEFAULT 0 AFTER `gg_guide_count`;



/***************my-upgrade-2018-07-24*****************/


INSERT INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`, `is_grouped`, `is_object_thumb`, `editable`) VALUES
('question_answer',	'ggcommunity',	'{item:$subject} answered a question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_author_comment',	'ggcommunity',	'{item:$subject} commented on {item:$owner}\'s answer {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_author_vote',	'ggcommunity',	'{item:$subject} voted for {item:$owner}\'s answer {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_chosen',	'ggcommunity',	'{item:$subject} chosen {item:$owner}\'s answer  {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_comment',	'ggcommunity',	'{item:$subject} commented on an answer {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_vote',	'ggcommunity',	'{item:$subject} voted for an answer {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_author_answer',	'ggcommunity',	'{item:$subject} answered {item:$owner}\'s question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_author_comment',	'ggcommunity',	'{item:$subject} commented on {item:$owner}\'s question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_author_vote',	'ggcommunity',	'{item:$subject} voted for {item:$owner}\'s question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_comment',	'ggcommunity',	'{item:$subject} commented on a question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_create',	'ggcommunity',	'{item:$subject} asked a new question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_vote',	'ggcommunity',	'{item:$subject} voted for a question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0);