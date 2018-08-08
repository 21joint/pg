/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Aug 8, 2018
 */

ALTER TABLE `engine4_ggcommunity_questions`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;


ALTER TABLE `engine4_ggcommunity_answers`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;


ALTER TABLE `engine4_core_comments`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;

ALTER TABLE `engine4_ggcommunity_votes`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;


ALTER TABLE `engine4_core_likes`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;


ALTER TABLE `engine4_nestedcomment_dislikes`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;


ALTER TABLE `engine4_gg_views`
ADD `gg_dt_created` datetime NULL,
ADD `gg_dt_lastmodified` datetime NULL AFTER `gg_dt_created`,
ADD `gg_user_created` int NOT NULL DEFAULT '0' AFTER `gg_dt_lastmodified`,
ADD `gg_user_lastmodified` int NOT NULL DEFAULT '0' AFTER `gg_user_created`,
ADD `gg_guid` varchar(128) NULL AFTER `gg_user_lastmodified`,
ADD `gg_ip_lastmodified` varbinary(16) NULL AFTER `gg_guid`;


INSERT INTO `engine4_gg_tasks` (`title`, `module`, `plugin`, `per_page`, `enabled`) VALUES
('Recalculate Answer Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Answers',	50,	1);