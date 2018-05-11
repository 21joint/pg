<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Api_Core extends Core_Api_Abstract {

    /**
     * Check default "/index.php/application/" valid or not * 
     * @return boolean
     */
    public function isRootFileValid() {
        $isValidFileAvailable = false;
        $file = APPLICATION_PATH . "/application/index.php";
        if (file_exists($file) && is_readable($file)) {
            $myfile = @fopen($file, "r") or die("Unable to open file!");
            // Output one line until end-of-file
            while (!feof($myfile)) {
                $rowContent = @fgets($myfile);
                if (strstr($rowContent, "include_once APPLICATION_PATH_MOD . DS . 'Advancedpagecache/cache.php';")) {
                    $isValidFileAvailable = true;
                    break;
                }
            }
            @fclose($myfile);
        }
        return $isValidFileAvailable;
    }

    /**
     * find free space available on disk
     * @param type $disk
     * @return float */
    public function getFreeDiskSpace($disk) {
        $bytes = disk_free_space($disk);
        $gb = round(($bytes / 1073741824), 2);
        return $gb;
    }

}
