/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: May 30, 2018
 */

UPDATE `engine4_core_menuitems` SET
`params` = '{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"search\",\"action\":\"activity\"}',
WHERE `name` = 'sdparentalguide_admin_main_search';

INSERT INTO `engine4_gg_tasks` (`title`, `module`, `plugin`, `per_page`, `enabled`) VALUES
('Recalculate Search Analytics',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_SearchAnalytics',	50,	1);

ALTER TABLE `engine4_gg_topics`
DROP `listingtype_id`,
DROP `category_id`,
DROP `subcategory_id`;

ALTER TABLE `engine4_gg_topics`
ADD `name_plural` varchar(128) COLLATE 'utf8_general_ci' NULL AFTER `name`;

UPDATE engine4_gg_topics SET name_plural = name;
DROP TABLE `engine4_gg_listing_topics`;