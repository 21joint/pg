/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jun 25, 2018
 */

ALTER TABLE `engine4_sitereview_listings`
ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_gg_topics`
ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_core_comments`
ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';