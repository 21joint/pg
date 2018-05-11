<?php

class Ggcommunity_Form_Select extends Engine_Form
{
  public function init()
  {
    $this->setAttribs(array(
      'id' => 'list_form',
      'class' => 'global_form_box',
    ))
    ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
      ->setMethod('GET');
    ;


    $this->addElement('Select', 'param', array(
      'label' => 'List By',
      'allowEmpty' => true,
      'required' => false,
      'multiOptions' => array('trending' => 'Trending', 'popular' => 'Popular', 'featured' => 'Featured'),
    ));

    // Button
    $this->addElement('Button', 'submit', array(
      'label' => 'List',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));


  }
}