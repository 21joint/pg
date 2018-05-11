<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_Statistic extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = false;
//    protected $_disableHooks = true;
    protected $_primary = "site_activity_id";
    public function getShortType($inflect = false){
        return "site_activity";
    }
} 




