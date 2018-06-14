<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_Credits extends Sitecredit_Model_DbTable_Credits
{
    protected $_rowClass = "Sitecredit_Model_Credit";
    protected $_name = 'sitecredit_credits';
            
    function activitiesPerformed($param){
      $validity = $this->validityCheck();
      $userTable = Engine_Api::_()->getDbTable('users', 'user');
      $userTableName=  $userTable->info('name');

      $creditTableName = Engine_Api::_()->getDbtable('credits','sitecredit')->info('name');

      $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
      $validityTableName = $validityTable->info('name');
      $select = $this->select()->setIntegrityCheck(false);

      $select->from($creditTableName , array('COUNT('.$creditTableName.'.credit_id) as activities',"$creditTableName.user_id",'SUM('.$creditTableName.'.credit_point) as credit',))
      ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id',array("$validityTableName.start_date"));

      if($param['basedon']=='activities'){
       $select->where("$creditTableName.type='activity_type'")
       ->group("$creditTableName.user_id")->order("activities DESC");
     }

     if($param['basedon']=='earned'){
      $select->group("$creditTableName.user_id")->order("credit DESC");
          //earned till now
    } else {

      $select->where('DATE_ADD(start_date, INTERVAL '.$validity.' MONTH) >'.$creditTableName.'.creation_date')
      ->where($creditTableName.'.creation_date > start_date');
      $select->group("$creditTableName.user_id")->order("credit DESC");
    }
    if(!empty($param['user_id_admin'])){
      $select->where("$creditTableName.user_id = ? ",$param['user_id_admin']);
    }
    $select->limit(100);
    $paginator = Zend_Paginator::factory($select);
    return $paginator;
    } 
    
    public function getUserActivityCount($user){        
        $creditTable = Engine_Api::_()->getDbtable('credits', 'sitecredit');
        $validity = $creditTable->validityCheck();

        $userTable = Engine_Api::_()->getDbTable('users', 'user');
        $userTableName = $userTable->info('name');

        $creditTableName = $creditTable->info('name');

        $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $validityTableName = $validityTable->info('name');
        $select = $creditTable->select()->setIntegrityCheck(false);

        $select->from($creditTableName, array('COUNT(' . $creditTableName . '.credit_id) as activities', "$creditTableName.user_id", 'SUM(' . $creditTableName . '.credit_point) as credit',))
                ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id', array("$validityTableName.start_date"));
        $select->join($userTableName, $userTableName . '.user_id = ' . $creditTableName . '.user_id', array("$userTableName.displayname"));

        $select->where('DATE_ADD(start_date, INTERVAL ' . $validity . ' MONTH) >' . $creditTableName . '.creation_date')
                ->where($creditTableName . '.creation_date > start_date');

        $select->group("$creditTableName.user_id");
        
        $select->where("$creditTableName.user_id = ? ",$user->getIdentity());
        $select->limit(1);
        $row = $this->fetchRow($select);
        return $row;
    }
    
    public function getFieldValue($user_id, $field_id){
      $fieldValuesTable = Engine_Api::_()->fields()->getTable('user', 'values');
      $select = $fieldValuesTable->select()->where('item_id = ?', $user_id)->where('field_id = ?', $field_id);
      $fieldValue = $fieldValuesTable->fetchRow($select);
      if(empty($fieldValue)){
          return null;
      }
      return $fieldValue->value;
    }
  
    public function getUserLevel($level_id){
      $levelsTable = Engine_Api::_()->getDbTable('levels', 'authorization');
      $levelsObj = $levelsTable->select()->where('level_id = ?', $level_id);
      return $userLevel = $levelsTable->fetchRow($levelsObj);
    }
} 




