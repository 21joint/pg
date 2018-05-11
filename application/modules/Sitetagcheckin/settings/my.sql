/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitetagcheckin
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: my.sql 6590 2012-08-20 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */





-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_modules`
--


INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES ('sitetagcheckin', 'Geo-Location, Geo-Tagging, Check-Ins & Proximity Search Plugin', 'Geo-Location, Geo-Tagging, Check-Ins & Proximity Search Plugin', '4.9.4', 1, 'extra');


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`,
`submenu`, `enabled`, `custom`, `order`) VALUES  
('sitetagcheckin_admin_main_settings','sitetagcheckin', 'General Settings', '','{"route":"admin_default","module":"sitetagcheckin","controller":"settings"}',
'sitetagcheckin_admin_main', '', '1', '0', '1'), 
('advancedactivity_admin_main_tagcheckin_faq', 'advancedactivity', 'Geo-Location Tagging, Check-Ins', '', '{"route":"admin_default","module":"sitetagcheckin","controller":"settings","action":"faq"}', 'advancedactivity_admin_main_faq', '', '1', '0', '5'),
( 'sitetagcheckin_admin_main_geotag','sitetagcheckin', 'Geo-Tagging', '',
'{"route":"admin_default","module":"sitetagcheckin","controller":"geotag","action":"index"}',
'sitetagcheckin_admin_main', '', '1', '0', '2'),
('sitetagcheckin_admin_main_checkin', 'sitetagcheckin', 'Check-Ins', '', '{"route":"admin_default","module":"sitetagcheckin","controller":"checkin","action":"index"}', 'sitetagcheckin_admin_main', '', '1', '0', '3'),
('sitetagcheckin_admin_manage_modules', 'sitetagcheckin', 'Manage Modules', '', '{"route":"admin_default","module":"sitetagcheckin","controller":"manage","action":"index"}', 'sitetagcheckin_admin_main', '', '1', '0', '4');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_sitetagcheckin_contents`
--

DROP TABLE IF EXISTS `engine4_sitetagcheckin_contents`;
CREATE TABLE IF NOT EXISTS `engine4_sitetagcheckin_contents` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `resource_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `resource_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `checkin_verb` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `checkedinto_verb` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `value` int(11) NOT NULL DEFAULT '1',
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`content_id`),
  UNIQUE KEY `resource_type` (`resource_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_sitetagcheckin_contents`
--

INSERT IGNORE INTO `engine4_sitetagcheckin_contents` (`module`, `resource_type`, `resource_id`, `value`, `default`, `enabled`) VALUES
('album', 'album', 'album_id', 1, 1, 1),
('blog', 'blog', 'blog_id', 1, 1, 1),
('classified', 'classified', 'classified_id', 1, 1, 1),
('event', 'event', 'event_id', 1, 1, 1),
('group', 'group', 'group_id', 1, 1, 1),
('forum', 'forum_topic', 'forum_id', 1, 1, 1),
('music', 'music_playlist', 'playlist_id', 1, 1, 1),
('poll', 'poll', 'poll_id', 1, 1, 1),
('video', 'video', 'video_id', 1, 1, 1),
('document', 'document', 'document_id', 1, 1, 1),
('list', 'list_listing', 'listing_id', 1, 1, 1),
('recipe', 'recipe', 'recipe_id', 1, 1, 1),
('sitepage', 'sitepage_page', 'page_id', 1, 1, 1),
('sitepagealbum', 'sitepage_album', 'album_id', 1, 1, 1),
('sitepagenote', 'sitepagenote_note', 'note_id', 1, 1, 1),
('sitepageevent', 'sitepageevent_event', 'event_id', 1, 1, 1),
('sitepagemusic', 'sitepagemusic_playlist', 'playlist_id', 1, 1, 1),
('sitepagediscussion', 'sitepage_topic', 'topic_id', 1, 1, 1),
('sitepagevideo', 'sitepagevideo_video', 'video_id', 1, 1, 1),
('sitepagepoll', 'sitepagepoll_poll', 'poll_id', 1, 1, 1),
('sitepagedocument', 'sitepagedocument_document', 'document_id', 1, 1, 1),
('sitepagereview', 'sitepagereview_review', 'review_id', 1, 1, 1),
('sitebusiness', 'sitebusiness_business', 'business_id', 1, 1, 1),
('sitebusinessalbum', 'sitebusiness_album', 'album_id', 1, 1, 1),
('sitebusinessnote', 'sitebusinessnote_note', 'note_id', 1, 1, 1),
('sitebusinessevent', 'sitebusinessevent_event', 'event_id', 1, 1, 1),
('sitebusinessmusic', 'sitebusinessmusic_playlist', 'playlist_id', 1, 1, 1),
('sitebusinessdiscussion', 'sitebusiness_topic', 'topic_id', 1, 1, 1),
('sitebusinessvideo', 'sitebusinessvideo_video', 'video_id', 1, 1, 1),
('sitebusinesspoll', 'sitebusinesspoll_poll', 'poll_id', 1, 1, 1),
('sitebusinessdocument', 'sitebusinessdocument_document', 'document_id', 1, 1, 1),
('sitebusinessreview', 'sitebusinessreview_review', 'review_id', 1, 1, 1);

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_settings`
--

INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES 
('sitetagcheckin.selectable', ''),
('sitetagcheckin.selectable.0', 'googleplaces'),
('sitetagcheckin.selectable.1', 'pages'),
('sitetagcheckin.selectable.2', 'businesses'),
('sitetagcheckin.dropdowntime', '1800'),
('sitetagcheckin.google.map.key',''),
('sitetagcheckin.status.update', 1),
('sitetagcheckin.tagged.location', 1),
('sitetagcheckin.tooltip.bgcolor', '#FFFFFF');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_sitetagcheckin_addlocations`
--

DROP TABLE IF EXISTS `engine4_sitetagcheckin_addlocations`;
CREATE TABLE IF NOT EXISTS `engine4_sitetagcheckin_addlocations` (
  `addlocation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `resource_type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `resource_id` int(11) NOT NULL,
  `item_type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `object_type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `object_id` int(11) NOT NULL,
  `params` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `event_date` datetime NOT NULL, 
  `location_id` int(11) DEFAULT NULL,
  `action_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
`current_checkin` tinyint(2) DEFAULT NULL,  
  PRIMARY KEY (`addlocation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_activity_actiontypes`
--

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`, `is_object_thumb`) VALUES
('sitetagcheckin_add_to_map', 'sitetagcheckin', '{item:$subject} was at {item:$object} {var:$event_date}: {body:$body}', 1, 7, 1, 1, 1, 1, 0),
('sitetagcheckin_location', 'sitetagcheckin', '{item:$subject} added a photo at - {var:$prefixadd} {var:$location}.', 1, 7, 1, 3, 1, 1, 0),
('sitetagcheckin_content', 'sitetagcheckin', '{item:$subject} has {var:$checked_into_verb} {item:$object}: {body:$body}', 1, 7, 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_activity_notificationtypes`
--

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
('sitetagcheckin_tagged', 'sitetagcheckin', '{item:$subject} has said that you were in {var:$location} in a {item:$object:$label} in which you were tagged.', 0, '', 1),
('sitetagcheckin_tagged_location', 'sitetagcheckin', '{var:$location} location has been added to a {item:$object:$label} in which you are tagged.', 0, '', 1);

--
-- Dumping data for table `engine4_activity_actiontypes`
--

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
('sitetagcheckin_checkin', 'sitetagcheckin', '{item:$subject} is ', 1, 5, 0, 1, 4, 1),
('sitetagcheckin_post', 'sitetagcheckin', '{actors:$subject:$object}:\r\n{body:$body}', 1, 7, 1, 1, 1, 0),
('sitetagcheckin_post_self', 'sitetagcheckin', '{item:$subject}\r\n{body:$body}', 1, 5, 1, 1, 1, 0),
('sitetagcheckin_status', 'sitetagcheckin', '{item:$subject}\r\n{body:$body}', 1, 5, 0, 1, 4, 0);




INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES ('sitetagcheckin_page_tagged', 'sitetagcheckin', '{item:$subject} mentioned your page with a {item:$object:$label}.', '0', '', '1');

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES ('sitetagcheckin_business_tagged', 'sitetagcheckin', '{item:$subject} mentioned your business with a {item:$object:$label}.', '0', '', '1');

DROP TABLE IF EXISTS `engine4_sitetagcheckin_profilemaps`;
CREATE TABLE IF NOT EXISTS `engine4_sitetagcheckin_profilemaps` (
  `profilemap_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `profile_type` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`profilemap_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sitetagcheckin_main_videolocation', 'sitetagcheckin', 'By Locations', '', '{"route":"sitetagcheckin_videobylocation"}', 'video_main', '', 0, 0, 2);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sitetagcheckin_main_albumlocation', 'sitetagcheckin', 'By Locations', '', '{"route":"sitetagcheckin_albumbylocation"}', 'album_main', '', 0, 0, 2);
