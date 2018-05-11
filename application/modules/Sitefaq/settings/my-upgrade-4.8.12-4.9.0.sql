--
-- Updating data for table `engine4_core_menuitems`
--
UPDATE `engine4_core_menuitems`
SET `params` = '{"route":"sitefaq_general","action":"home","icon":"fa-question-circle-o"}'
WHERE `name` = 'core_main_sitefaq';
--
-- Change the Commentable & Shareable values
--
UPDATE engine4_activity_actiontypes SET commentable=3,shareable=3 WHERE type = 'comment_sitefaq_faq' and module='sitefaq';
