/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ('core_admin_main_plugins_sdparentalguide', 'sdparentalguide', 'Guidance Guide Settings', '', '{"route":"admin_default","module":"sdparentalguide","controller":"manage"}', 'core_admin_main_plugins', '', '1', '0', '999');
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ('sdparentalguide_admin_main_manage', 'sdparentalguide', 'Global Settings', '', '{"route":"admin_default","module":"sdparentalguide","controller":"manage"}', 'sdparentalguide_admin_main', '', '1', '0', '1');
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ('sdparentalguide_admin_main_onboarding', 'sdparentalguide', 'Onboarding', '', '{"route":"admin_default","module":"sdparentalguide","controller":"manage","action":"onboarding"}', 'sdparentalguide_admin_main', '', '1', '0', '2');
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ('sdparentalguide_admin_main_listings', 'sdparentalguide', 'Listing Modifications', '', '{"route":"admin_default","module":"sdparentalguide","controller":"manage","action":"listings"}', 'sdparentalguide_admin_main', '', '1', '0', '3');
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ('sdparentalguide_admin_main_badges', 'sdparentalguide', 'Badge System', '', '{"route":"admin_default","module":"sdparentalguide","controller":"manage","action":"badges"}', 'sdparentalguide_admin_main', '', '1', '0', '4');
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ('sdparentalguide_admin_main_featuredusers', 'sdparentalguide', 'Featured Users', '', '{"route":"admin_default","module":"sdparentalguide","controller":"manage","action":"featured-users"}', 'sdparentalguide_admin_main', '', '1', '0', '5');


ALTER TABLE `engine4_users` ADD `gg_featured` TINYINT NOT NULL DEFAULT '0' ;

CREATE TABLE IF NOT EXISTS `engine4_gg_settings` (
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*22 Dec 2017*/

ALTER TABLE `engine4_sitereview_listings` ADD `gg_dt_created` DATETIME NULL , ADD `gg_dt_lastmodified` DATETIME NULL , ADD `gg_user_created` INT NOT NULL DEFAULT '0' , ADD `gg_user_lastmodified` INT NOT NULL DEFAULT '0' , ADD `gg_guid` INT NULL , ADD `gg_ip_lastmodified` INT NULL ;
ALTER TABLE `engine4_sitereview_listings` CHANGE `gg_ip_lastmodified` `gg_ip_lastmodified` VARBINARY(16) NULL DEFAULT NULL;
ALTER TABLE `engine4_sitereview_listings` CHANGE `gg_guid` `gg_guid` VARCHAR(32) NULL DEFAULT NULL;

ALTER TABLE `engine4_sitereview_listings`
ADD `gg_grading_complete` tinyint NULL DEFAULT '0',
ADD `gg_grading_picquality` tinyint NULL DEFAULT '0' AFTER `gg_grading_complete`,
ADD `gg_grading_picquantity` tinyint NULL DEFAULT '0' AFTER `gg_grading_picquality`,
ADD `gg_grading_description` tinyint NULL DEFAULT '0' AFTER `gg_grading_picquantity`,
ADD `gg_grading_grammar` tinyint NULL DEFAULT '0' AFTER `gg_grading_description`,
ADD `gg_grading_categorization` tinyint NULL DEFAULT '0' AFTER `gg_grading_grammar`,
ADD `gg_grading_disclosure` tinyint NULL DEFAULT '0' AFTER `gg_grading_categorization`,
ADD `gg_grading_safetyguidelines` tinyint NULL DEFAULT '0' AFTER `gg_grading_disclosure`,
ADD `gg_graded_by_comments` longtext NULL AFTER `gg_grading_safetyguidelines`,
ADD `gg_graded_by` int NULL DEFAULT '0' AFTER `gg_graded_by_comments`,
COMMENT='';



/***09 Jan 2018***/


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