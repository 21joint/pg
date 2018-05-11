INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'sitepageoffer_offer' as `type`,
    'comment' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin','user');


INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'sitepageoffer_offer' as `type`,
    'comment' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');


DROP TABLE IF EXISTS `engine4_sitepageoffer_claims`;
CREATE TABLE IF NOT EXISTS `engine4_sitepageoffer_claims` (
  `claim_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) unsigned NOT NULL,
  `page_id` int(11) unsigned NOT NULL,
  `offer_id` int(11) unsigned NOT NULL,
  `claim_value` tinyint(1) NOT NULL,
  PRIMARY KEY (`claim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`,  `body`,  `enabled`,  `displayable`,  `attachable`,  `commentable`,  `shareable`, `is_generated`) VALUES
('sitepageoffer_home', 'sitepageoffer', '{item:$subject} claimed {item:$object:an offer} from {itemParent:$object}:', '1', '4', '1', '3', '1', 0);

INSERT IGNORE INTO `engine4_seaocore_tabs` (`module` ,`type` ,`name` ,`title` ,`enabled` ,`order` ,`limit`)VALUES
('sitepageoffer', 'offers', 'recent_pageoffers', 'Recent', '1', '1', '24'),
('sitepageoffer', 'offers', 'liked_pageoffers', 'Most Liked', '1', '2', '24'),
('sitepageoffer', 'offers', 'viewed_pageoffers', 'Most Viewed', '1', '3', '24'),
('sitepageoffer', 'offers', 'commented_pageoffers', 'Most Commented', '0', '4', '24'),
('sitepageoffer', 'offers', 'featured_pageoffers', 'Featured', '0', '5', '24'),
('sitepageoffer', 'offers', 'hot_pageoffers', 'Hot', '0', '6', '24'),
('sitepageoffer', 'offers', 'popular_pageoffers', 'Popular', '0', '7', '24'),
('sitepageoffer', 'offers', 'random_pageoffers', 'Random', '0', '8', '24');

UPDATE `engine4_core_menuitems` SET `params` = '{"route":"sitepageoffer_home","action":"home"}' WHERE `engine4_core_menuitems`.`name` ='sitepage_main_offer' LIMIT 1 ;

UPDATE `engine4_core_menuitems` SET `order` = '6' WHERE `engine4_core_menuitems`.`name` = 'sitepageoffer_admin_main_faq' LIMIT 1 ;

UPDATE `engine4_sitepageoffer_offers` SET `claim_count` = '-1' WHERE `engine4_sitepageoffer_offers`.`claim_count` = '0';