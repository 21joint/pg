<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegateway
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    install.php 2015-09-10 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegifplayer_Installer extends Engine_Package_Installer_Module
{
    public function onPreinstall() {
        $getErrorMsg = $this->getVersion();
        if (!empty($getErrorMsg)) {
            return $this->_error($getErrorMsg);
        }
        $PRODUCT_TYPE = 'sitegifplayer';
        $PLUGIN_TITLE = 'Sitegifplayer';
        $PLUGIN_VERSION = '4.9.2p1';
        $PLUGIN_CATEGORY = 'plugin';
        $PRODUCT_DESCRIPTION = 'GIF Player Plugin';
        $PRODUCT_TITLE = 'GIF Player Plugin';
        $_PRODUCT_FINAL_FILE = 0;
        $SocialEngineAddOns_version = '4.9.2p1';
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
        $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='sitegifplayer';");
        parent::onInstall();
    }

    private function getVersion() {
        $db = $this->getDb();

        $errorMsg = '';
        $finalModules = $getResultArray = array();
        $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();

        $modArray = array(
            'nestedcomment' => '4.9.2p2',
            'advancedactivity' => '4.9.2p2',
            'sitealbum' => '4.9.2p3',
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
            $errorMsg .= '<div class="tip"><span>Note: Your website does not have the latest version of "' . $modArray['title'] . '". Please upgrade "' . $modArray['title'] . '" on your website to the latest version available in your SocialEngineAddOns Client Area to enable its integration with "GIF Player Plugin".<br/> Please <a href="' . $base_url . '/manage">Click here</a> to go Manage Packages.</span></div>';
        }

        return $errorMsg;
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
