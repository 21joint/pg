/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jul 13, 2018
 */

ALTER TABLE `engine4_core_search`
ADD FULLTEXT `title_keywords_hidden` (`title`, `keywords`, `hidden`);

ALTER TABLE `engine4_core_search`
ADD `topic_id` int NULL DEFAULT '0',
ADD `creation_date` datetime NULL AFTER `topic_id`,
ADD `modified_date` datetime NULL AFTER `creation_date`;