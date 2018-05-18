INSERT IGNORE INTO `engine4_sitepageintegration_mixsettings` (`module`, `resource_type`, `resource_id`, `item_title`, `enabled`) VALUES
('sitestoreproduct', 'sitestoreproduct_product_0', 'product_id', 'Products', 0);


INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sitepage_sitestoreproduct_gutter_create', 'sitepage', 'Create New Product', 'Sitepage_Plugin_Menus', '', 'sitepage_gutter', '', 1, 0, 999);
