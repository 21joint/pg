/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: my.sql 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_modules`
--

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sitepagemusic', 'Directory / Pages - Music Extension', 'Directory / Pages - Music Extension', '4.9.4p1', 1, 'extra') ;

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_menuitems`
--
INSERT IGNORE INTO `engine4_core_menuitems` ( `name` , `module` , `label` , `plugin` , `params` , `menu` , `submenu` , `enabled` , `order` )VALUES
 ('sitepage_main_music', 'sitepagemusic', 'Music', 'Sitepagemusic_Plugin_Menus::canViewMusics', '{"route":"sitepagemusic_home","action":"home"}', 'sitepage_main', '', 1, '21');

--
-- Dumping data for table `engine4_seaocore_tabs`
--

INSERT IGNORE INTO `engine4_seaocore_tabs` (`module` ,`type` ,`name` ,`title` ,`enabled` ,`order` ,`limit`)VALUES
('sitepagemusic', 'musics', 'recent_pagemusics', 'Recent', '1', '1', '24'),
('sitepagemusic', 'musics', 'liked_pagemusics', 'Most Liked', '1', '2', '24'),
('sitepagemusic', 'musics', 'viewed_pagemusics', 'Most Viewed', '1', '3', '24'),
('sitepagemusic', 'musics', 'commented_pagemusics', 'Most Commented', '0', '4', '24'),
('sitepagemusic', 'musics', 'featured_pagemusics', 'Featured', '0', '5', '24'),
('sitepagemusic', 'musics', 'random_pagemusics', 'Random', '0', '6', '24');

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
('sitepagemusic_create', 'sitepagemusic', '{item:$subject} has created a page music {item:$object}.', 0, '');

INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
("SITEPAGEMUSIC_CREATENOTIFICATION_EMAIL", "sitepagemusic", "[host],[email],[recipient_title],[subject],[message],[template_header],[site_title],[template_footer]");