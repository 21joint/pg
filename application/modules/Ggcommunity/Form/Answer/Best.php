<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Best Answer
 */
class Ggcommunity_Form_Answer_Best extends Engine_Form
{
  
  public function init()
  {
    $this->setAttribs(array(
      'id' => 'best_form',
      'class' => 'global_form_popup',
      ))
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;

    $this
    ->setTitle('Chose This Answer')
    ->setDescription('Are you sure you want to declare this theory as best?');


    $this->addElement('Button', 'submit', array(
      'type' => 'submit',
      'label' => 'Confirm',
      'decorators' => array('ViewHelper'),
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
