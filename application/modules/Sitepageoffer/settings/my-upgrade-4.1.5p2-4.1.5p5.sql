-- --------------------------------------------------------

--
-- Table structure for table `engine4_sitepageoffer_photos`
--

DROP TABLE IF EXISTS `engine4_sitepageoffer_photos`;
CREATE TABLE IF NOT EXISTS `engine4_sitepageoffer_photos` (
  `photo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(11) unsigned NOT NULL,
  `offer_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `collection_id` int(11) unsigned NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`photo_id`),
  KEY `album_id` (`album_id`),
  KEY `sitepageoffer_id` (`offer_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_sitepageoffer_albums`
--

DROP TABLE IF EXISTS `engine4_sitepageoffer_albums`;
CREATE TABLE IF NOT EXISTS `engine4_sitepageoffer_albums` (
  `album_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `photo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `collectible_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`album_id`),
  KEY `sitepageoffer_id` (`offer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


-- -------------------------------------------------------- 

--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` ( `name` , `module` , `label` , `plugin` , `params` , `menu` , `submenu` , `enabled` , `order` )VALUES
 ('sitepage_main_offer', 'sitepageoffer', 'Page Offers', 'Sitepageoffer_Plugin_Menus::canViewOffers', '{"route":"sitepageoffer_offerlist","action":"offerlist"}', 'sitepage_main', '', 1, '18');

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_activity_actiontypes`
--

INSERT  IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
('sitepageoffer_admin_new', 'sitepageoffer', '{item:$subject} added a new offer:', 1, 1, 2, 1, 1, 1);


-- --------------------------------------------------------

DELETE FROM `engine4_activity_actiontypes` WHERE `engine4_activity_actiontypes`.`type` = 'sitepageoffer_new' ;

ALTER TABLE `engine4_sitepageoffer_offers` ADD `url` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `hotoffer`;

ALTER TABLE `engine4_sitepageoffer_offers` ADD `photo_id` INT( 11 ) NOT NULL AFTER `end_time`;

-- --------------------------------------------------------
