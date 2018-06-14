/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jun 11, 2018
 */

ALTER TABLE `engine4_sitereview_listingtypes`
ADD `gg_topic_id` int NULL DEFAULT '0';

ALTER TABLE `engine4_sitecredit_credits`
ADD `gg_topic_id` int NULL DEFAULT '0';

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
VALUES ('sdparentalguide_admin_main_contribution', 'sdparentalguide', 'Contribution', NULL, '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"contribution\",\"action\":\"index\"}', 'sdparentalguide_admin_main', NULL, '1', '0', '9');

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_main_contribution_members',	'sdparentalguide',	'Members By Topic',	NULL,	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"contribution\",\"action\":\"members\"}',	'sdparentalguide_admin_main_contribution',	NULL,	1,	0,	2),
('sdparentalguide_admin_main_contribution_transaction',	'sdparentalguide',	'All Transaction',	NULL,	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"contribution\",\"action\":\"index\"}',	'sdparentalguide_admin_main_contribution',	NULL,	1,	0,	1);

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_main_levels',	'sdparentalguide',	'Levels',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"level\",\"action\":\"manage\"}',	'sdparentalguide_admin_main',	'',	1,	0,	6);