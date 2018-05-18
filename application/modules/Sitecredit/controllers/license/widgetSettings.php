<?php $db = Engine_Db_Table::getDefaultAdapter();

	$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES 
 ("sitecredit_admin_main_level", "sitecredit", "Member Level Settings", "", \'{"route":"admin_default","module":"sitecredit","controller":"level","action":"index"}\', "sitecredit_admin_main", "", "1", "0", "5"),
 ("sitecredit_admin_main_credit", "sitecredit", "Manage Credits", "", \'{"route":"admin_default","module":"sitecredit","controller":"credit"}\', "sitecredit_admin_main", "", "1", "0", "10"),
 ("sitecredit_admin_main_badge", "sitecredit", "Badges / Titles", "", \'{"route":"admin_default","module":"sitecredit","controller":"badge","action":"index"}\', "sitecredit_admin_main", "", "1", "0", "15"),
 ("sitecredit_admin_main_creditoffer", "sitecredit", "Credit Offers", "", \'{"route":"admin_default","module":"sitecredit","controller":"credit","action":"credit-offer"}\', "sitecredit_admin_main", "", "1", "0", "20"),
 ("sitecredit_admin_main_user", "sitecredit", "Send Credits", "", \'{"route":"admin_default","module":"sitecredit","controller":"user","action":"send"}\', "sitecredit_admin_main", "", "1", "0", "25"),
 ("sitecredit_admin_main_transaction", "sitecredit", "Credit Transactions", "", \'{"route":"admin_default","module":"sitecredit","controller":"transaction","action":"index"}\', "sitecredit_admin_main", "", "1", "0", "30"),
 ("sitecredit_admin_main_upgraderequest", "sitecredit", "Manage Upgrade Requests", "", \'{"route":"admin_default","module":"sitecredit","controller":"credit","action":"upgrade-request"}\', "sitecredit_admin_main", "", "1", "0", "35"),
 ("sitecredit_admin_main_module", "sitecredit", "Manage Modules", "", \'{"route":"admin_default","module":"sitecredit","controller":"module","action":"index"}\', "sitecredit_admin_main", "", "1", "0", "40"),
 ("sitecredit_admin_main_mail", "sitecredit", "Mail Templates", "", \'{"route":"admin_default","module":"core","controller":"mail","action":"templates"}\', "sitecredit_admin_main", "", "1", "0", "45"),
 ("sitecredit_admin_main_statistics", "sitecredit", "Statistics", "", \'{"route":"admin_default","module":"sitecredit","controller":"settings","action":"statistics"}\', "sitecredit_admin_main", "", "1", "0", "46"),
 ("sitecredit_admin_main_support", "sitecredit", "Support", "", \'{"route":"admin_default","module":"sitecredit","controller":"settings","action":"support"}\', "sitecredit_admin_main", "", "1", "0", "47"), 
 ("sitecredit_admin_global_instruction", "sitecredit", "User Guidelines", "", \'{"route":"admin_default","module":"sitecredit","controller":"settings","action":"instruction"}\', "sitecredit_admin_main_global", "", "1", "0", "2"),
 ("sitecredit_admin_credit_manage", "sitecredit", "Manage Credits / Activites", "", \'{"route":"admin_default","module":"sitecredit","controller":"credit","action":"manage"}\', "sitecredit_admin_main_credit", "", "1", "0", "3"),
 ("sitecredit_admin_credit_setcredits", "sitecredit", "Assign Credit Values", "", \'{"route":"admin_default","module":"sitecredit","controller":"credit","action":"index"}\', "sitecredit_admin_main_credit", "", "1", "0", "1"),
 ("sitecredit_admin_credit_upgradelevel", "sitecredit", "Member Level Credits", "", \'{"route":"admin_default","module":"sitecredit","controller":"credit","action":"upgrade-level"}\', "sitecredit_admin_main_credit", "", "1", "0", "2"),
 ("sitecredit_admin_badge_general", "sitecredit", "Badge Settings", "", \'{"route":"admin_default","module":"sitecredit","controller":"badge","action":"index"}\', "sitecredit_admin_main_badge", "", "1", "0", "1"),
 ("sitecredit_admin_badge_manage", "sitecredit", "Manage Badges", "", \'{"route":"admin_default","module":"sitecredit","controller":"badge","action":"manage"}\', "sitecredit_admin_main_badge", "", "1", "0", "2"),
 ("sitecredit_admin_main_transaction_allmembers", "sitecredit", "All Transactions", "", \'{"route":"admin_default","module":"sitecredit","controller":"transaction","action":"index"}\', "sitecredit_admin_main_transaction", "", "1", "0", "1"),
 ("sitecredit_admin_main_modulelist", "sitecredit", "Modules List", "", \'{"route":"admin_default","module":"sitecredit","controller":"module","action":"module-list"}\', "sitecredit_admin_main", "", "1", "0", "41"),
 ("sitecredit_admin_main_transaction_specificmembers", "sitecredit", "Members Credit Info", "", \'{"route":"admin_default","module":"sitecredit","controller":"user","action":"index"}\', "sitecredit_admin_main_transaction", "", "1", "0", "2");
');
	$db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES ("sitecredit_main", "standard", "Credits, Reward Points and Virtual Currency - User Engagement Main Navigation", "999");
 ');
	$db->query(' INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES 
 ("sitecredit_main_mycredit", "sitecredit", "My Credits", "", \'{"route":"credit_general"}\', "sitecredit_main", "", "1", "0", "1"),
 ("sitecredit_main_transaction", "sitecredit", "Transactions", "", \'{"route":"credit_general","action":"transaction"}\', "sitecredit_main", "", "1", "0", "2"),
 ("sitecredit_main_earn", "sitecredit", "Earn Credits", "", \'{"route":"credit_general","action":"earncredit"}\', "sitecredit_main", "", "1", "0", "3"),
 ("core_main_sitecredit", "sitecredit", "Credits", "Sitecredit_Plugin_Menus::allowedToViewCredits", \'{"route":"credit_general","icon":"fa-database"}\', "core_main", "", "1", "0", "6");');
 ?>