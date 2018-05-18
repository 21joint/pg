<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Storeintergration.php 2013-03-18 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 
class Sitepageintegration_Form_Storeintergration extends Engine_Form {
  protected $_owner;
  
  public function getOwnerId() {
    return $this->_owner;
  }

  public function setOwnerId($owner) {
    $this->_owner = $owner;
    return $this;
  }
  public function init() {

    $this->setMethod('POST')
			   ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    $this->setTitle('Choose Store')
        ->setDescription('Choose Store to integrated to page. This page automatically integrated with selected store.');

		$mixSettings = array();
		$stores = Engine_Api::_()->getDbtable('stores', 'sitestore')->getStoreId($this->getOwnerId());
		foreach($stores as $store) {
			$store_object = Engine_Api::_()->getItem('sitestore_store', $store['store_id']);
			$mixSettings[$store_object->store_id] = $store_object->title;
		}

		$this->addElement('Radio', 'store_id', array(
			'multiOptions' => $mixSettings
		));

    $this->addElement('Button', 'accept', array(
      'label' => 'Submit',
      'ignore' => true,
      'decorators' => array('ViewHelper'),
      'type' => 'submit',
    ));

    $this->addElement('Cancel', 'cancel', array(
      'prependText' => ' or ',
      'label' => 'cancel',
      'link' => true,
      'href' => '',
      'onclick' => 'parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
      ),
    ));

    $this->addDisplayGroup(array('accept', 'cancel'), 'buttons');
  }
}