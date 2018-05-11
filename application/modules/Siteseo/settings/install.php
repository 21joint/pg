<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Installer.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_Installer extends Engine_Package_Installer_Module {

    public function onPreinstall() {
        $PRODUCT_TYPE = 'siteseo';
        $PLUGIN_TITLE = 'Siteseo';
        $PLUGIN_VERSION = '4.9.4p3';
        $PLUGIN_CATEGORY = 'plugin';
        $PRODUCT_DESCRIPTION = 'Ultimate SEO / Sitemaps Plugin';
        $PRODUCT_TITLE = 'Ultimate SEO / Sitemaps Plugin';
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
        $this->updateSearchTable();
        $this->placeWidgets();
    }

    function onInstall() {
        $db = $this->getDb();
        $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='siteseo';");
        parent::onInstall();
    }

    //ADD META TITLE, META DESCRIPTION AND META KEYWORDS COLUMN TO CORE SEARCH TABLE
    public function updateSearchTable() {
        $db = $this->getDb();
        $columnsArray = array('title', 'description', 'keywords');
        $tableName = 'engine4_core_search';
        foreach ($columnsArray as $column) {
            $hasColumn = $db->query("SHOW COLUMNS FROM $tableName LIKE 'meta_$column';")->fetch();
            if(empty($hasColumn)) {
                $db->query("ALTER TABLE $tableName ADD COLUMN `meta_$column` varchar(255) ;");
                $db->query("UPDATE $tableName SET `meta_$column` = $column;");
            }
        }
    }

    public function placeWidgets() {
        $db = $this->getDb();

        // PLACE SCHEMA MARKUP WIDGET
        $select = new Zend_Db_Select($db);
        $select
        ->from('engine4_core_pages')
        ->where('name = ?', 'footer')
        ->limit(1);
        $info = $select->query()->fetch();

        if (empty($info)) 
            return false;

        $page_id = $info['page_id'];
        $select = new Zend_Db_Select($db);
        $select->from('engine4_core_content')
        ->where('name = ?', 'siteseo.schema-markup')
        ->where('page_id = ?', $page_id)
        ->where('type = ?', 'widget')
        ->limit(1);
        $info = $select->query()->fetch();

        if (!empty($info)) 
            return false;

        $select = new Zend_Db_Select($db);
        $select
        ->from('engine4_core_content')
        ->where('name = ?', 'main')
        ->where('page_id = ?', $page_id)
        ->where('type = ?', 'container')
        ->limit(1);
        $info = $select->query()->fetch();

        if ($info) {
            $container_id = $info['content_id'];
            // Insert content
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'siteseo.schema-markup',
                'page_id' => $page_id,
                'parent_content_id' => $container_id,
                'order' => 10,
                ));
        }
    }
}
