/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



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