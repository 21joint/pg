<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Pgservicelayer_Model_View extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = false;
    public function getShortType($inflect = false){
        return 'action';
    }
    public function getIdentity() {
        return $this->action_id;
    }
} 




