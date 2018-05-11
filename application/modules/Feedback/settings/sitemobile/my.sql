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
('core_main_feedback', 'feedback', 'Feedback',  '', '{"route":"feedback_create"}', 'core_main', '', 985, 1, 1); 

INSERT IGNORE INTO `engine4_sitemobile_modules` (`name`, `visibility`, `integrated`, `enable_mobile`, `enable_tablet`) VALUES
('feedback', 1, 0, 0, 0);