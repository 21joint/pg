/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Ahmad Raza
 * Created: Jan 26, 2018
 */

INSERT INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`, `processes`, `semaphore`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES
('User Credibility Maintenance',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Credibility',	300,	1,	0,	0,	0,	0,	0,	0,	0,	0,	0);

ALTER TABLE `engine4_users`
ADD `gg_contribution` int NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_activities` int(11) NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_contribution_updated` tinyint NULL DEFAULT '0';

ALTER TABLE `engine4_gg_topics`
ADD `custom` tinyint NULL DEFAULT '1';

UPDATE engine4_sitereview_listingtypes SET featured_color = '#ffd819';


