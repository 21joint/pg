/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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


DROP TABLE IF EXISTS `engine4_gg_assigned_badges`;
CREATE TABLE `engine4_gg_assigned_badges` (
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

ALTER TABLE `engine4_gg_assigned_badges`
ADD `assignedbadge_id` int NOT NULL AUTO_INCREMENT UNIQUE FIRST;
ALTER TABLE `engine4_gg_assigned_badges` DROP PRIMARY KEY, ADD PRIMARY KEY( `assignedbadge_id`);
ALTER TABLE `engine4_gg_assigned_badges` ADD `owner_id` int NULL DEFAULT '0';
ALTER TABLE `engine4_gg_badges` ADD `owner_id` int NULL DEFAULT '0';