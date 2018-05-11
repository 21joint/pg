<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_Search extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = false;
    protected $_primary = "search_activity_id";
    public function getShortType($inflect = false){
        return "search_activity";
    }
} 




