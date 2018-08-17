<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_GuideItem extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = false;
    protected $_primary = "item_id";
    public function getShortType($inflect = false){
        return "item";
    }
    public function getContent(){
        return Engine_Api::_()->getItem($this->content_type,$this->content_id);
    }
    public function getGuide(){
        return Engine_Api::_()->getItem('sdparentalguide_guide', $this->guide_id);
    }
} 
