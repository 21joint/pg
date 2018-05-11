<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Share Question
 */
class Ggcommunity_Form_Question_Share extends Engine_Form
{
  
  public function init()
  {
    $this->setAttribs(array(
      'id' => 'share_form',
      'class' => 'global_form_popup',
    ))
    ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ->setMethod('POST')
    ;

    $this
    ->setTitle('Copy Link')
    ->setDescription('Copy this struggle and share with your friend!');

    $this->addElement('Text', 'url', array(
      'allowEmpty' => false,
      'required' => true,
      'attribs' => array('readonly' => 'true'),
      'decorators' => array('ViewHelper'),
    ));


    $this->addElement('Button', 'submit', array(
      'type' => 'submit',
      'label' => 'Copy',
      'ignore' => true,
      'decorators' => array('ViewHelper'),
      'onclick' => 'copy_url()'
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
