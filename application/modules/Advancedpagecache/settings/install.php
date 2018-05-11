<?php

class Advancedpagecache_Installer extends Engine_Package_Installer_Module {

    public function onPreinstall() {
        $PRODUCT_TYPE = 'advancedpagecache';
        $PLUGIN_TITLE = 'Advancedpagecache';
        $PLUGIN_VERSION = '4.9.3';
        $PLUGIN_CATEGORY = 'plugin';
        $PRODUCT_DESCRIPTION = 'Speed up website performance';
        $PRODUCT_TITLE = 'Page Cache Plugin - Speed up your Website';
        $_PRODUCT_FINAL_FILE = 0;
        $SocialEngineAddOns_version = '4.9.2';
        $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
        $is_file = @file_exists($file_path);
        if (empty($is_file)) {
            include APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/license3.php";
        } else {
            $db = $this->getDb();
            $select = new Zend_Db_Select($db);
            $select->from('engine4_core_modules')->where('name = ?', $PRODUCT_TYPE);
            $is_Mod = $select->query()->fetchObject();
            if (empty($is_Mod))
                include_once $file_path;
        }

        parent::onPreinstall();
    }

    function onInstall() {
        $db = $this->getDb();
        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`id`, `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES (NULL, "advancedpagecache_admin_main_tester", "advancedpagecache", "Speed Analyzer","", \'{"route":"admin_default","module":"advancedpagecache","controller":"page-caching","action":"tester"}\', "advancedpagecache_admin_main","", "1", "0", "4");');
        $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='advancedpagecache';");
        parent::onInstall();

        $db->query('UPDATE `engine4_core_menuitems` SET `label` = \'Manage Single User Caching\' WHERE `engine4_core_menuitems`.`name` = \'advancedpagecache_admin_main_full_page\';');
        $db->query('UPDATE `engine4_core_menuitems` SET `label` = \'Manage Multiple Users Caching\' WHERE `engine4_core_menuitems`.`name` = \'advancedpagecache_admin_main_partial\';');
        
    }

}
