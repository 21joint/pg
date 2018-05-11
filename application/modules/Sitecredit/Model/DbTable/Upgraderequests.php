<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Upgraderequests.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitecredit_Model_DbTable_Upgraderequests extends Engine_Db_Table
{
 protected $_rowClass = "Sitecredit_Model_Upgraderequest";

 function getRequestsPaginator($params = array())  {
  $paginator = Zend_Paginator::factory($this->getRequestsSelect($params));
  if( !empty($params['page']) ) {
    $paginator->setCurrentPageNumber($params['page']);
  }
  if( !empty($params['limit']) )  {
    $paginator->setItemCountPerPage($params['limit']);
  }
  return $paginator;
}

function getRequestsSelect($params = array())  {
  $table = Engine_Api::_()->getDbtable('upgraderequests', 'sitecredit');
  $select = $table->select()->order('creation_date DESC' );
  return $select;
}

function insertData($param)  {
  $param['creation_date']= new Zend_Db_Expr('NOW()');
  $row = $this->createRow();
  $row->setFromArray($param);
  $row->save();
} 

function updateStatus($param) {
  $this->update(array('status'=> $param['status']), array('upgraderequest_id = ?' => $param['id']));
}

}