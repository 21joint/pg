/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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