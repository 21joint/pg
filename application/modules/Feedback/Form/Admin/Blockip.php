<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Blockip.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Form_Admin_Blockip extends Engine_Form
{
  protected $_field;

  public function init()
  {
    $this
    ->setMethod('post')
    ->setAttrib('class', 'global_form_box');

		$blockip_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', null);
		if($blockip_id) {
			$label = new Zend_Form_Element_Text('label');
			$label->setLabel('IP Address')
				->setAttrib('disabled', true);
		} 
		else {
			$label = new Zend_Form_Element_Text('label');
			$label->setLabel('IP Address')
				->addValidator('NotEmpty')
				->setRequired(true);
		}

    $id = new Zend_Form_Element_Hidden('id');

    $this->addElements(array(
      $label,
      $id
    ));
     
    $this->addElement('Checkbox', 'blockip_feedback', array(
      'label' => "Block Feedback posting",
      'value' => 0
    ));
    
    $this->addElement('Checkbox', 'blockip_comment', array(
      'label' => "Block Comment posting",
      'value' => 0
    ));
    
    $this->addElement('Button', 'submit', array(
      'label' => 'Block IP Address',
      'style' => 'margin-top:25px;',
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

  public function setField($blockip)
  {
    $this->_field = $blockip;

    $this->label->setValue($blockip->blockip_address);
    $this->id->setValue($blockip->blockip_id);
    $this->blockip_comment->setValue($blockip->blockip_comment);
    $this->blockip_feedback->setValue($blockip->blockip_feedback);
    $this->submit->setLabel('Edit setting');

  }
}
