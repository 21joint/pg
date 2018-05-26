/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: May 26, 2018
 */

UPDATE `engine4_gg_tasks` SET
`enabled` = '1'
WHERE `plugin` = 'Sdparentalguide_Plugin_Task_Contribution';
UPDATE `engine4_gg_tasks` SET
`enabled` = '1'
WHERE `plugin` = 'Sdparentalguide_Plugin_Task_ContributionLevel';

ALTER TABLE `engine4_sitecredit_badges`
ADD `gg_contribution_level` int(11) NOT NULL DEFAULT '0',
ADD `gg_level_id` int(11) NOT NULL DEFAULT '0' AFTER `gg_contribution_level`;