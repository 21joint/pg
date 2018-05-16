/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Ahmad Raza
 * Created: May 16, 2018
 */



ALTER TABLE `engine4_users`
CHANGE `gg_credibility` `gg_contribution` int(11) NULL DEFAULT '0' AFTER `gg_ip_lastmodified`,
CHANGE `gg_credibility_updated` `gg_contribution_updated` tinyint(4) NULL DEFAULT '0' AFTER `gg_activities`;


ALTER TABLE `engine4_users`
ADD `gg_contribution_level` int NOT NULL DEFAULT '0',
ADD `gg_review_count` int NOT NULL DEFAULT '0' AFTER `gg_contribution_level`,
ADD `gg_question_count` int NOT NULL DEFAULT '0' AFTER `gg_review_count`,
ADD `gg_guide_count` int NOT NULL DEFAULT '0' AFTER `gg_question_count`,
ADD `gg_bronze_count` int NOT NULL DEFAULT '0' AFTER `gg_guide_count`,
ADD `gg_silver_count` int NOT NULL DEFAULT '0' AFTER `gg_bronze_count`,
ADD `gg_gold_count` int NOT NULL DEFAULT '0' AFTER `gg_silver_count`,
ADD `gg_platinum_count` int NOT NULL DEFAULT '0' AFTER `gg_gold_count`,
ADD `gg_expert_bronze_count` int NOT NULL DEFAULT '0' AFTER `gg_platinum_count`,
ADD `gg_expert_silver_count` int NOT NULL DEFAULT '0' AFTER `gg_expert_bronze_count`,
ADD `gg_expert_gold_count` int NOT NULL DEFAULT '0' AFTER `gg_expert_silver_count`,
ADD `gg_expert_platinum_count` int NOT NULL DEFAULT '0' AFTER `gg_expert_gold_count`,
ADD `gg_followers_count` int NOT NULL DEFAULT '0' AFTER `gg_expert_platinum_count`,
ADD `gg_following_count` int NOT NULL DEFAULT '0' AFTER `gg_followers_count`;

ALTER TABLE `engine4_users`
CHANGE `gg_expert` `gg_expert_count` int NOT NULL DEFAULT '0' AFTER `gg_mvp`;