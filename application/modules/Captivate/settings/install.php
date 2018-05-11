<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Installer extends Engine_Package_Installer_Module {

    function onPreinstall() {
        $db = $this->getDb();
        $getErrorMsg = $this->_getVersion();
        if (!empty($getErrorMsg)) {
            return $this->_error($getErrorMsg);
        }

        $PRODUCT_TYPE = 'captivate';
        $PLUGIN_TITLE = 'Captivate';
        $PLUGIN_VERSION = '4.9.4p5';
        $PLUGIN_CATEGORY = 'plugin';
        $PRODUCT_DESCRIPTION = 'Responsive Captivate Theme';
        $PRODUCT_TITLE = 'Responsive Captivate Theme';
        $_PRODUCT_FINAL_FILE = 0;
        $SocialEngineAddOns_version = '4.8.12';
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
    }

    public function onInstall() {
        $db = $this->getDb();
        $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='captivate';");
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitemobile')
                ->where('enabled = ?', 1);
        $sitemobile = $select->query()->fetchObject();
        if (!empty($sitemobile)) {
            $db->query("UPDATE `engine4_core_modules` SET `enabled` = '0' WHERE `engine4_core_modules`.`name` = 'mobi';");
        }
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'captivate');
        $is_captivate_object = $select->query()->fetchObject();

        if (!empty($is_captivate_object)) {
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
    VALUES 
    ("captivate_admin_main_htmlblock", "captivate", "HTML Block", "", \'{"route":"admin_default","module":"captivate","controller":"html-block"}\', "captivate_admin_main", "", 1, 0, 10)
    ');
        }

        //CHECK THAT ALBUM PLUGIN IS INSTALLED OR NOT
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitemenu')
                ->where('enabled = ?', 1);
        $check_sitemenu = $select->query()->fetchObject();
        if (!empty($check_sitemenu)) {
            $db->query("INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('captivate_core_mini_admin', 'core', 'Admin', 'Captivate_Plugin_Menus', '', 'user_settings', '', 1, 0, 10),
( 'captivate_core_mini_auth', 'user', 'Sign Out', 'Captivate_Plugin_Menus', '', 'user_settings', '', 1, 0, 11),
( 'captivate_core_mini_signin', 'user', 'Sign In', 'Captivate_Plugin_Menus', '', 'core_mini', '', 1, 0, 12);
");
            $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '0' WHERE `engine4_core_menuitems`.`name` = 'core_mini_auth';");
            $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '0' WHERE `engine4_core_menuitems`.`name` = 'core_mini_admin';");

            //CHECK THAT ALBUM PLUGIN IS INSTALLED OR NOT
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_core_modules')
                    ->where('name = ?', 'siteeventticket')
                    ->where('enabled = ?', 1);
            $check_siteeventticket = $select->query()->fetchObject();
            if (!empty($check_siteeventticket)) {
                $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '0' WHERE `engine4_core_menuitems`.`name` = 'core_mini_siteeventticketmytickets';");

                $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("captivate_siteeventticket_main_ticket", "siteeventticket", "My Tickets", "Captivate_Plugin_Menus", \'{"route":"siteeventticket_order", "action":"my-tickets"}\', "user_settings", "", 1, 0, 9)');
            }
        }



        $this->_createCustomizationFileInTheme("captivate");
        $this->updatePage('core_help_terms', 'Trust But Verify', 'We Believe on you so You Can');
        $this->updatePage('core_help_contact', 'We Cherish Interactions', 'Because We Know Your Time Is Precious So, Get In Touch Now..');
        parent::onInstall();
    }

    private function _createCustomizationFileInTheme($themeName) {
        $global_directory_name = APPLICATION_PATH . '/application/themes/' . $themeName;
        @chmod($global_directory_name, 0777);

        if (!is_readable($global_directory_name)) {
            return $this->_error("<span style='color:red'>Note: You do not have readable permission on the path below, please give 'chmod 777 recursive permission' on it to continue with the installation process : <br /> 
  Path Name: <b>" . $global_directory_name . "</b></span>");
        }

        $global_settings_file = $global_directory_name . '/customization.css';
        $is_file_exist = @file_exists($global_settings_file);
        if (empty($is_file_exist)) {
            @chmod($global_directory_name, 0777);
            if (!is_writable($global_directory_name)) {
                return $this->_error("<span style='color:red'>Note: You do not have writable permission on the path below, please give 'chmod 777 recursive permission' on it to continue with the installation process : <br /> 
  Path Name: " . $global_directory_name . "</span>");
            }

            $fh = @fopen($global_settings_file, 'w');
            @fwrite($fh, '/* ADD CUSTOM STYLE */');
            @fclose($fh);

            @chmod($global_settings_file, 0777);
        }
    }

    private function _getVersion() {
        $db = $this->getDb();
        $errorMsg = '';
        $finalModules = $getResultArray = array();
        $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
        $modArray = array(
            'sitecontentcoverphoto' => '4.8.10p4',
            'sitehomepagevideo' => '4.8.10p1',
            'sitemenu' => '4.8.10p1',
            'siteusercoverphoto' => '4.8.10p4',
        );
        foreach ($modArray as $key => $value) {
            $isMod = $db->query("SELECT * FROM  `engine4_core_modules` WHERE  `name` LIKE  '" . $key . "'")->fetch();
            if (!empty($isMod) && !empty($isMod['version'])) {
                $isModSupport = $this->checkVersion($isMod['version'], $value);
                if (empty($isModSupport)) {
                    $finalModules['modName'] = $key;
                    $finalModules['title'] = $isMod['title'];
                    $finalModules['versionRequired'] = $value;
                    $finalModules['versionUse'] = $isMod['version'];
                    $getResultArray[] = $finalModules;
                }
            }
        }
        foreach ($getResultArray as $modArray) {
            $errorMsg .= '<div class="tip"><span>Note: Your website does not have the latest version of "' . $modArray['title'] . '". Please upgrade "' . $modArray['title'] . '" on your website to the latest version available in your SocialEngineAddOns Client Area to enable its integration with "Responsive Captivate Theme".<br/> Please <a href="' . $base_url . '/manage">Click here</a> to go Manage Packages.</span></div>';
        }
        return $errorMsg;
    }

    private function updatePage($pageName, $title, $description) {
        $db = $this->getDb();
        $page_id = $db->select()
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', $pageName)
                ->limit(1)
                ->query()
                ->fetchColumn();
        $tableNameContentName = "engine4_core_content";
        $top_content_id = $db->select()
                ->from($tableNameContentName, 'content_id')
                ->where('page_id =?', $page_id)
                ->where('name =?', 'top')
                ->query()
                ->fetchColumn();
        if (empty($top_content_id)) {
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'parent_content_id' => null,
                'order' => 1,
                'params' => ''
            ));
            $content_id = $db->lastInsertId('engine4_core_content');
            $middle_content_id = $db->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $content_id)
                    ->where('name =?', 'middle')
                    ->query()
                    ->fetchColumn();

            if (empty($middle_content_id)) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => 'middle',
                    'page_id' => $page_id,
                    'parent_content_id' => $content_id,
                    'order' => 2,
                    'params' => ''
                ));

                $content_id = $db->lastInsertId('engine4_core_content');

                $middle_banner_id = $db->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'captivate.banner-images')
                        ->query()
                        ->fetchColumn();
                if (!$middle_banner_id) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'widget',
                        'name' => 'captivate.banner-images',
                        'page_id' => $page_id,
                        'parent_content_id' => $content_id,
                        'order' => 1,
                        'params' => '{"showBanners":"1","selectedBanners":"","width":"","height":"280","speed":"5000","order":"2","captivateHtmlTitle":"' . $title . '","captivateHtmlDescription":"' . $description . '","title":"","nomobile":"0","name":"captivate.banner-images"}'
                    ));
                }
            }
            $db->query("UPDATE `engine4_core_content` SET  `order` =  '2' WHERE  `engine4_core_content`.`page_id` = $page_id AND `engine4_core_content`.`name` = 'main' LIMIT 1 ;");
        } else {
            $middle_content_id = $db->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $top_content_id)
                    ->where('name =?', 'middle')
                    ->query()
                    ->fetchColumn();

            if (!empty($middle_content_id)) {

                $middle_banner_id = $db->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $middle_content_id)
                        ->where('name =?', 'captivate.banner-images')
                        ->query()
                        ->fetchColumn();

                if (!$middle_banner_id) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'widget',
                        'name' => 'captivate.banner-images',
                        'page_id' => $page_id,
                        'parent_content_id' => $middle_content_id,
                        'order' => 1,
                        'params' => '{"showBanners":"1","selectedBanners":"","width":"","height":"280","speed":"5000","order":"2","captivateHtmlTitle":"' . $title . '","captivateHtmlDescription":"' . $description . '","title":"","nomobile":"0","name":"captivate.banner-images"}'
                    ));
                }
                $db->query("UPDATE `engine4_core_content` SET  `order` =  '2' WHERE  `engine4_core_content`.`page_id` = $page_id AND `engine4_core_content`.`name` = 'main' LIMIT 1;");

                if (Engine_Api::_()->hasModuleBootstrap('spectacular')) {
                    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id AND `engine4_core_content`.`name` = 'spectacular.banner-images' LIMIT 1;");
                }
            }
        }
    }

    function checkVersion($databaseVersion, $checkDependancyVersion) {
        if (strcasecmp($databaseVersion, $checkDependancyVersion) == 0)
            return -1;
        $databaseVersionArr = explode(".", $databaseVersion);
        $checkDependancyVersionArr = explode('.', $checkDependancyVersion);
        $fValueCount = $count = count($databaseVersionArr);
        $sValueCount = count($checkDependancyVersionArr);
        if ($fValueCount > $sValueCount)
            $count = $sValueCount;
        for ($i = 0; $i < $count; $i++) {
            $fValue = $databaseVersionArr[$i];
            $sValue = $checkDependancyVersionArr[$i];
            if (is_numeric($fValue) && is_numeric($sValue)) {
                $result = $this->compareValues($fValue, $sValue);
                if ($result == -1) {
                    if (($i + 1) == $count) {
                        return $this->compareValues($fValueCount, $sValueCount);
                    } else
                        continue;
                }
                return $result;
            }
            elseif (is_string($fValue) && is_numeric($sValue)) {
                $fsArr = explode("p", $fValue);
                $result = $this->compareValues($fsArr[0], $sValue);
                return $result == -1 ? 1 : $result;
            } elseif (is_numeric($fValue) && is_string($sValue)) {
                $ssArr = explode("p", $sValue);
                $result = $this->compareValues($fValue, $ssArr[0]);
                return $result == -1 ? 0 : $result;
            } elseif (is_string($fValue) && is_string($sValue)) {
                $fsArr = explode("p", $fValue);
                $ssArr = explode("p", $sValue);
                $result = $this->compareValues($fsArr[0], $ssArr[0]);
                if ($result != -1)
                    return $result;
                $result = $this->compareValues($fsArr[1], $ssArr[1]);
                return $result;
            }
        }
    }

    public function compareValues($firstVal, $secondVal) {
        $num = $firstVal - $secondVal;
        return ($num > 0) ? 1 : ($num < 0 ? 0 : -1);
    }

}
