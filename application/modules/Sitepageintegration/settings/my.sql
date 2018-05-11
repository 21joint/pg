INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sitepage_list_gutter_create', 'sitepage', 'Post a new Listing', 'Sitepage_Plugin_Menus', '{"route":"list_general", "class":"buttonlink item_icon_list_listing","action":"create"}', 'sitepage_gutter', '', 1, 0, 999),

('sitepage_document_gutter_create', 'sitepage', 'Post New Documents', 'Sitepage_Plugin_Menus', '{"route":"document_create", "class":"buttonlink item_icon_document","action":"create"}', 'sitepage_gutter', '', 1, 0, 999),

('sitepage_sitebusiness_gutter_create', 'sitepage', 'Create New Business', 'Sitepage_Plugin_Menus', '', 'sitepage_gutter', '', 1, 0, 999),

('sitepage_sitegroup_gutter_create', 'sitepage', 'Create New Group', 'Sitepage_Plugin_Menus', '', 'sitepage_gutter', '', 1, 0, 999),

('sitepage_sitereview_gutter_create_1', 'sitepage', 'Post New Product', 'Sitepage_Plugin_Menus::sitepagesitereviewGutterCreate', '{"route":"sitereview_general_listtype_1", "action":"create", "listing_id": "1", "class":"buttonlink item_icon_sitereview_listtype_1"}', 'sitepage_gutter', '', 1, 0, 999),

('sitepage_sitestoreproduct_gutter_create', 'sitepage', 'Create New Product', 'Sitepage_Plugin_Menus', '', 'sitepage_gutter', '', 1, 0, 999);


INSERT IGNORE INTO `engine4_sitepageintegration_mixsettings` (`module`, `resource_type`, `resource_id`, `item_title`, `enabled`) VALUES
('sitestoreproduct', 'sitestoreproduct_product_0', 'product_id', 'Products', 0);