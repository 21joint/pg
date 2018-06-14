/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jun 12, 2018
 */

ALTER TABLE `engine4_sitereview_listingtypes`
ADD FOREIGN KEY (`gg_topic_id`) REFERENCES `engine4_gg_topics` (`topic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION