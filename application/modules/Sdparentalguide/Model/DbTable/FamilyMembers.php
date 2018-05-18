<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_FamilyMembers extends Engine_Db_Table
{
    protected $_rowClass = "Sdparentalguide_Model_FamilyMember";
    protected $_name = 'gg_family_member';
    public function getById($id){
        return $this->fetchRow($this->select()->where('family_member_id = ?',$id));
    }
} 




