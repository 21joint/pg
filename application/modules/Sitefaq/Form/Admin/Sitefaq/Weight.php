<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Question.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Admin_Sitefaq_Weight extends Engine_Form
{
  public function init()
  {
	  $this->setTitle('Set FAQ Weight');
		$this->setDescription("Enter the weight that you want to associate with this FAQ (Enter an integer between 0 to 9999.). This will work as a reference to the FAQs priority. Higher an FAQs weight, higher will be its chances to be shown on top of other FAQs. (Ordering based on weight will be applicable on FAQs Home and Browse pages. For widgets, you will be able to choose the ordering sequence from their settings.)");

		$weight = Zend_Controller_Front::getInstance()->getRequest()->getParam('weight', null);

		$this->addElement('Text', 'weight', array(
			'allowEmpty' => false,
			'required' => true,
			'maxlength' => '4',
			'value' => $weight,
			'validators' => array(
					array('Int', true)
		)));
	        
    $this->addElement('Button', 'submit', array(
      'label' => 'Save',
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
    
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      ),
    ));
  }

}