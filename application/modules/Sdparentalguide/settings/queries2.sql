/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/***21 Jan 2018***/


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