/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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
