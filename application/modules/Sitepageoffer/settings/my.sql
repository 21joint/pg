/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: my.sql 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` ( `name` , `module` , `label` , `plugin` , `params` , `menu` , `submenu` , `enabled` , `order` )VALUES
 ('sitepage_main_offer', 'sitepageoffer', 'Offers', 'Sitepageoffer_Plugin_Menus::canViewOffers', '{"route":"sitepageoffer_home","action":"home"}', 'sitepage_main', '', 1, '18');

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_modules`
--

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sitepageoffer', 'Page Offers', 'Sitepageoffer', '4.9.4p2', 1, 'extra') ;


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


INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
( 'offer_claim', 'sitepageoffer', '[host][email][template_header][message]][template_footer]');

--
-- Table structure for table `engine4_sitepageoffer_claims`
--

DROP TABLE IF EXISTS `engine4_sitepageoffer_claims`;
CREATE TABLE IF NOT EXISTS `engine4_sitepageoffer_claims` (
  `claim_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) unsigned NOT NULL,
  `page_id` int(11) unsigned NOT NULL,
  `offer_id` int(11) unsigned NOT NULL,
  `claim_value` tinyint(1) NOT NULL,
  PRIMARY KEY (`claim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `engine4_sitepageoffer_offers`;
CREATE TABLE IF NOT EXISTS `engine4_sitepageoffer_offers` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `title` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `claim_count` int(5) NOT NULL,
  `claimed` int(5) NOT NULL DEFAULT '0',
  `view_count` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL DEFAULT '0',
  `like_count` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `sticky` int(11) NOT NULL,
  `hotoffer` int(11) NOT NULL,
  `url` text CHARACTER SET utf8 NOT NULL,
  `coupon_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `end_settings` tinyint(2) NOT NULL,
  `end_time` datetime NOT NULL,
  `photo_id` int(11) NOT NULL,
  PRIMARY KEY (`offer_id`),
  KEY `page_id` (`page_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`,  `body`,  `enabled`,  `displayable`,  `attachable`,  `commentable`,  `shareable`, `is_generated`) VALUES
('sitepageoffer_home', 'sitepageoffer', '{item:$subject} claimed {item:$object:an offer} from {itemParent:$object}:', '1', '4', '1', '3', '1', 1);

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_seaocore_tabs`
--

INSERT IGNORE INTO `engine4_seaocore_tabs` (`module` ,`type` ,`name` ,`title` ,`enabled` ,`order` ,`limit`)VALUES
('sitepageoffer', 'offers', 'recent_pageoffers', 'Recent', '1', '1', '24'),
('sitepageoffer', 'offers', 'liked_pageoffers', 'Most Liked', '1', '2', '24'),
('sitepageoffer', 'offers', 'viewed_pageoffers', 'Most Viewed', '1', '3', '24'),
('sitepageoffer', 'offers', 'commented_pageoffers', 'Most Commented', '0', '4', '24'),
('sitepageoffer', 'offers', 'featured_pageoffers', 'Featured', '0', '5', '24'),
('sitepageoffer', 'offers', 'hot_pageoffers', 'Hot', '0', '6', '24'),
('sitepageoffer', 'offers', 'popular_pageoffers', 'Popular', '0', '7', '24'),
('sitepageoffer', 'offers', 'random_pageoffers', 'Random', '0', '8', '24');

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
('sitepageoffer_create', 'sitepageoffer', '{item:$subject} has created a page offer {item:$object}.', 0, '');


INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
("SITEPAGEOFFER_CREATENOTIFICATION_EMAIL", "sitepageoffer", "[host],[email],[recipient_title],[subject],[message],[template_header],[site_title],[template_footer]");