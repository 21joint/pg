/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jul 12, 2018
 */

CREATE TABLE `engine4_gg_views` (
  `action_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `action_type` varchar(32) NOT NULL,
  `content_id` int NOT NULL,
  `conent_type` varchar(128) NOT NULL,
  `owner_id` int NOT NULL DEFAULT '0',
  `creation_date` datetime NULL
);

ALTER TABLE `engine4_users`
ADD `click_count` int(11) NOT NULL DEFAULT '0';

ALTER TABLE `engine4_sitereview_listings`
ADD `click_count` int(11) NOT NULL DEFAULT '0';

ALTER TABLE `engine4_ggcommunity_questions`
ADD `click_count` int(11) NOT NULL DEFAULT '0';

INSERT INTO `engine4_gg_tasks` (`title`, `module`, `plugin`, `per_page`, `enabled`) VALUES
('Recalculate Views For Reviews',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_CalReviewViews',	50,	1),
('Recalculate Clicks For Reviews',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_CalReviewClicks',	50,	1),
('Recalculate Views For Questions',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_CalQuestionViews',	50,	1),
('Recalculate Clicks For Questions',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_CalQuestionClicks',50,	1),
('Recalculate Views For Members',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_CalMemberViews',	50,	1),
('Recalculate Clicks For Members',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_CalMemberClicks',	50,	1);