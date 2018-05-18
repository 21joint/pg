<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Admin_Search_Filter extends Engine_Form
{
  public function init()
  {
    $this
      ->clearDecorators()
      ->addDecorator('FormElements')
      ->addDecorator('Form')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'))
      ;

    $this
      ->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box',
      ))
      ->setMethod('GET');
    
    
    $search = new Zend_Form_Element_Text('search');
    $search
      ->setLabel('Search Term')
      ->setAttrib('placeholder','Search Term')      
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'search-term'));
    
    $alias = new Zend_Form_Element_Text('alias');
    $alias
      ->setLabel('Alias')
      ->setAttrib('placeholder','Alias')      
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'alias'));
    
    $this->addElements(array(
      $search,
      $alias,
    ));
    
    $this->addDisplayGroup(array('search','alias'),'search-grp');
    
    
    
    $this->addElement('Dummy', 'search_button', array(
        'decorators' => array(
          array('ViewScript', array(
            'viewScript' => '_SearchButtons.tpl',
          ))
        )
    ));
        
    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}