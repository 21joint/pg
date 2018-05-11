INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sitepagereview_admin_main_dayitems', 'sitepagereview', 'Review of the Day', '', '{"route":"admin_default","module":"sitepagereview","controller":"settings", "action": "manage-day-items"}', 'sitepagereview_admin_main', '', 1, 0, 3);

DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = 'sitepagereview.comment.widgets' LIMIT 1;
DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = 'sitepagereview.recent.widgets' LIMIT 1;
DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = 'sitepagereview.like.widgets' LIMIT 1;
DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = 'sitepagereview.popular.widgets' LIMIT 1;
DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = 'sitepagereview.rate.widgets' LIMIT 1;

INSERT IGNORE INTO `engine4_seaocore_searchformsetting` (`module`, `name`, `display`, `order`, `label`)
VALUES ('sitepage', 'has_review', '1', '100', 'Only Pages With Reviews');