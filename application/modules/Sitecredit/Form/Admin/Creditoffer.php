<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Creditoffer.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Admin_Creditoffer extends Engine_Form {

  public function init() {
    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    $this  ->setTitle('Credit Offer')
    ->setDescription('Add an offer which your site users can avail while purchasing the credits.');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;


    $label='Value ('.Engine_Api::_()->getApi('core', 'sitecredit')->getCurrencySymbol().')';
    $this->addElement('Text', 'value', array(
      'description' => "",
      'required' => true,
      'validators'=>array(
        array('NotEmpty', true),
        array('Int', true),
        new Engine_Validate_AtLeast(1),
        ),
      'label' => $label,
      'value' => '',
      ));
    $this->addElement('Text', 'credit_point', array(
      'label' => 'Credits',
      'description' => "",
      'required' => true,
      'validators'=>array(
        array('NotEmpty', true),
        array('Int', true),
        new Engine_Validate_AtLeast(1),

        ),
      'value' => '',
      ));
    

    $this->addElement('Radio', 'end_date', array(
      'label' => 'When should this offer end?',
      'description' => '',
      'multiOptions' => array(
        1 => 'No Specific End Date',
        0 => 'Specific Date',
        ),
      'value' => 1,
      'onchange'=>'onEndDateChange(this.value)'
      ));


    $expiry_date = new Engine_Form_Element_CalendarDateTime('expiry_date');
    $expiry_date->setLabel("Offer Validity");
    $expiry_date->setValue(date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') , date('d') -1, date('Y'))));

    $this->addElement($expiry_date);

    $this->addElement('Button', 'submit', array(
      'label' => 'Add Offer',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
      ));
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'href' => '',
      'onClick'=> 'javascript:parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
        )
      ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');

  }

}
