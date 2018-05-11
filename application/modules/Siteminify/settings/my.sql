/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: my.sql 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
-- --------------------------------------------------------

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('siteminify', 'Minify Plugin - Speed up your Website', 'Minify Plugin - Speed up your Website', '4.9.2', 1, 'extra');

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_siteminify', 'siteminify', 'SEAO: Minify Plugin - Speed up your Website', '','{"route":"admin_default","module":"siteminify","controller":"settings","action":"readme"}','core_admin_main_plugins', '', 999),
('siteminify_admin_main_settings', 'siteminify', 'Global Settings', '','{"route":"admin_default","module":"siteminify","controller":"settings"}','siteminify_admin_main', '', 1),
('siteminify_admin_main_faq', 'siteminify', 'FAQ', '','{"route":"admin_default","module":"siteminify","controller":"settings","action":"faq"}','siteminify_admin_main', '', 999),
('siteminify_admin_main_guidline', 'siteminify', 'Guidline For Gzip compression', '','{"route":"admin_default","module":"siteminify","controller":"settings","action":"guidline"}','siteminify_admin_main', '', 100);

INSERT IGNORE INTO `engine4_core_settings`(`name`, `value`) VALUES
('siteminify.css.combine.eachrequest', '5'),
('siteminify.js.combine.eachrequest', '5');
