
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

INSERT IGNORE INTO `engine4_sitemobile_menus` (`name`, `type`, `title`, `order`) VALUES ('sitepageoffer_profile', 'standard', 'Page Offer Profile Options Menu', '999');

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_sitemobile_menuitems`
--

INSERT IGNORE INTO `engine4_sitemobile_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `custom`, `order`, `enable_mobile`, `enable_tablet`) VALUES
('sitepage_main_offer', 'sitepageoffer', 'Offers', 'Sitepageoffer_Plugin_Menus::canViewOffers', '{"route":"sitepageoffer_browse","action":"browse"}', 'sitepage_main', '','0', '50', 1, 1),
('sitepageoffer_add', 'sitepageoffer', 'Add an Offer', 'Sitepageoffer_Plugin_Menus', '', 'sitepageoffer_profile', NULL, '0', '1', '1', '1'),
('sitepageoffer_edit', 'sitepageoffer', 'Edit Offer', 'Sitepageoffer_Plugin_Menus', '', 'sitepageoffer_profile', NULL, '0', '2', '1', '1'),
('sitepageoffer_delete', 'sitepageoffer', 'Delete Offer', 'Sitepageoffer_Plugin_Menus', '', 'sitepageoffer_profile', NULL, '0', '3', '1', '1'),
('sitepageoffer_share', 'sitepageoffer', 'Share', 'Sitepageoffer_Plugin_Menus', '', 'sitepageoffer_profile', NULL, '0', '5', '1', '1'),
('sitepageoffer_report', 'sitepageoffer', 'Report', 'Sitepageoffer_Plugin_Menus', '', 'sitepageoffer_profile', NULL, '0', '6', '1', '1');

-- --------------------------------------------------------

INSERT IGNORE INTO `engine4_sitemobile_searchform` (`name`, `class`, `search_filed_name`, `params`, `script_render_file`, `action`) VALUES
('sitepageoffer_index_browse', 'Sitepageoffer_Form_Search', 'search_offer', '', '', '');

INSERT IGNORE INTO `engine4_sitemobile_navigation` 
(`name`, `menu`, `subject_type`) VALUES 
('sitepageoffer_index_view', 'sitepageoffer_profile', 'sitepageoffer_offer');