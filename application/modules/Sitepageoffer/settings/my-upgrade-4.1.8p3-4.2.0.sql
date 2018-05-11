 UPDATE `engine4_activity_actiontypes` SET `body` = '{item:$object} added a new offer:', `displayable` = '6',`is_object_thumb` = '1' WHERE `engine4_activity_actiontypes`.`type` = 'sitepageoffer_admin_new' LIMIT 1;


ALTER TABLE `engine4_sitepageoffer_offers` CHANGE `end_time` `end_time` DATETIME NULL DEFAULT NULL ;

UPDATE `engine4_core_menuitems` SET `label` = 'Offers' WHERE `engine4_core_menuitems`.`name`
='sitepage_main_offer' LIMIT 1 ;

UPDATE  `engine4_activity_actiontypes` SET  `is_generated` =  '1'
WHERE  `engine4_activity_actiontypes`.`type` =  'sitepageoffer_new';
UPDATE  `engine4_activity_actiontypes` SET  `is_generated` =  '1'
WHERE  `engine4_activity_actiontypes`.`type` =  'sitepageoffer_admin_new';
