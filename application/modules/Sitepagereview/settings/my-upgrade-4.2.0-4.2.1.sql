
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"sitepagereview_home","action":"home"}' WHERE `engine4_core_menuitems`.`name` = 'sitepage_main_review' LIMIT 1 ;

UPDATE `engine4_core_pages` SET `name` = 'sitepagereview_index_browse' WHERE `engine4_core_pages`.`name` ='sitepagereview_index_reviewlist' LIMIT 1 ;