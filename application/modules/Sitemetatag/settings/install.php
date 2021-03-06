<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Installer.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitemetatag_Installer extends Engine_Package_Installer_Module {

    public function onPreinstall() {
        $PRODUCT_TYPE = 'sitemetatag';
        $PLUGIN_TITLE = 'Sitemetatag';
        $PLUGIN_VERSION = '4.9.4';
        $PLUGIN_CATEGORY = 'plugin';
        $PRODUCT_DESCRIPTION = 'Social Meta Tags Plugin';
        $PRODUCT_TITLE = 'Social Meta Tags Plugin – Open Graph for Facebook, Google+, Pinterest and Twitter Cards';
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
        $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='sitemetatag';");
        parent::onInstall();
    }
}
