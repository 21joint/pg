
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    my.sql 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

-- 
-- 
-- -- --------------------------------------------------------
-- 
-- --
-- -- Dumping data for table `engine4_core_modules`
-- --
--


INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sitehashtag', 'Hashtags', 'Hashtags', '4.9.0', 1, 'extra') ;


DROP TABLE IF EXISTS `engine4_sitehashtag_tagmaps`;		
 CREATE TABLE IF NOT EXISTS `engine4_sitehashtag_tagmaps` (		
   `tagmap_id` int(11) unsigned NOT NULL AUTO_INCREMENT,		
   `tag_id` int(11) unsigned NOT NULL,		
   `action_id` int(11) NOT NULL,		
   `creation_date` datetime DEFAULT NULL,		
   PRIMARY KEY (`tagmap_id`)		
 ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;		
 
-- --		
-- --Create table tags		
-- --
	
 DROP TABLE IF EXISTS `engine4_sitehashtag_tags`;		
 CREATE TABLE IF NOT EXISTS `engine4_sitehashtag_tags` (		
   `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,		
   `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,		
   `tag_count` int(11) NOT NULL DEFAULT '0',		
   `modified_date` datetime DEFAULT NULL,		
   PRIMARY KEY (`tag_id`),		
   UNIQUE KEY `text` (`text`)		
 ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;		
 		
 		
 INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES		
('advancedactivity_admin_main_hashtag_faq', 'advancedactivity', 'Hashtags', '', '{"route":"admin_default","module":"sitehashtag","controller":"settings" ,"action":"faq"}', 'advancedactivity_admin_main_faq', '', 3),
('sitehashtag_admin_main_settings', 'sitehashtag', 'General Settings', '', '{"route":"admin_default","module":"sitehashtag","controller":"settings"}', 'sitehashtag_admin_main', '', 1),		
('sitehashtag_admin_main_manage', 'sitehashtag', 'Manage Modules', '', '{"route":"admin_default","module":"sitehashtag","controller":"manage"}', 'sitehashtag_admin_main', '', 2);		
 		
 		
 DROP TABLE IF EXISTS `engine4_sitehashtag_contents`;		
 CREATE TABLE IF NOT EXISTS `engine4_sitehashtag_contents` (		
   `content_id` int(11) NOT NULL AUTO_INCREMENT,		
   `module_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,		
   `resource_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,		
   `enabled` tinyint(1) NOT NULL DEFAULT '1',		
   PRIMARY KEY (`content_id`),		
   UNIQUE KEY `module_name` (`module_name`),		
   UNIQUE KEY `resource_type` (`resource_type`)		
 ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;		
 		

 		
 -- --Dumping data for table `engine4_sitehashtag_contents`		
 
 INSERT IGNORE INTO `engine4_sitehashtag_contents` (`module_name`, `resource_type`, `enabled`) VALUES
 ('sitepage', 'sitepage_page', 1),		
 ('sitebusiness', 'sitebusiness_business', 1),		
 ('sitegroup', 'sitegroup_group', 1),		
 ('siteevent', 'siteevent_event', 1),		
 ('sitestore', 'sitestore_store', 1),		
 ('document', 'document', 1),		
 ('recipe', 'recipe', 1),		
 ('list', 'list_listing', 1),		
 ('sitefaq', 'sitefaq_faq', 1),		
 ('sitetutorial', 'sitetutorial_tutorial', 1),		
 ('feedback', 'feedback', 1),		
 ('sitereview', 'sitereview_listing', 1),		
 ('album', 'album', 1),		
 ('music', 'music_playlist', 1),		
 ('video', 'video', 1),		
 ('blog', 'blog', 1),		
 ('group', 'group', 1),		
 ('event', 'event', 1),		
 ('classified', 'classified', 1),		
 ('forum', 'forum', 1),		
 ('poll', 'poll', 1),		
 ('advancedactivity', 'status', 1);

