<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Activitycredits.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitecredit_Model_DbTable_Activitycredits extends Engine_Db_Table
{
  function insertData($param) {
    $translate = Zend_Registry::get('Zend_Translate');
      // Prepare language list
    $languageList = $translate->getList();

    $param['modified_date']= new Zend_Db_Expr('NOW()');
    $updateArray=array(
      'modified_date'=>$param['modified_date'],
      'credit_point_first'=> $param['credit_point_first'],
      'credit_point_other'=> $param['credit_point_other'],
      'deduction'=> $param['deduction'],
      'limit_per_day'=> $param['limit_per_day'],
      );
    foreach($languageList as $key => $value)  {
      $updateArray['language_'.$key] = $param['language_'.$key];
    }  

    if(empty($param['member_level'])) {
      foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level ) {
        $param['member_level']=$level->level_id;
        $ativity_object = $this->fetchRow(array('activity_type = ?' => $param['activity_type'], 'member_level =?' => $param['member_level']));
        if (!empty($ativity_object))  {
          $this->update($updateArray, array('activity_type = ?' => $param['activity_type'], 'member_level =?' => $param['member_level']));
        } else {
          if(!empty($param['credit_point_first']) && !empty($param['credit_point_other'])) {
            $param['creation_date']= new Zend_Db_Expr('NOW()'); 
            $row = $this->createRow();
            $row->setFromArray($param);
            $row->save();
          }
        }
      }
      $param['member_level']=0;
      $ativity_object = $this->fetchRow(array('activity_type = ?' => $param['activity_type'], 'member_level =?' => $param['member_level']));
      if (!empty($ativity_object))  {
        $this->update($updateArray, array('activity_type = ?' => $param['activity_type'], 'member_level =?' => $param['member_level']));
      } else {
        if(!empty($param['credit_point_first']) && !empty($param['credit_point_other'])) {
          $param['creation_date']= new Zend_Db_Expr('NOW()'); 
          $row = $this->createRow();
          $row->setFromArray($param);
          $row->save();
        }
      }
      //}
    } else {
      $ativity_object = $this->fetchRow(array('activity_type = ?' => $param['activity_type'], 'member_level =?' => $param['member_level']));
      if (!empty($ativity_object))  {
        $this->update($updateArray, array('activity_type = ?' => $param['activity_type'], 'member_level =?' => $param['member_level']));
      } else {
        if(!empty($param['credit_point_first']) && !empty($param['credit_point_other']) ) {
          $param['creation_date']= new Zend_Db_Expr('NOW()'); 
          $row = $this->createRow();
          $row->setFromArray($param);
          $row->save();
        }
      }

    }
  }

  function getData($activity_type,$member_level) {
    //if(!empty($member_level)){
    return  $this->fetchRow(array('activity_type = ?' => $activity_type, 'member_level =?' => $member_level));
    /* } else {
    return  $this->fetchRow(array('activity_type = ?' => $activity_type)); 
  }*/
}

function changeStatus($param) {
  $translate = Zend_Registry::get('Zend_Translate');
      // Prepare language list
  $languageList = $translate->getList();

  $param['modified_date']= new Zend_Db_Expr('NOW()');
  $updateArray=array(
    'modified_date'=>$param['modified_date'],
    'credit_point_first'=> $param['credit_point_first'],
    'credit_point_other'=> $param['credit_point_other'],
    'deduction'=> $param['deduction'],
    'limit_per_day'=> $param['limit_per_day'],
    );
  foreach($languageList as $key => $value){
    $updateArray['language_'.$key] = $param['language_'.$key];
  }  

  $this->update($updateArray, array('activity_type = ?' => $param['activity_type'], 'member_level =?' => $param['member_level']));
}
function CreditDayLimit($level_id)  {
  $creditTableName = $this->info('name');

  $select=$this->select();
  $select->from($creditTableName , array('SUM('.$creditTableName.'.limit_per_day) as credit'));
  $select->where('member_level = ?',$level_id)->where("status='enabled'");
  return $this->fetchRow($select);
}

}