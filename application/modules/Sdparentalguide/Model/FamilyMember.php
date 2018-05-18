<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_FamilyMember extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = false;
    public function getRelationship(){
        $table = Engine_Api::_()->getDbTable("relationships","sdparentalguide");
        $row = $table->getById($this->type);
        if(empty($row)){
            return null;
        }
        return $row->title;
    }
    public function getGender(){
        $gender = "Prefer Not to Answer";
        switch($this->gender){
            case 1:
                $gender = "Male";
                break;
            case 2:
                $gender = "Female";
                break;
        }
        return $gender;
    }
    public function getShortType($inflect = false) {
        return "family_member";
    }
} 




