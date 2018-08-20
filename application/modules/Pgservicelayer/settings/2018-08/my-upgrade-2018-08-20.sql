/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Aug 20, 2018
 */

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES 
('sdparentalguide_admin_search_customactivity', 'sdparentalguide', 'Site Activity', '', '{"route":"admin_default","module":"sdparentalguide","controller":"search","action":"customactivity"}', 'sdparentalguide_admin_main_search', NULL, '1', '0', '4');

ALTER TABLE `engine4_gg_site_activities` ADD `user_id` int NULL DEFAULT '0';