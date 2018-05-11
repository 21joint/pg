<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Order.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Model_Order extends Core_Model_Item_Abstract
{

  public function onPaymentSuccess() {
   if (in_array($this->payment_status, array('initial', 'trial', 'pending', 'active', 'overdue', 'expired'))) {

      //$this->setActive(true);

      // Change status
    if ($this->payment_status != 'active') {
      $this->payment_status = 'active';
      
    }
  }
  $this->save();
  return $this;
}

public function onPaymentPending() {
 if (in_array($this->payment_status, array('initial', 'trial', 'pending', 'active', 'overdue', 'expired'))) {
      // Change status
  if ($this->payment_status != 'pending') {
    $this->payment_status = 'pending';
    
  }
}
$this->save();
return $this;
}

public function onPaymentFailure() {
 
  if (in_array($this->payment_status, array('initial', 'trial', 'pending', 'active', 'overdue', 'expired'))) {
      // Change status
    if ($this->payment_status != 'overdue') {
      $this->payment_status = 'overdue';
      
    }

    $session = new Zend_Session_Namespace('Payment_Sitecredit');
    $session->unsetAll();
  }
  $this->save();
  return $this;
}
}