<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Option
 */
class Ggcommunity_Form_Admin_Manage_Option extends Engine_Form
{
  public function init()
  {
    $this
      ->setTitle('Approve Question')
      ->setDescription('Are you sure you want to approve this item?')
    ;

    $this
      ->setAttribs(array(
        'id' => 'option_form',
        'class' => 'global_form_popup',
      ))
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;


    $this->addElement('Button', 'submit', array(
      'type' => 'submit',
      'label' => 'Approve',
      'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => Zend_Registry::get('Zend_Translate')->_(' or '),
      'href' => '',
      'onclick' => 'parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
      )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');

    
  }
}