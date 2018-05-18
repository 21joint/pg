
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemobile
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: my.sql 6590 2013-06-03 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_sitemobile_menus`
--

INSERT IGNORE INTO `engine4_sitemobile_menus` (`id`, `name`, `type`, `title`, `order`) VALUES (NULL, 'sitepagemusic_profile', 'standard', 'Page Music Profile Options Menu', '999');

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_sitemobile_menuitems`
--

INSERT IGNORE INTO `engine4_sitemobile_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `custom`, `order`, `enable_mobile`, `enable_tablet`) VALUES 
('sitepage_main_music', 'sitepagemusic', 'Music', 'Sitepagemusic_Plugin_Menus::canViewMusics', '{"route":"sitepagemusic_browse","action":"browse"}', 'sitepage_main', '','0','130', '1', '1'),
('sitepagemusic_add', 'sitepagemusic', 'Upload Music', 'Sitepagemusic_Plugin_Menus', '', 'sitepagemusic_profile', NULL, '0', '2', '1', '1'),
('sitepagemusic_edit', 'sitepagemusic', 'Edit Playlist', 'Sitepagemusic_Plugin_Menus', '', 'sitepagemusic_profile', NULL, '0', '3', '1', '1'),
('sitepagemusic_delete', 'sitepagemusic', 'Delete Playlist', 'Sitepagemusic_Plugin_Menus', '', 'sitepagemusic_profile', NULL, '0', '4', '1', '1'),
('sitepagemusic_share', 'sitepagemusic', 'Share', 'Sitepagemusic_Plugin_Menus', '', 'sitepagemusic_profile', NULL, '0', '5', '1', '1'),
('sitepagemusic_report', 'sitepagemusic', 'Report', 'Sitepagemusic_Plugin_Menus', '', 'sitepagemusic_profile', NULL, '0', '6', '1', '1');

-- --------------------------------------------------------

INSERT IGNORE INTO `engine4_sitemobile_searchform` (`name`, `class`, `search_filed_name`, `params`, `script_render_file`, `action`) VALUES
('sitepagemusic_playlist_browse', 'Sitepagemusic_Form_Searchwidget', 'search_music', '', '', '');

INSERT IGNORE INTO `engine4_sitemobile_navigation` 
(`name`, `menu`, `subject_type`) VALUES
('sitepagemusic_playlist_view', 'sitepagemusic_profile', 'sitepagemusic_playlist');