<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Ggcommunity Search
 */

class Ggcommunity_Form_Admin_Manage_Search extends Engine_Form
{
  public function init()
  {
    
    // CHANGE FILEDS WITH ADD ELEMENT, INSTEAD DIRECT ELEMENT - YOU AN REMOVE THIS COMMENT AFTER THIS
    $this
      ->clearDecorators()
      ->addDecorator('FormElements')
      ->addDecorator('Form')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'))
    ;

    $this
      ->setAttribs(array(
        'id' => 'search_form',
        'class' => 'global_form_box',
      ))
      ->setMethod('GET')
      //->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params))
    ;

    // Element: keyword
    $this->addElement('Text', 'keyword', array(
      'label' => 'Search by Keyword from Description',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
      
    ));
   
    $this->addElement('Button', 'submit', array(
      'type' => 'submit',
      'label' => 'Search',
      'decorators' => array(
        'ViewHelper', 
        array('HtmlTag', array('tag' => 'div')),
        array('HtmlTag2', array('tag' => 'div')),
        
      ),
    ));

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    
  }
}