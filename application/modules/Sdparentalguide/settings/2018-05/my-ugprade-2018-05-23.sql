/* Delete unsed Widgets */
DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = 111;
DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = 112;

/* Update */
UPDATE `engine4_core_content` SET `name` = 'sdparentalguide.header' WHERE `engine4_core_content`.`content_id` = 110;
UPDATE `engine4_core_content` SET `params` = '[\"\"]' WHERE `engine4_core_content`.`content_id` = 110;