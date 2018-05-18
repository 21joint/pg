<?php $db = Engine_Db_Table::getDefaultAdapter(); 

	$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sitemetatag_admin_main_metatags", "sitemetatag", "Manage Meta Tags","",\'{"route":"admin_default","module":"sitemetatag","controller":"meta-tags","action":"manage" }\',"sitemetatag_admin_main","",20),
("sitemetatag_admin_main_widgetized", "sitemetatag", "Widgetized Pages","",\'{"route":"admin_default","module":"sitemetatag","controller":"meta-tags","action":"manage" }\',"sitemetatag_admin_main_metatags","", 10),
("sitemetatag_admin_main_nonwidgetized", "sitemetatag", "Non Widgetized Pages","",\'{"route":"admin_default","module":"sitemetatag","controller":"meta-tags","action":"non-widgetized" }\',"sitemetatag_admin_main_metatags","", 20);
');



?>