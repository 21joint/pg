--
-- Updating data for table `engine4_core_menuitems`
--
UPDATE `engine4_core_menuitems`
SET `params` = '{"route":"sitegroup_general","action":"home","icon":"fa-group"}'
WHERE `name` = 'core_main_sitegroup';

--
-- Change the Commentable & Shareable values
--
UPDATE engine4_activity_actiontypes SET commentable=3,shareable=3 WHERE type='comment_sitegroup_group' and module='sitegroup';