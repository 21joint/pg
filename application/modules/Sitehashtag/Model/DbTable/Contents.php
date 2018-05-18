<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Contents.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_Model_DbTable_Contents extends Engine_Db_Table {

    protected $_rowClass = "Sitehashtag_Model_Content";

    public function getEnable($moduleName) {
        
        $globalSetting = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.modules', 2);
        
        if($globalSetting == 2){
            return 1;
        }
        else if($globalSetting == 1){
            //MAKE QUERY
            $select = $this->select();
            $contents = $this->fetchAll($select);

            foreach ($contents as $content) {
                $module_name = $content->module_name;
                if (stripos($module_name, $moduleName) !== FALSE || stripos($moduleName, $module_name) !== FALSE) {
                    return $content->enabled;
                }
            }
            //RETURN RESULTS
            return 0;
        }
        else {
            if (stripos($moduleName, 'activity') !== FALSE || stripos('activity', $moduleName) !== FALSE) {
                return 1;
            } else {
                return 0;
            }
        }
        
    }

}
