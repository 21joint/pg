
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

INSERT IGNORE INTO `engine4_sitemobile_menus` (`id`, `name`, `type`, `title`, `order`) VALUES (NULL, 'sitepagereview_profile', 'standard', 'Page Review Profile Options Menu', '999');

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_sitemobile_menuitems`
--

INSERT IGNORE INTO `engine4_sitemobile_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `custom`, `order`, `enable_mobile`, `enable_tablet`) VALUES 
('sitepage_main_review', 'sitepagereview', 'Reviews', 'Sitepagereview_Plugin_Menus::canViewReviews', '{"route":"sitepagereview_browse","action":"browse"}', 'sitepage_main', '', '0','60', 1, 1),
('sitepagereview_edit', 'sitepagereview', 'Edit Review', 'Sitepagereview_Plugin_Menus', '', 'sitepagereview_profile', NULL, '0', '1', '1', '1'),
('sitepagereview_delete', 'sitepagereview', 'Delete Review', 'Sitepagereview_Plugin_Menus', '', 'sitepagereview_profile', NULL, '0', '2', '1', '1'),
('sitepagereview_report', 'sitepagereview', 'Report', 'Sitepagereview_Plugin_Menus', '', 'sitepagereview_profile', NULL, '0', '3', '1', '1');

-- --------------------------------------------------------

INSERT IGNORE INTO `engine4_sitemobile_searchform` (`name`, `class`, `search_filed_name`, `params`, `script_render_file`, `action`) VALUES
('sitepagereview_index_browse', 'Sitepagereview_Form_Searchwidget', 'search_review', '', '', '');

INSERT IGNORE INTO `engine4_sitemobile_navigation` 
(`name`, `menu`, `subject_type`) VALUES
('sitepagereview_index_view', 'sitepagereview_profile', 'sitepagereview_review');