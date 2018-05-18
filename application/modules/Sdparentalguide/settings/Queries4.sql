/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Ahmad Raza
 * Created: Feb 3, 2018
 */


ALTER TABLE `engine4_users`
DROP `gg_contribution`,
DROP `gg_activities`,
DROP `gg_contribution_updated`;


ALTER TABLE `engine4_users`
ADD `gg_contribution` int NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_activities` int(11) NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_contribution_updated` tinyint NULL DEFAULT '0';