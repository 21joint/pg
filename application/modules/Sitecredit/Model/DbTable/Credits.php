<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Credits.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitecredit_Model_DbTable_Credits extends Engine_Db_Table
{
  protected $_rowClass = "Sitecredit_Model_Credit";

  function insertBonusData($param) { 
    $coreSettings=Engine_Api::_()->getApi('settings', 'core');
    $viewer=Engine_Api::_()->user()->getViewer();
    $website_name=$coreSettings->getSetting('core.general.site.title', '');
    $validity=$this->validityCheck();
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $routeStartP = Engine_Api::_()->getApi('settings', 'core')->getSetting('credit.manifestUrlP', "credits");
    $URL = $view->baseUrl()."/" . $routeStartP;
    $param['type']='bonus';
    if(!($param['user_id'] == 0)) {        
      $validityTable=Engine_Api::_()->getDbTable('validities','sitecredit');
      $select=$validityTable->select()->where('user_id=?',$param['user_id']);
      $validityuser= $validityTable->fetchRow($select);
      if(empty($validityuser)) {  
       Engine_Api::_()->getDbtable('validities','sitecredit')->insertvalidity($param);
      }
      $row = $this->createRow();
      $row->setFromArray($param);
      $row->save();
      $user=Engine_Api::_()->user()->getUser($param['user_id']);
      $link = '<a href="' . $URL . '" target="_parent">'.$param['credit_point'].'</a>'; 
      Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user,$viewer,$viewer, "Sitecredit_bonus", array(
                            'website_name' => $website_name,
                            'credit_value' => $link,
                        )); 
      if(!empty($param['send_mail'])) {
        Engine_Api::_()->getApi('core', 'sitecredit')->sendEmailToUser($param,"credits_sent_to_users");
      }        

    } else {
      $tableName = Engine_Api::_()->getDbTable('users', 'user');
      $select = $tableName->select();
      if(!empty($param['member_level'])) {
        $select->where("level_id=?",$param['member_level']);
      }

      $userObjects = $tableName->fetchAll($select);
      foreach ($userObjects as $users) {
        $param['user_id'] = $users->user_id;
        $validityTable=Engine_Api::_()->getDbTable('validities','sitecredit');
        $select=$validityTable->select()->where('user_id=?',$users->user_id);
        $validityuser= $validityTable->fetchRow($select);
        if(empty($validityuser)){  
          Engine_Api::_()->getDbtable('validities','sitecredit')->insertvalidity($param);
        }     	
        $row = $this->createRow();
        $row->setFromArray($param);
        $row->save();
        $user=Engine_Api::_()->user()->getUser($param['user_id']); 
        $link = '<a href="' . $URL . '" target="_parent">'.$param['credit_point'].'</a>';
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user,$viewer,$viewer, "Sitecredit_bonus", array(
                            'website_name' => $website_name,
                            'credit_value' => $link,
                        ));
        if(!empty($param['send_mail'])) {
          Engine_Api::_()->getApi('core', 'sitecredit')->sendEmailToUser($param,"credits_sent_to_users");
        } 
      }
    }
  } 

function insertCredit($param) {
  $param['creation_date']= new Zend_Db_Expr('NOW()');
  
  if($param['type']=='sent_to_friend') {
    $param['credit_point']=-$param['credit_point'];
  }
  if($param['type']=='received_from_friend') {
    $validityTable=Engine_Api::_()->getDbTable('validities','sitecredit');
    $select=$validityTable->select()->where('user_id=?',$param['user_id']);
    $validityuser= $validityTable->fetchRow($select);
    if(empty($validityuser)) {  
      Engine_Api::_()->getDbtable('validities','sitecredit')->insertvalidity($param);
    }
  }
  $row = $this->createRow();
  $row->setFromArray($param);
  $row->save();
  //after insertion in table
  if($param['type']=='buy'){
    return $row->credit_id;
  }
}

