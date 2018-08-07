/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Aug 6, 2018
 */

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_main_permission',	'sdparentalguide',	'Permission',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"permission\",\"action\":\"index\"}',	'sdparentalguide_admin_main',	'',	1,	0,	10);

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_permission_custom',	'sdparentalguide',	'Custom',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"permission\",\"action\":\"custom\"}',	'sdparentalguide_admin_permission',	'',	1,	0,	2),
('sdparentalguide_admin_permission_reviews',	'sdparentalguide',	'Reviews',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"permission\",\"action\":\"index\"}',	'sdparentalguide_admin_permission',	'',	1,	0,	1);

INSERT INTO `engine4_gg_tasks` (`title`, `module`, `plugin`, `per_page`, `enabled`) VALUES
('Purge Database',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_PurgeDatabase',	1,	1);