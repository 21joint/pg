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

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('siteminify_admin_main_guidline', 'siteminify', 'Guidline For Gzip compression', '','{"route":"admin_default","module":"siteminify","controller":"settings","action":"guidline"}','siteminify_admin_main', '', 100)
;

INSERT IGNORE INTO `engine4_core_settings`(`name`, `value`) VALUES
('siteminify.css.combine.eachrequest', '5'),
('siteminify.js.combine.eachrequest', '5');
