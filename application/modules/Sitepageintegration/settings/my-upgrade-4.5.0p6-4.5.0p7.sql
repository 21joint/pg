INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sitepage_sitegroup_gutter_create', 'sitepage', 'Create New Group', 'Sitepage_Plugin_Menus', '', 'sitepage_gutter', '', 1, 0, 999),

('sitepage_document_gutter_create', 'sitepage', 'Post New Documents', 'Sitepage_Plugin_Menus', '{"route":"document_create", "class":"buttonlink item_icon_document","action":"create"}', 'sitepage_gutter', '', 1, 0, 999);

INSERT IGNORE INTO `engine4_sitepageintegration_mixsettings` (`module`, `resource_type`, `resource_id`, `item_title`, `enabled`) VALUES
('document', 'document_0', 'document_id', 'Documents', 0),
('sitegroup', 'sitegroup_group_0', 'group_id', 'Groups', 0);
