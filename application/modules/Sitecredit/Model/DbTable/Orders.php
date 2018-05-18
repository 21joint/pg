<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Orders.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Model_DbTable_Orders extends Engine_Db_Table
{

  protected $_name = 'sitecredit_orders';
  protected $_rowClass = 'Sitecredit_Model_Order';
  function getOrderid($Param) {

    $value=Array();
    
    if($Param['credit_offer']) {
     
      $value['offer_id']=$Param['offers'];
      $table= Engine_Api::_()->getDbtable('offers', 'sitecredit');
      $select=$table->select()->where('offer_id = ?',$Param['offers']);
      
      $result=$table->fetchRow($select);

      $value['credit_point']=$result->credit_point;
      $value['grand_total']=$result->value;
    } else {
      
      $value['offer_id']=0;
      $value['credit_point']=$Param['credit_point_2'];
      $value['grand_total']=$Param['credit_point_2']*$Param['costonecredit'];
    }

    $value['user_id']=Engine_Api::_()->user()->getViewer()->getIdentity();
    $value['creation_date']=new Zend_Db_Expr('NOW()');
    $value['order_status']='pending';
    $value['gateway_id']=$Param['payment_method'];
    $value['gateway_type']='normal';

    $row = $this->createRow();
    $row->setFromArray($value);
    $row->save();

    return $row->order_id;
    
  }


  
}



