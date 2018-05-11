UPDATE `engine4_core_menuitems` SET `params` = '{"route":"sitepageoffer_browse","action":"browse"}' WHERE `engine4_core_menuitems`.`name` = 'sitepage_main_offer' LIMIT 1 ;

UPDATE `engine4_core_pages` SET `name` = 'sitepageoffer_index_browse' WHERE `engine4_core_pages`.`name` ='sitepageoffer_index_offerlist' LIMIT 1 ;