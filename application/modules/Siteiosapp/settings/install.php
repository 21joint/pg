<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    install.php 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteiosapp_Installer extends Engine_Package_Installer_Module {
    function onPreinstall() {
        $db = $this->getDb();

        $select = new Zend_Db_Select($db);
        $isAPIActivate = $select
                ->from('engine4_core_settings', 'value')
                ->where('name = ?', 'siteapi.isActivate')
                ->limit(1)
                ->query()
                ->fetchColumn();
        $isAPIActivate = !empty($isAPIActivate) ? $isAPIActivate : 0;

        $select = new Zend_Db_Select($db);
        $isAPIEnabled = $select
                ->from('engine4_core_modules', 'enabled')
                ->where('name = ?', 'siteapi')
                ->where('enabled = ?', 1)
                ->query()
                ->fetchColumn();

//        if (empty($isAPIEnabled)) {
//            $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
//            return $this->_error("<span style='color:red'>Note: You have not installed the '<a href='http://www.socialengineaddons.com/socialengine-rest-api-plugin' target='_blank'>SocialEngine REST API Plugin</a>' on your site yet. Please install it first before installing the 'iOS Mobile Application - iPhone and iPad'. <a href='" . $base_url . "/manage'>Click here</a> to go to Manage Packages.</span>");
//        } else {
        if (1) {
            if (empty($isAPIActivate)) {
                $core_final_url = '';
                $baseUrl = $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();
                $explode_base_url = explode("/", $baseUrl);
                foreach ($explode_base_url as $url_key) {
                    if ($url_key != 'install') {
                        $core_final_url .= $url_key . '/';
                    }
                }
//                return $this->_error("<span style='color:red'>Note: You have installed the 'SocialEngine REST API Plugin' but not activated it on your site yet. Please activate it first before installing the 'iOS Mobile Application - iPhone and iPad'.</span> <a href='" . 'http://' . $core_final_url . "admin/siteapi/settings/readme'>Click here</a> to activate the 'SocialEngine REST API Plugin'.");
            }

            $getErrorMsg = $this->_getVersion();
            $getLatestVersion = $this->_isVersionCompatible(array('siteiosapp' => '4.8.12'));

            if (!empty($getLatestVersion)) {
                $schema = 'http://';
                if (!empty($_SERVER["HTTPS"]) && 'on' == strtolower($_SERVER["HTTPS"])) {
                    $schema = 'https://';
                }
                $getHost = $schema . $_SERVER['HTTP_HOST'];
                $baseParentUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
                $baseParentUrl = @trim($baseParentUrl, "/");
                $url = (!empty($baseParentUrl)) ? $getHost . DIRECTORY_SEPARATOR . $baseParentUrl . DIRECTORY_SEPARATOR . 'help/privacy' : $getHost . DIRECTORY_SEPARATOR . 'help/privacy';
                if (strstr($url, '/install/')) {
                    $url = str_replace('/install/', '/', $url);
                }
                $select = new Zend_Db_Select($db);
                $select->from('engine4_siteiosapp_menus')
                        ->where('name = ?', 'privacy_policy');
                $rowExists = $select->query()->fetchObject();
                if (isset($rowExists) && !empty($rowExists)) {
                    $db->query("UPDATE `engine4_siteiosapp_menus` SET `url` = '" . $url . "' WHERE `engine4_siteiosapp_menus`.`name` = 'privacy_policy'");
                }

                $url = (!empty($baseParentUrl)) ? $getHost . DIRECTORY_SEPARATOR . $baseParentUrl . DIRECTORY_SEPARATOR . 'help/terms' : $getHost . DIRECTORY_SEPARATOR . 'help/terms';
                if (strstr($url, '/install/')) {
                    $url = str_replace('/install/', '/', $url);
                }
                $select = new Zend_Db_Select($db);
                $select->from('engine4_siteiosapp_menus')
                        ->where('name = ?', 'terms_of_service');
                $rowExists = $select->query()->fetchObject();
                if (isset($rowExists) && !empty($rowExists)) {
                    $db->query(" UPDATE `engine4_siteiosapp_menus` SET `url` = '" . $url . "' WHERE `engine4_siteiosapp_menus`.`name` = 'terms_of_service'");
                }
            }

            if (!empty($getErrorMsg)) {
                return $this->_error($getErrorMsg);
            }

            //CHECK THAT ADVANCED ACTIVITY FEED PLUGIN IS ACTIVATED OR NOT
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_core_settings')
                    ->where('name = ?', 'advancedactivity.navi.auth')
                    ->limit(1);
            $isAAFActivate = $select->query()->fetchAll();
            $flagAAFActivate = !empty($isAAFActivate) ? $isAAFActivate[0]['value'] : 0;

            //CHECK THAT ADVANCED ACTIVITY PLUGIN IS INSTALLED OR NOT
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_core_modules')
                    ->where('name = ?', 'advancedactivity')
                    ->where('enabled = ?', 1);
            $isAAFInstalled = $select->query()->fetchObject();
            if (!empty($isAAFInstalled) && !empty($flagAAFActivate)) {
                $PRODUCT_TYPE = 'siteiosapp';
                $PLUGIN_TITLE = 'Siteiosapp';
                $PLUGIN_VERSION = '4.9.4p9';
                $PLUGIN_CATEGORY = 'plugin';
                $PRODUCT_DESCRIPTION = 'iOS Mobile Application - iPhone and iPad';
                $PRODUCT_TITLE = 'iOS Mobile Application - iPhone and iPad';
                $_PRODUCT_FINAL_FILE = 0;
                $SocialEngineAddOns_version = '4.8.9p14';
                $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
                $is_file = file_exists($file_path);
                if (empty($is_file)) {
                    include APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/license3.php";
                } else {
                    $db = $this->getDb();
                    $select = new Zend_Db_Select($db);
                    $select->from('engine4_core_modules')->where('name = ?', $PRODUCT_TYPE);
                    $is_Mod = $select->query()->fetchObject();
                    if (empty($is_Mod)) {
                        include_once $file_path;
                    }
                }
                parent::onPreinstall();
            } elseif (!empty($isAAFInstalled) && empty($flagAAFActivate)) {
                $baseUrl = $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();
                $url_string = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
                if (strstr($url_string, "manage/install")) {
                    $calling_from = 'install';
                } else if (strstr($url_string, "manage/query")) {
                    $calling_from = 'queary';
                }
                $explode_base_url = explode("/", $baseUrl);
                foreach ($explode_base_url as $url_key) {
                    if ($url_key != 'install') {
                        $core_final_url .= $url_key . '/';
                    }
                }

                return $this->_error("<span style='color:red'>Note: You have installed the Advanced Activity Feeds / Wall Plugin but not activated it on your site yet. Please activate it first before installing the iOS Mobile Application - iPhone and iPad.</span><br/><a href='" . 'http://' . $core_final_url . "admin/advancedactivity/settings/readme'>Click here</a> to activate the Advanced Activity Feeds / Wall Plugin.");
            } else {
                $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
                return $this->_error("The \"iOS Mobile Application - iPhone and iPad\" is dependant on our \"<a href='http://www.socialengineaddons.com/socialengine-advanced-activity-feeds-wall-plugin' target='_blank'>Advanced Activity Feeds / Wall Plugin</a>\". So please install this plugin before installing the \"iOS Mobile Application - iPhone and iPad\"");
            }
        }
    }

    public function onInstall() {
        $db = $this->getDb();

        $select = new Zend_Db_Select($db);
        $select->from('engine4_core_menuitems')
                ->where('name = ?', 'siteiosapp_admin_api_sitereview_views')
                ->where('plugin = ?', 'Siteapi_Plugin_Menus::mltMapping');
        $rowExists = $select->query()->fetchObject();
        if (isset($rowExists) && !empty($rowExists)) {
            $db->query("UPDATE `engine4_core_menuitems` SET `plugin` = 'Siteiosapp_Plugin_Menus::mltMapping' WHERE `engine4_core_menuitems`.`name` = 'siteiosapp_admin_api_sitereview_views'");
        }

        $this->_updateAppCreationProcessTabs();

        // Rename "app-builder" directory to "ios-HOST-app-builder". It needed for our client, who are going to upgrade this plugin and have old directory with "app-builder" name.
        $getWebsiteName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
        $websiteStr = str_replace(".", "-", $getWebsiteName);
        if (is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/ios-app-builder')) {
            @rename(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/ios-app-builder', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . 'ios-' . $websiteStr . '-app-builder');
        }

        if (is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/app-builder')) {
            @rename(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/app-builder', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . 'ios-' . $websiteStr . '-app-builder');
        }

        if (is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . 'ios-' . str_replace('www.', '', strtolower($_SERVER['HTTP_HOST'])) . '-app-builder')) {
            @rename(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . 'ios-' . str_replace('www.', '', strtolower($_SERVER['HTTP_HOST'])) . '-app-builder', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . 'ios-' . $websiteStr . '-app-builder');
        }

        $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='sitemobileiosapp';");

        $activitynotificationtypesTable = $db->query('SHOW TABLES LIKE \'engine4_activity_notificationtypes\'')->fetch();
        if (!empty($activitynotificationtypesTable)) {
            $enable_push = $db->query("SHOW COLUMNS FROM engine4_activity_notificationtypes LIKE 'siteiosapp_enable_push'")->fetch();
            if (empty($enable_push)) {
                $db->query("ALTER TABLE `engine4_activity_notificationtypes` ADD `siteiosapp_enable_push` TINYINT( 1 ) NOT NULL DEFAULT '1';");
            }

            $ios_pushtype = $db->query("SHOW COLUMNS FROM engine4_activity_notificationtypes LIKE 'siteiosapp_pushtype'")->fetch();
            if (empty($ios_pushtype)) {
                $db->query("ALTER TABLE `engine4_activity_notificationtypes` ADD `siteiosapp_pushtype` TINYINT( 4 ) NOT NULL DEFAULT '7';");
            }
        }

        $db->query("UPDATE  `engine4_activity_notificationtypes` SET  `siteiosapp_enable_push` =  '1' WHERE  `type` IN ('" . join("','", self::_getDefaultEnablePushNotification()) . "')");


        $this->_addMultipleListingViewMappingTabs();
        $this->_addIosSubcribersTabs();
        //Advanced Event Dashboard Query
        $select = new Zend_Db_Select($db);
        $isAdvancedEventRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "core_main_siteevent")
                ->limit(1)
                ->query()
                ->fetchColumn();
        //CHECK THAT ADVANCED EVENT PLUGIN IS INSTALLED OR NOT
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'siteevent')
                ->where('enabled = ?', 1);
        $isSiteeventModuleEnabled = $select->query()->fetchObject();
        if (empty($isAdvancedEventRowExist) && $isSiteeventModuleEnabled)
            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`) VALUES ("core_main_siteevent", "Advanced Events", "Advanced Events", "siteevent", NULL, NULL, "both", "menu", "1", "1", "18")');


        //For Prime Messanger
//        
//         $select = new Zend_Db_Select($db);
//        $isprimemessangerRowExist = $select->from('engine4_siteiosapp_menus')
//                ->where('name = ?', "core_main_primemessenger")
//                ->limit(1)
//                ->query()
//                ->fetchColumn();
//        
//         $select = new Zend_Db_Select($db);
//        $select
//                ->from('engine4_core_modules')
//                ->where('name = ?', 'primemessenger')
//                ->where('enabled = ?', 1);
//        $isprimeMessangerModuleEnabled = $select->query()->fetchObject();
//        if (empty($isprimemessangerRowExist) && !empty($isprimeMessangerModuleEnabled))
//            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`) VALUES ("core_main_primemessenger", "Prime Messenger", "Prime Messenger", "primemessenger", NULL, NULL, "both", "menu", "1", "1", "18")');
//        


        $select = new Zend_Db_Select($db);
        $isdiariesRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "core_main_diaries")
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($isdiariesRowExist))
            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`,`params`) VALUES ("core_main_diaries", "Diaries", "Diary", "siteevent", NULL, NULL, "both", "menu", "1", "1", "35", \'a:1:{s:7:"version";s:5:"1.8.2";}\' )');


        // Insert Review Wishlist
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitereview')
                ->where('enabled = ?', 1);
        $isSitereviewEnabled = $select->query()->fetchObject();
        if (!empty($isSitereviewEnabled)) {
            $select = new Zend_Db_Select($db);
            $isWishlistRowExist = $select->from('engine4_siteiosapp_menus')
                    ->where('name = ?', "sitereview_wishlist")
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
            $select = new Zend_Db_Select($db);
            $coreWishlistRowExist = $select->from('engine4_siteiosapp_menus')
                    ->where('name = ?', "core_main_wishlist")
                    ->limit(1)
                    ->query()
                    ->fetchColumn();

            if (empty($isWishlistRowExist)) {
                if (empty($coreWishlistRowExist))
                    $db->query('INSERT INTO `engine4_siteiosapp_menus` (`menu_id`, `name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`) VALUES (NULL, "core_main_wishlist", "Wishlists", "Wishlists", "siteapi", NULL, NULL, "both", "menu", "0", "1", "7")');
            }
            elseif (empty($coreWishlistRowExist))
                $db->query('UPDATE `engine4_siteiosapp_menus` set name="core_main_wishlist" , module="siteapi" where name="sitereview_wishlist"');
        }



        //Advanced page plugin dashboard Query
        $select = new Zend_Db_Select($db);
        $isSitepageotRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "core_main_sitepage")
                ->limit(1)
                ->query()
                ->fetchColumn();
        //CHECK THAT ADVANCED Page PLUGIN IS INSTALLED OR NOT
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitepage')
                ->where('enabled = ?', 1);
        $isSitepageModuleEnabled = $select->query()->fetchObject();
        if (empty($isSitepageotRowExist) && $isSitepageModuleEnabled)
            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`) VALUES ("core_main_sitepage", "Directory / Pages", "Directory / Pages", "sitepage", NULL, NULL, "both", "menu", "1", "1", "19")');


        //Advanced Videos plugin dashboard Query
        $select = new Zend_Db_Select($db);
        $isSitevideotRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "core_main_sitevideo")
                ->limit(1)
                ->query()
                ->fetchColumn();
        //CHECK THAT ADVANCED Video PLUGIN IS INSTALLED OR NOT
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitevideo')
                ->where('enabled = ?', 1);
        $isSitevideoModuleEnabled = $select->query()->fetchObject();
        if (empty($isSitevideotRowExist) && $isSitevideoModuleEnabled)
            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`,`params`) VALUES ("core_main_sitevideo", "Advanced Videos", "Advanced Videos", "sitevideo", NULL, NULL, "both", "menu", "1", "1", "19",\'a:1:{s:7:"version";s:5:"1.8.4";}\')');


        //Advanced Channel plugin dashboard Query
        $select = new Zend_Db_Select($db);
        $isSitevideotRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "core_main_sitevideochannel")
                ->limit(1)
                ->query()
                ->fetchColumn();
        //CHECK THAT ADVANCED Video PLUGIN IS INSTALLED OR NOT
        $select = new Zend_Db_Select($db);
        if (empty($isSitevideotRowExist) && $isSitevideoModuleEnabled)
            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`,`params`) VALUES ("core_main_sitevideochannel", "Videos Channels", "Videos Channels", "sitevideo", NULL, NULL, "both", "menu", "1", "1", "19",\'a:1:{s:7:"version";s:5:"1.8.4";}\')');

        //Advanced Playlist plugin dashboard Query
        $select = new Zend_Db_Select($db);
        $isSitevideoRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "core_main_sitevideoplaylist")
                ->limit(1)
                ->query()
                ->fetchColumn();
        //CHECK THAT ADVANCED VIDEO PLUGIN IS INSTALLED OR NOT
        $select = new Zend_Db_Select($db);
        if (empty($isSitevideoRowExist) && $isSitevideoModuleEnabled)
            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`,`params`) VALUES ("core_main_sitevideoplaylist", "Videos Playlists", "Videos Playlists", "sitevideo", NULL, NULL, "both", "menu", "1", "1", "19",\'a:1:{s:7:"version";s:5:"1.8.4";}\')');



        // Store plugin dashboard Query
        $select = new Zend_Db_Select($db);
        $isSitegroupRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "core_main_sitestore")
                ->limit(1)
                ->query()
                ->fetchColumn();
        // CHECK THAT GROUP PLUGIN IS INSTALLED OR NOT
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitestore')
                ->where('enabled = ?', 1);
        $isSitegroupModuleEnabled = $select->query()->fetchObject();

        if (empty($isSitegroupRowExist) && $isSitegroupModuleEnabled) {
            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`, `params`) VALUES ("core_main_sitestore", "Store / Marketplace", "Store / Marketplace", "sitestore", NULL, NULL, "both", "menu", "1", "1", "11", \'a:1:{s:7:"version";s:5:"1.6.6";}\')');

            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`, `params`) VALUES ("core_main_sitestoreproduct", "Store / Products", "Store / Products", "sitestoreproduct", NULL, NULL, "both", "menu", "1", "1", "11", \'a:1:{s:7:"version";s:5:"1.6.6";}\')');

            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`, `params`) VALUES ("core_main_sitestoreoffer", "Store / Coupons", "Store / Coupons", "sitestoreoffer", NULL, NULL, "both", "menu", "1", "1", "11", \'a:1:{s:7:"version";s:5:"1.6.6";}\')');

            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`, `params`) VALUES ("core_main_sitestoreproduct_cart", "Cart", "Cart", "sitestoreproduct", NULL, NULL, "both", "menu", "1", "1", "6", \'a:1:{s:7:"version";s:5:"1.6.6";}\')');

            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`, `params`) VALUES ("core_main_sitestoreproduct_orders", "My Store Orders", "My Store Orders", "sitestoreproduct", NULL, NULL, "both", "menu", "1", "1", "31", \'a:1:{s:7:"version";s:5:"1.6.6";}\')');
            $select = new Zend_Db_Select($db);
            $isWishlistRowExist = $select->from('engine4_siteiosapp_menus')
                    ->where('name = ?', "core_main_wishlist")
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
            if (empty($isWishlistRowExist))
                $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`, `params`) VALUES ("core_main_wishlist", "Wishlist", "Wishlist", "siteapi", NULL, NULL, "both", "menu", "1", "1", "6", \'a:1:{s:7:"version";s:5:"1.7.1";}\')');
        }

        // Group plugin dashboard Query
        $select = new Zend_Db_Select($db);
        $isSitegroupRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "core_main_sitegroup")
                ->limit(1)
                ->query()
                ->fetchColumn();
        // CHECK THAT GROUP PLUGIN IS INSTALLED OR NOT
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitegroup')
                ->where('enabled = ?', 1);
        $isSitegroupModuleEnabled = $select->query()->fetchObject();
        if (empty($isSitegroupRowExist) && $isSitegroupModuleEnabled)
            $db->query('INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`, `params`) VALUES ("core_main_sitegroup", "Groups / Communities", "Groups / Communities", "sitegroup", NULL, NULL, "both", "menu", "1", "1", "20", \'a:1:{s:7:"version";s:5:"1.7.8";}\')');

        //Add new people you may know tab in app creation 
        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_people\' LIMIT 1')->fetch();
        if (empty($isRowExist)) {
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_people", "siteiosapp", "People You May Know", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"8"}\', "siteiosapp_admin_appsetup_main", "", 7);');
        }

        // Store Ends
        parent::onInstall();
    }

    public function _getDefaultEnablePushNotification() {
        return array("commented_commented", "commented", "friend_accepted", "friend_request", "liked", "liked_commented", "message_new", "post_user", "shared", "tagged", "aaf_tagged", "event_accepted", "group_accepted", "group_approve", "siteevent_accepted", "siteevent_approve", "siteevent_editorreview", "siteevent_invite", "sitegroup_manageadmin", "sitegroup_suggested", "sitegroupmember_accepted", "sitegroupmember_approve", "sitegroupmember_invite", "sitegroup_addmember", "sitepage_contentlike", "sitepage_manageadmin", "sitepage_suggested", "sitepagemember_accepted", "sitepagemember_approve", "sitepagemember_invite", "sitepage_addmember", "follow_sitereview_wishlist", "sitereview_approved_review", "sitereview_editorreview", "sitereview_write_review", "sitestoreproduct_approved_review", "sitestoreproduct_editorreview", "sitestoreproduct_order_comment_from_buyer", "sitestoreproduct_order_comment_to_buyer", "sitestoreproduct_order_comment_to_store_admin", "sitestoreproduct_order_ship", "sitestoreproduct_order_status_admin_change", "sitestoreproduct_order_status_change", "sitestore-sitestoreproductwritereview", "sitestore_manageadmin", "sitestore_suggested", "video_processed");
    }

    private function _updateAppCreationProcessTabs() {
        $db = $this->getDb();

        // Add params column in menu table
        $menuTableObj = $db->query('SHOW TABLES LIKE \'engine4_siteiosapp_menus\'')->fetch();
        if (!empty($menuTableObj)) {
            $paramColumn = $db->query("SHOW COLUMNS FROM engine4_siteiosapp_menus LIKE 'params'")->fetch();
            if (empty($paramColumn)) {
                $db->query("ALTER TABLE `engine4_siteiosapp_menus` ADD `params` TEXT NULL;");
            }
        }

        if (!empty($menuTableObj)) {
            $colorColumn = $db->query("SHOW COLUMNS FROM engine4_siteandroidapp_menus LIKE 'siteiosapp_menucolor'")->fetch();
            if (empty($colorColumn)) {
                $db->query("ALTER TABLE `engine4_siteandroidapp_menus` ADD `siteiosapp_menucolor` TEXT NULL;");
            }
        }

        // Set "Cometchat" row in table
        $select = new Zend_Db_Select($db);
        $isCometChatRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('module = ?', "cometchat")
                ->limit(1)
                ->query()
                ->fetchColumn();
        if (empty($isCometChatRowExist))
            $db->query("INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`) VALUES ('', 'CometChat', 'CometChat', 'cometchat', 'f1d7', NULL, 'both', 'menu', '0', '1', '21');");

        // Set "Spread the World" row in table
        $select = new Zend_Db_Select($db);
        $isLanguageRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "spread_the_word")
                ->limit(1)
                ->query()
                ->fetchColumn();
        if (empty($isLanguageRowExist))
            $db->query("INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`) VALUES ('spread_the_word', 'Spread the word', 'Spread the word', NULL, 'f045', NULL, 'both', 'menu', '1', '1', '21');");

        // Set "Location" row in table
        $select = new Zend_Db_Select($db);
        $isLanguageRowExist = $select->from('engine4_siteiosapp_menus')
                ->where('name = ?', "seaocore_location")
                ->limit(1)
                ->query()
                ->fetchColumn();
        if (empty($isLanguageRowExist))
            $db->query("INSERT INTO `engine4_siteiosapp_menus` (`name`, `dashboard_label`, `header_label`, `module`, `icon`, `url`, `show`, `type`, `status`, `default`, `order`) VALUES ('seaocore_location', 'Choose Location', 'Choose Location', NULL, NULL, NULL, 'both', 'menu', '1', '1', '22');");

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_general_settings\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_general_settings", "siteiosapp", "App Submission Info", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"1"}\', "siteiosapp_admin_appsetup_main", "", 1);');

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_graphic_assets\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_graphic_assets", "siteiosapp", "Graphic Assets", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"2"}\', "siteiosapp_admin_appsetup_main", "", 2);');

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_dashboard_setting\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_dashboard_setting", "siteiosapp", "Dashboard Settings", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"3"}\', "siteiosapp_admin_appsetup_main", "", 3);');
        else {
            $db->query('UPDATE `engine4_core_menuitems` set `params`=\'{"tab":"3"}\' , `order`="3" where name="siteiosapp_admin_dashboard_setting"');
        }

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_splash_and_slideshows\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_splash_and_slideshows", "siteiosapp", "Introductory Slideshow", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"4"}\', "siteiosapp_admin_appsetup_main", "", 4);');
        else {
            $db->query('UPDATE `engine4_core_menuitems` set `params`=\'{"tab":"4"}\' , `order`="4" where name="siteiosapp_admin_splash_and_slideshows" ');
        }

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_language_assets\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_language_assets", "siteiosapp", "Language Assets", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"5"}\', "siteiosapp_admin_appsetup_main", "", 5);');
        else {
            $db->query('UPDATE `engine4_core_menuitems` set `params`=\'{"tab":"5"}\' , `order`="5" where name="siteiosapp_admin_language_assets" ');
        }

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_advertising\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_advertising", "siteiosapp", "Advertising", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"6"}\', "siteiosapp_admin_appsetup_main", "", 6);');
        else {
            $db->query('UPDATE `engine4_core_menuitems` set `params`=\'{"tab":"6"}\' , `order`="6" where name="siteiosapp_admin_advertising" ');
        }

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_download\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_download", "siteiosapp", "Download Tar", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"7"}\', "siteiosapp_admin_appsetup_main", "", 7);');
        else {
            $db->query('UPDATE `engine4_core_menuitems` set `params`=\'{"tab":"7"}\' , `order`="7" where name="siteiosapp_admin_download" ');
        }

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_advertising_type\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_advertising_type", "siteiosapp", "Advertisement Type", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"6","subTab":"1"}\', "siteiosapp_admin_advertising", "", 1);');

        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_advertising_modules\' LIMIT 1')->fetch();
        if (empty($isRowExist))
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_advertising_modules", "siteiosapp", "Advertisement Placement", \'Siteiosapp_Plugin_Menus::appBuildUrls\', \'{"tab":"6","subTab":"2"}\', "siteiosapp_admin_advertising", "", 2);');

        if (!empty($menuTableObj)) {
            $colorColumn = $db->query("SHOW COLUMNS FROM engine4_siteiosapp_menus LIKE 'siteiosapp_menucolor'")->fetch();
            if (empty($colorColumn)) {
                $db->query("ALTER TABLE `engine4_siteiosapp_menus` ADD `siteiosapp_menucolor` TEXT NULL;");
            }
        }
    }

    private function _getVersion() {

        $db = $this->getDb();

        $errorMsg = '';
        $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();

        $modArray = array(
            'siteapi' => '4.8.9p12'
        );

        $finalModules = array();
        foreach ($modArray as $key => $value) {
            $select = new Zend_Db_Select($db);
            $select->from('engine4_core_modules')
                    ->where('name = ?', "$key")
                    ->where('enabled = ?', 1);
            $isModEnabled = $select->query()->fetchObject();
            if (!empty($isModEnabled)) {
                $select = new Zend_Db_Select($db);
                $select->from('engine4_core_modules', array('title', 'version'))
                        ->where('name = ?', "$key")
                        ->where('enabled = ?', 1);
                $getModVersion = $select->query()->fetchObject();

//				$isModSupport = strcasecmp($getModVersion->version, $value);
                $running_version = $getModVersion->version;
                $product_version = $value;
                $shouldUpgrade = false;
                if (!empty($running_version) && !empty($product_version)) {
                    $temp_running_verion_2 = $temp_product_verion_2 = 0;
                    if (strstr($product_version, "p")) {
                        $temp_starting_product_version_array = @explode("p", $product_version);
                        $temp_product_verion_1 = $temp_starting_product_version_array[0];
                        $temp_product_verion_2 = $temp_starting_product_version_array[1];
                    } else {
                        $temp_product_verion_1 = $product_version;
                    }
                    $temp_product_verion_1 = @str_replace(".", "", $temp_product_verion_1);


                    if (strstr($running_version, "p")) {
                        $temp_starting_running_version_array = @explode("p", $running_version);
                        $temp_running_verion_1 = $temp_starting_running_version_array[0];
                        $temp_running_verion_2 = $temp_starting_running_version_array[1];
                    } else {
                        $temp_running_verion_1 = $running_version;
                    }
                    $temp_running_verion_1 = @str_replace(".", "", $temp_running_verion_1);


                    if (($temp_running_verion_1 < $temp_product_verion_1) || (($temp_running_verion_1 == $temp_product_verion_1) && ($temp_running_verion_2 < $temp_product_verion_2))) {
                        $shouldUpgrade = true;
                    }
                }

                if (!empty($shouldUpgrade)) {
                    $finalModules[$key] = $getModVersion->title;
                }
            }
        }

        foreach ($finalModules as $modArray) {
            $errorMsg .= '<div class="tip"><span style="background-color: #da5252;color:#FFFFFF;">Note: You do not have the latest version of the "' . $modArray . '". Please upgrade "' . $modArray . '" on your website to the latest version available in your SocialEngineAddOns Client Area to enable its integration with "' . $modArray . '".<br/> Please <a class="" href="' . $base_url . '/manage">Click here</a> to go Manage Packages.</span></div>';
        }

        return $errorMsg;
    }

    private function _addMultipleListingViewMappingTabs() {
        $db = Engine_Db_Table::getDefaultAdapter();
        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_api_sitereview_views\' LIMIT 1')->fetch();
        if (empty($isRowExist)) {
            $db->query('INSERT INTO `engine4_core_menuitems` (`id`, `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES (NULL, "siteiosapp_admin_api_sitereview_views", "siteiosapp", "Multiple Listing Type Layout", \'Siteiosapp_Plugin_Menus::mltMapping\', \'{"route":"admin_default","module":"siteiosapp","controller":"view-maps-listing-type", "action":"manage"}\', "siteiosapp_admin_main", NULL, "1", "0", "11")');

            $db->query("CREATE TABLE IF NOT EXISTS `engine4_siteiosapp_listingtypeViewMaps` (
  `listingtype_id` int(5) NOT NULL,  
  `profileView_id` int(11) NOT NULL,
  `browseView_id` int(11) NOT NULL,
  PRIMARY KEY (`listingtype_id`),
  KEY `profileView_id` (`profileView_id`),
  KEY `browseView_id` (`browseView_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
        }
    }

    private function _addIosSubcribersTabs() {
        $db = Engine_Db_Table::getDefaultAdapter();
        $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_app_subscribers\' LIMIT 1')->fetch();
        if (empty($isRowExist)) {
            $db->query('INSERT INTO `engine4_core_menuitems` (`id`, `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES (NULL, "siteiosapp_app_subscribers", "siteiosapp", "View User Subscriptions", NULL, \'{"route":"admin_default","module":"siteiosapp","controller":"ios-subscription", "action":"manage"}\', "siteiosapp_admin_main", NULL, "1", "0", "10")');

            $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_app_subscribers_manage\' LIMIT 1')->fetch();
            if (empty($isRowExist))
                $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_app_subscribers_manage", "siteiosapp", "User Subscriptions ",  NULL, \'{"route":"admin_default","module":"siteiosapp","controller":"ios-subscription", "action":"manage"}\',"siteiosapp_app_subscribers", "", 1);');

            $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_subscription_plans\' LIMIT 1')->fetch();
            if (empty($isRowExist))
                $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_subscription_plans", "siteiosapp", "Subscription Plans",  NULL, \'{"route":"admin_default","module":"siteiosapp","controller":"ios-subscription", "action":"ios-packages"}\',"siteiosapp_app_subscribers", "", 2);');

            $isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_subscription_faq\' LIMIT 1')->fetch();
            if (empty($isRowExist))
                $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteiosapp_admin_subscription_faq", "siteiosapp", "FAQs",  NULL, \'{"route":"admin_default","module":"siteiosapp","controller":"ios-subscription", "action":"faq"}\',"siteiosapp_app_subscribers", "", 3);');

            //@TODO Add device uuid
            $db->query('CREATE TABLE IF NOT EXISTS `engine4_siteiosapp_userSubscription` (
  `transaction_id` varchar(255) NOT NULL,  
  `user_id` int(11) unsigned NOT NULL,
  `email` varchar(128) NOT NULL,
  `displayname` varchar(128) NOT NULL default "",
  `package_id` int(11) unsigned NOT NULL,
  `device_uuid` varchar(255)  NOT NULL,
  `creation_date` datetime NOT NULL,
  `isSandbox` tinyint(4) NOT NULL default "0",
  `receipt` text NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
        }
    }

    private function _isVersionCompatible($modArray) {
        $db = $this->getDb();

        $errorMsg = '';
        $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();

        $finalModules = array();
        foreach ($modArray as $key => $value) {
            $select = new Zend_Db_Select($db);
            $select->from('engine4_core_modules')
                    ->where('name = ?', "$key")
                    ->where('enabled = ?', 1);
            $isModEnabled = $select->query()->fetchObject();
            if (!empty($isModEnabled)) {
                $select = new Zend_Db_Select($db);
                $select->from('engine4_core_modules', array('title', 'version'))
                        ->where('name = ?', "$key")
                        ->where('enabled = ?', 1);
                $getModVersion = $select->query()->fetchObject();

//				$isModSupport = strcasecmp($getModVersion->version, $value);
                $running_version = $getModVersion->version;
                $product_version = $value;
                $shouldUpgrade = false;
                if (!empty($running_version) && !empty($product_version)) {
                    $temp_running_verion_2 = $temp_product_verion_2 = 0;
                    if (strstr($product_version, "p")) {
                        $temp_starting_product_version_array = @explode("p", $product_version);
                        $temp_product_verion_1 = $temp_starting_product_version_array[0];
                        $temp_product_verion_2 = $temp_starting_product_version_array[1];
                    } else {
                        $temp_product_verion_1 = $product_version;
                    }
                    $temp_product_verion_1 = @str_replace(".", "", $temp_product_verion_1);


                    if (strstr($running_version, "p")) {
                        $temp_starting_running_version_array = @explode("p", $running_version);
                        $temp_running_verion_1 = $temp_starting_running_version_array[0];
                        $temp_running_verion_2 = $temp_starting_running_version_array[1];
                    } else {
                        $temp_running_verion_1 = $running_version;
                    }
                    $temp_running_verion_1 = @str_replace(".", "", $temp_running_verion_1);


                    if (($temp_running_verion_1 < $temp_product_verion_1) || (($temp_running_verion_1 == $temp_product_verion_1) && ($temp_running_verion_2 < $temp_product_verion_2))) {
                        $shouldUpgrade = true;
                    }
                }

                if (!empty($shouldUpgrade)) {
                    $finalModules[$key] = $getModVersion->title;
                }
            }
        }

        foreach ($finalModules as $modArray) {
            $errorMsg .= '<div class="tip"><span style="background-color: #da5252;color:#FFFFFF;">Note: You do not have the latest version of the "' . $modArray . '". Please upgrade "' . $modArray . '" on your website to the latest version available in your SocialEngineAddOns Client Area to enable its integration with "' . $modArray . '".<br/> Please <a class="" href="' . $base_url . '/manage">Click here</a> to go Manage Packages.</span></div>';
        }

        return $errorMsg;
    }

}
