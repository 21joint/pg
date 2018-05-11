<?php
/**
 * EXTFOX
 *
 * @category   Ggcommunity
 * @package    Publish Question
 */
class Ggcommunity_Form_Question_Publish extends Engine_Form
{
  
  public function init()
  {
    $this->setAttribs(array(
      'id' => 'publish_form',
      'class' => 'global_form_popup',
    ))
    ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ->setMethod('POST');

    $this
    ->setTitle('Publish Item')
    ->setDescription('Are you sure you want to publish this item?');


    $this->addElement('Button', 'submit', array(
      'type' => 'submit',
      'label' => 'Publish',
      'ignore' => true,
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
