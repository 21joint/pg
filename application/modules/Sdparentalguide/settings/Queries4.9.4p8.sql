/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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

