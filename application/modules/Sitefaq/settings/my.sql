/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: my.sql 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

 
INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sitefaq', 'FAQs', 'FAQs', '4.9.4', 1, 'extra');

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sitefaq_main_home', 'sitefaq', 'FAQs Home', 'Sitefaq_Plugin_Menus::canViewSitefaqs', '{"route":"sitefaq_general","action":"home"}', 'sitefaq_main', '', 1, 0, 1),
('sitefaq_main_browse', 'sitefaq', 'Browse FAQs', 'Sitefaq_Plugin_Menus::canViewSitefaqs', '{"route":"sitefaq_general","action":"browse"}', 'sitefaq_main', '', 1, 0, 2),
('sitefaq_main_manage', 'sitefaq', 'My FAQs', 'Sitefaq_Plugin_Menus::canCreateSitefaqs', '{"route":"sitefaq_general","action":"manage"}', 'sitefaq_main', '', 1, 0, 3),
('sitefaq_main_create', 'sitefaq', 'Create New FAQ', 'Sitefaq_Plugin_Menus::canCreateSitefaqs', '{"route":"sitefaq_general","action":"create"}', 'sitefaq_main', '', 1, 0, 4),
('sitefaq_main_question', 'sitefaq', 'Ask Question', 'Sitefaq_Plugin_Menus::canAskQuestions', '{"class":"smoothbox", "route":"sitefaq_general","action":"question"}', 'sitefaq_main', '', 1, 0, 5);

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
('sitefaq_new', 'sitefaq', '{item:$subject} created a new FAQ:', 0, 5, 1, 3, 1, 1),
('comment_sitefaq_faq', 'sitefaq', '{item:$subject} commented on {item:$owner}''s {item:$object:FAQ}: {body:$body}', 0, 1, 1, 1, 1, 1);
--
-- Change the Commentable & Shareable values
--
UPDATE engine4_activity_actiontypes SET commentable=3,shareable=3 WHERE type = 'comment_sitefaq_faq' and module='sitefaq';
