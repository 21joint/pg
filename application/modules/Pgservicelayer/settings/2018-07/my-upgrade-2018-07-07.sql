/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jul 7, 2018
 */

ALTER TABLE `engine4_ggcommunity_questions` ADD `topic_id` int NOT NULL DEFAULT '0';

ALTER TABLE `engine4_sitereview_listings`
ADD `dislike_count` int(11) unsigned NOT NULL DEFAULT '0' AFTER `like_count`;

ALTER TABLE `engine4_core_comments`
ADD `dislike_count` int(11) unsigned NOT NULL DEFAULT '0' AFTER `like_count`;

ALTER TABLE `engine4_ggcommunity_votes`
ADD `modified_date` datetime NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `engine4_user_membership`
ADD `gg_deleted` tinyint NULL DEFAULT '0',
ADD `gg_guid` varchar(128) COLLATE 'utf8_general_ci' NULL AFTER `gg_deleted`,
ADD `creation_date` datetime NULL AFTER `gg_guid`;

ALTER TABLE `engine4_seaocore_follows`
ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_gg_search_activity`
ADD `source_url` text COLLATE 'utf8_general_ci' NULL AFTER `search_text`;