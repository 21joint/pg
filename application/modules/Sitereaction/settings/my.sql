
INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
('sitereaction', 'Reactions & Stickers Plugin', 'Reactions & Stickers Plugin', '4.9.4', 1, 'extra');

 INSERT IGNORE INTO `engine4_core_menuitems` ( `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES		
( 'advancedactivity_admin_main_reaction_faq', 'advancedactivity', 'Reactions & Stickers', '', '{"route":"admin_default","module":"sitereaction","controller":"faq"}', 'advancedactivity_admin_main_faq', '', 1, 0, 999),		
( 'sitereaction_admin_main_settings', 'sitereaction', 'General Settings', '', '{"route":"admin_default","module":"sitereaction","controller":"settings"}', 'sitereaction_admin_main', '', 1, 0, 1),		
 ( 'sitereaction_admin_main_collection', 'sitereaction', 'Manage Sticker Store', '', '{"route":"admin_default","module":"sitereaction","controller":"collection"}', 'sitereaction_admin_main', '', 1, 0, 3),		
 ( 'sitereaction_admin_main_reaction', 'sitereaction', 'Mange Reaction', '', '{"route":"admin_default","module":"sitereaction","controller":"reaction"}', 'sitereaction_admin_main', '', 1, 0, 2),		
 ('sitereaction_admin_main_searchlist', 'sitereaction', 'Manage Sticker Search', '', '{"route":"admin_default","module":"sitereaction","controller":"collection", "action":"manage-search" }', 'sitereaction_admin_main', '', 1, 0, 4)		
 ;		
 		
 		
  -- Table structure for table `engine4_sitereaction_reactionicons`		
 		
 		
 DROP TABLE IF EXISTS `engine4_sitereaction_reactionicons`;		
 CREATE TABLE `engine4_sitereaction_reactionicons` (		
   `reactionicon_id` int(11) unsigned NOT NULL AUTO_INCREMENT,		
   `title` varchar(128) NOT NULL,		
   `type` varchar(128) NOT NULL,		
   `photo_id` int(10) unsigned NOT NULL default '0',		
   `order` int(11) unsigned NOT NULL default '0',		
   PRIMARY KEY (`reactionicon_id`)		
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;		
 		
 		
 INSERT IGNORE INTO `engine4_sitereaction_reactionicons` (`title`, `type`, `order`) VALUES		
 ('Like', 'like', 1),		
 ('Love', 'love', 2),		
 ('WOW', 'wow', 3),		
 ('HaHa', 'haha', 4),		
 ('Sad', 'sad', 5),		
 ('Angry', 'angry', 6);		
 		
 		
  		
 		
 		
  -- Table structure for table `engine4_sitereaction_collections`		
 		
 		
 DROP TABLE IF EXISTS `engine4_sitereaction_collections`;		
 CREATE TABLE `engine4_sitereaction_collections` (		
   `collection_id` int(11) unsigned NOT NULL AUTO_INCREMENT,		
   `title` varchar(128) NOT NULL,		
   `body` varchar(255) NOT NULL,		
   `sticker_id` int(10) unsigned NOT NULL default '0',		
   `start_time` datetime NOT NULL,		
   `end_time` datetime NOT NULL,		
   `enabled` int(10) unsigned NOT NULL default '1',		
   `include` int(10) unsigned NOT NULL default '1',		
   `order` int(11) unsigned NOT NULL default '999',		
   PRIMARY KEY (`collection_id`)		
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;		
 		
 		
  		
 		
 		
  -- Table structure for table `engine4_sitereaction_stickers`		
 		
 		
 DROP TABLE IF EXISTS `engine4_sitereaction_stickers`;		
 CREATE TABLE `engine4_sitereaction_stickers` (		
   `sticker_id` int(11) unsigned NOT NULL AUTO_INCREMENT,		
   `title` VARCHAR(128) NULL DEFAULT NULL,		
   `collection_id` int(11) unsigned NOT NULL,		
   `file_id` int(10) unsigned NOT NULL default '0',		
   `order` int(11) unsigned NOT NULL default '999',		
   PRIMARY KEY (`sticker_id`)		
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;		
 		
 
  		
 		
  -- Table structure for table `engine4_sitereaction_stickersearch`		
 		
 		
 DROP TABLE IF EXISTS `engine4_sitereaction_stickersearch`;		
 CREATE TABLE `engine4_sitereaction_stickersearch` (		
   `stickersearch_id` int(11) NOT NULL AUTO_INCREMENT,		
   `title` varchar(64) NOT NULL,		
   `keyword` varchar(64) NOT NULL,		
   `background_color` varchar(16) NOT NULL,		
   `file_id` int(10) unsigned NOT NULL default '0',		
   `order` int(11) unsigned NOT NULL default '999',		
   PRIMARY KEY (`stickersearch_id`)		
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;		
 		
 		
  -- Dumping data for table `engine4_sitereaction_stickersearch`		
 		
 		
 INSERT IGNORE INTO `engine4_sitereaction_stickersearch` (`title`, `keyword`, `background_color`, `order`, `file_id`) VALUES		
 ('Happy', 'happy', '#FFCC00', 1, 0),		
 ('In Love', 'love', '#F64E88', 2, 0),		
 ('Sad', 'sad', '#A9A192', 3, 0),		
 ('Eating', 'eating', '#FC8A0F', 4, 0),		
 ('Celebrating', 'celebrating', '#95C63F', 5, 0),		
 ('Active', 'active', '#54C6FF', 6, 0),		
 ('Working', 'working', '#19B596', 7, 0),		
 ('Sleepy', 'sleepy', '#9571A3', 8, 0),		
 ('Angry', 'angry', '#ED513E', 9, 0),		
 ('Confused', 'confused', '#B37736', 10, 0);		
 		
  		
 		
 		
  -- Table structure for table `sitereaction_userscollections`		
 		
 DROP TABLE IF EXISTS `engine4_sitereaction_userscollections`;		
 CREATE TABLE `engine4_sitereaction_userscollections` (		
   `user_id` int(11) NOT NULL,		
   `collection_id` int(11) NOT NULL		
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;		
 		
 		
  -- Dumping data for table `engine4_activity_notificationtypes`		
 		
 		
 INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES		
 ('reacted', 'sitereaction', '{item:$subject} reacted on your {item:$object:$label}.', 0, '')		
 ;		
 		
  		
 		
 		
  -- Dumping data for table `engine4_core_mailtemplates`		
 		
 		
 INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES		
 ('notify_reacted', 'sitereaction', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]');		
 		
 		
 		
  -- Dumping data for table `engine4_activity_actiontypes`		
 		
 		
 INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`)		
 VALUES ('react', 'sitereaction', '{item:$subject} reacted on {item:$owner}''s {item:$object:$type}.', 1, 5, 1, 1, 1, 1);

