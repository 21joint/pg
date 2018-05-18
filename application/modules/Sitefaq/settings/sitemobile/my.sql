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
INSERT IGNORE INTO `engine4_sitemobile_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`, `enable_mobile`, `enable_tablet`) VALUES
('core_main_sitefaq', 'sitefaq', 'FAQs',  'Sitefaq_Plugin_Menus::canViewSitefaqs', '{"route":"sitefaq_general","action":"home"}', 'core_main', '', 983, 1, 1); 

INSERT IGNORE INTO `engine4_sitemobile_modules` (`name`, `visibility`, `integrated`, `enable_mobile`, `enable_tablet`) VALUES
('sitefaq', 1, 0, 0, 0);

INSERT IGNORE INTO `engine4_sitemobile_menus` (`name`, `type`, `title`, `order`) VALUES 
('sitefaq_main', 'standard', 'FAQs Main Navigation Menu', '999');

INSERT IGNORE INTO `engine4_sitemobile_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`, `enable_mobile`, `enable_tablet`) VALUES
('sitefaq_main_home', 'sitefaq', 'FAQs Home', 'Sitefaq_Plugin_Menus::canViewSitefaqs', '{"route":"sitefaq_general","action":"home"}', 'sitefaq_main', '', 1, 1, 1),
('sitefaq_main_browse', 'sitefaq', 'Browse FAQs', 'Sitefaq_Plugin_Menus::canViewSitefaqs', '{"route":"sitefaq_general","action":"browse"}', 'sitefaq_main', '', 2, 1, 1),
('sitefaq_main_question', 'sitefaq', 'Ask Question', 'Sitefaq_Plugin_Menus::canAskQuestions', '{"class":"smoothbox", "route":"sitefaq_general","action":"question"}', 'sitefaq_main', '', 3, 1, 1);

INSERT IGNORE INTO `engine4_sitemobile_menus` (`name`, `type`, `title`, `order`) VALUES 
 ('sitefaq_gutter', 'standard', 'FAQ View Page Options Menu', '999');

INSERT IGNORE INTO `engine4_sitemobile_navigation` (`name`, `menu`, `subject_type`) VALUES
 ('sitefaq_index_view', 'sitefaq_gutter', 'sitefaq_faq');

INSERT IGNORE INTO `engine4_sitemobile_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `custom`, `order`, `enable_mobile`, `enable_tablet`) VALUES 
('sitefaq_gutter_share', 'sitefaq', 'Share', 'Sitefaq_Plugin_Menus::canShareSitefaqs', '', 'sitefaq_gutter', NULL, '0', '1', '1', '1');

INSERT IGNORE INTO `engine4_sitemobile_searchform` (`name`, `class`, `search_filed_name`, `params`, `script_render_file`, `action`) VALUES
('sitefaq_index_browse', 'Sitefaq_Form_Search', 'search', '', '', ''),
('sitefaq_index_home', 'Sitefaq_Form_Search', 'search', '', '', '');