/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ALTER TABLE `engine4_gg_topics`
CHANGE `name` `name` varchar(128) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `topic_id`,
CHANGE `description` `description` longtext COLLATE 'utf8_unicode_ci' NULL AFTER `name`;