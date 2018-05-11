<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_Relationships extends Engine_Db_Table
{
    public function getMultiOptions(){
        $relationships = $this->fetchAll();
        if(count($relationships) <= 0){
            return array('' => '');
        }
        $relationshipOptions = array('' => '');
        foreach($relationships as $relationship){
            $relationshipOptions[$relationship->relationship_id] = $relationship->title;
        }
        return $relationshipOptions;
    }
    public function getById($id){
        return $this->fetchRow($this->select()->where('relationship_id = ?',$id));
    }
} 




