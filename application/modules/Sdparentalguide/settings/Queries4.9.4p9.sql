/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('user_profile_familymembers',	'sdparentalguide',	'Family Members',	'Sdparentalguide_Plugin_Menus::editFamilyMembers',	'',	'user_profile',	'',	1,	0,	11);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('user_edit_familymembers',	'sdparentalguide',	'Family Members',	'Sdparentalguide_Plugin_Menus::editFamilyMembers',	'',	'user_edit',	'',	1,	0,	5);