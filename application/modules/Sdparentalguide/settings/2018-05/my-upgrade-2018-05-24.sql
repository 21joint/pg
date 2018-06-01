ALTER TABLE `engine4_users`
ADD `gg_reviews_count` int(11) NOT NULL DEFAULT '0';

ALTER TABLE `engine4_users`
ADD `gg_questions_count` int(11) NOT NULL DEFAULT '0';

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('sdparentalguide_admin_main_jobs',	'sdparentalguide',	'Admin Jobs',	'',	'{\"route\":\"admin_default\",\"module\":\"sdparentalguide\",\"controller\":\"manage\",\"action\":\"jobs\"}',	'sdparentalguide_admin_main',	'',	1,	0,	2);


UPDATE `engine4_core_menuitems` SET
`order` = '3'
WHERE `name` = 'sdparentalguide_admin_main_onboarding';

UPDATE `engine4_core_menuitems` SET
`order` = '4',
`label` = 'Listings'
WHERE `name` = 'sdparentalguide_admin_main_listings';

UPDATE `engine4_core_menuitems` SET
`order` = '5'
WHERE `name` = 'sdparentalguide_admin_main_badges';

UPDATE `engine4_core_menuitems` SET
`order` = '6',
`label` = 'Users'
WHERE `name` = 'sdparentalguide_admin_main_featuredusers';

UPDATE `engine4_core_menuitems` SET
`order` = '7'
WHERE `name` = 'sdparentalguide_admin_main_topics';

UPDATE `engine4_core_menuitems` SET
`order` = '8'
WHERE `name` = 'sdparentalguide_admin_main_search';


DROP TABLE IF EXISTS `engine4_gg_tasks`;
CREATE TABLE `engine4_gg_tasks` (
  `task_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `module` varchar(128) NOT NULL DEFAULT '',
  `plugin` varchar(128) NOT NULL,
  `per_page` int(11) unsigned NOT NULL DEFAULT '50',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `plugin` (`plugin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `engine4_gg_tasks` (`task_id`, `title`, `module`, `plugin`, `per_page`, `enabled`) VALUES
(1,	'Recalculate All Contribution Points',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Contribution',	50,	0),
(2,	'Recalculate All Following',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Following',	50,	1),
(3,	'Recalculate Contribution Level',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_ContributionLevel',	50,	0),
(4,	'Recalculate Review Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Reviews',	50,	1),
(5,	'Recalculate Question Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Questions',	50,	1),
(6,	'Recalculate Guide Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Guide',	50,	0),
(7,	'Recalculate Badge Count',	'Sdparentalguide',	'Sdparentalguide_Plugin_Task_Badges',	50,	1);


DELETE FROM `engine4_core_menuitems`
WHERE `menu` = 'sdparentalguide_admin_main' AND ((`name` = 'sdparentalguide_admin_main_onboarding'));