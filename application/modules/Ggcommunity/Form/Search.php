<?php
/**
 * @package    Ggcommunity
 * @author     EXTFOX
 */
class Ggcommunity_Form_Search extends Engine_Form
{
  public function init()
  {
    $this
      ->setMethod('get')
      ->setDecorators(array('FormElements', 'Form'))
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'browse_struggles'))
    ;
    
    $this->addElement('Text', 'query', array(
      'decorators' => array(
        'ViewHelper',
      ),
      'placeholder' => 'What is your parenting struggle?',
    ));

    $this->addElement('Button', 'submit', array(
      'label' => 'Search Now',
      'class' => 'btn primary large-x',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array(
        'ViewHelper',
      ),
    ));
  }
}