<?php $db = Engine_Db_Table::getDefaultAdapter(); 
$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("sitegifplayer_admin_main_settings","sitegifplayer","Global Settings","", \'{"route":"admin_default","module":"sitegifplayer","controller":"settings","action":"index"}\', "sitegifplayer_admin_main","", "1", "0", "1"),
("sitegifplayer_admin_main_faq", "sitegifplayer","FAQ","",\'{"route":"admin_default","module":"sitegifplayer","controller":"settings","action":"faq"}\',"sitegifplayer_admin_main","", "1", "0", "5");');?>