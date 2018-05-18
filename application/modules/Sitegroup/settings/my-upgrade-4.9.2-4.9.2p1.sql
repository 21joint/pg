
-- added projects tab in the dashboard tabs for donation type backing

INSERT IGNORE INTO `engine4_core_menuitems` (`id`, `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) 
VALUES (NULL, 'sitegroup_dashboard_projects', 'sitegroup', 'Projects', 'Sitegroup_Plugin_Dashboardmenus', '{\"route\":\"sitegroup_dashboard\", \"action\":\"choose-project\"}', 'sitegroup_dashboard', '', '1', '0', '12');