function activitiesPerformed($param)  { 
  $validity=$this->validityCheck();
  $userTable=Engine_Api::_()->getDbTable('users', 'user');
  $userTableName=  $userTable->info('name');

  $creditTableName = $this->info('name');

  $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
  $validityTableName = $validityTable->info('name');
  $select = $this->select()->setIntegrityCheck(false);

  $select->from($creditTableName , array('COUNT('.$creditTableName.'.credit_id) as activities',"$creditTableName.user_id",'SUM('.$creditTableName.'.credit_point) as credit',))
  ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id',array("$validityTableName.start_date"));
  $select->join($userTableName, $userTableName . '.user_id = ' . $creditTableName . '.user_id',array("$userTableName.displayname"));           

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
if(!empty($param['count'])){
  $select->limit($param['count']);
  return $this->fetchAll($select);
}
return $select;
} 

function Credits($param)  {
  $user_id=$param['user_id'];
  $validity=$this->validityCheck();
  $creditTableName = $this->info('name');
  
  $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
  $validityTableName = $validityTable->info('name');
  $select = $this->select()->setIntegrityCheck(false);
  
  $select->from($creditTableName , array('COUNT('.$creditTableName.'.credit_id) as activities',"$creditTableName.user_id",'SUM('.$creditTableName.'.credit_point) as credit'))
  ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id',array("$validityTableName.start_date")); 
  $select->where("$creditTableName.user_id=?",$user_id);        
  
  if($param['basedon']){
    $select->group("$creditTableName.user_id")->order("credit DESC");
          //earned till now
  } else {
    $select ->where('DATE_ADD(start_date, INTERVAL '.$validity.' MONTH) >'.$creditTableName.'.creation_date')
    ->where($creditTableName.'.creation_date > start_date')
    ->group("$creditTableName.user_id")->order("credit DESC");
  }
  
  return $this->fetchRow($select);

}

function CreditEarnDay($param) {
  $now = date('Y-m-d h:m:s');
  $now = strtotime($now);
  $raw = date('Y-m-d',$now);
  
  $creditTableName = $this->info('name');
  
  $select=$this->select();
  $select->from($creditTableName , array('SUM('.$creditTableName.'.credit_point) as credit'));
  $select->where('user_id = ?',$param['user_id'])->where("type='activity_type'")->where("DATE_FORMAT(`creation_date`, '%Y-%m-%d')=?",$raw);
  return $this->fetchRow($select);
  
}

function CreditsActivityType($param) {
  $user_id=$param['user_id'];
  $validity=$this->validityCheck();
  
  $creditTableName = $this->info('name');
  
  $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
  $validityTableName = $validityTable->info('name');
  $select = $this->select()->setIntegrityCheck(false);

  $select->from($creditTableName , array("$creditTableName.type",'SUM('.$creditTableName.'.credit_point) as credit'))
  ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id',array("$validityTableName.start_date"));
  
  $select->where("$creditTableName.user_id=?",$user_id);
  $select ->where('DATE_ADD(start_date, INTERVAL '.$validity.' MONTH) >'.$creditTableName.'.creation_date')
  ->where($creditTableName.'.creation_date > start_date');
  
  $select->group("$creditTableName.type"); 
  return $this->fetchAll($select);
  
}

function getCreditByTypeID($param= array())  {
  $user_id=$param['user_id'];
  $validity=$this->validityCheck();
  $creditTableName = $this->info('name');
  
  $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
  $validityTableName = $validityTable->info('name');
  $select = $this->select()->setIntegrityCheck(false);

  $select->from($creditTableName , array("$creditTableName.type_id",'SUM('.$creditTableName.'.credit_point) as credit'))
  ->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id',array("$validityTableName.start_date"));  
  $select->where("$creditTableName.user_id = ?",$user_id);
  $select->where('DATE_ADD(start_date, INTERVAL '.$validity.' MONTH) >'.$creditTableName.'.creation_date')
  ->where($creditTableName.'.creation_date > start_date'); 
  $select->where("$creditTableName.type=?",'activity_type')
  ->group("$creditTableName.type_id");

  return $this->fetchAll($select);
}

function validityCheck()  {
  $coreSettings=Engine_Api::_()->getApi('settings', 'core');
  $CreditValidity = $coreSettings->getSetting('sitecredit.validity',1);        
  if(empty($CreditValidity))
  {
    return (25*12);
  } else {

   $year= $coreSettings->getSetting('sitecredit.year.validity',1);
   $month= $coreSettings->getSetting('sitecredit.month.validity',1);   

   $validityMonths=($year*12)+$month;
   return $validityMonths;

 }
 
}

} 




