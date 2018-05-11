<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Validities.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Model_DbTable_Validities extends Engine_Db_Table
{
  
  protected $_rowClass = "Sitecredit_Model_Validity";

  function insertvalidity($param = array())
  {
    $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
    if(empty($param['user_id']))
    {
      $param['user_id']=$viewer;
    }     
    $param['start_date']= new Zend_Db_Expr('curdate()');
    
    $row = $this->createRow();
    $row->setFromArray($param);
    $row->save();
  }

  function updateValidity($start_date)
  {
    $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();

    $this->update(array('start_date'=>$start_date), array('user_id = ?' => $viewer));

  }

}