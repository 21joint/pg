INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('sitepagereview_admin_main_level', 'sitepagereview', 'Member Level Settings', '', '{"route":"admin_default","module":"sitepagereview","controller":"settings","action":"level"}', 'sitepagereview_admin_main', '', 1);

INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'sitepagereview_review' as `type`,
    'create' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin', 'user');



INSERT IGNORE INTO `engine4_core_menuitems` ( `name` , `module` , `label` , `plugin` , `params` , `menu` , `submenu` , `enabled` , `order` )VALUES
 ('sitepage_main_review', 'sitepagereview', 'Reviews', 'Sitepagereview_Plugin_Menus::canViewReviews', '{"route":"sitepagereview_reviewlist","action":"reviewlist"}', 'sitepage_main', '', 1, '999');