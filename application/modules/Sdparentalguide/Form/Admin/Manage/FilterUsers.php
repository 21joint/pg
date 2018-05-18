<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Admin_Manage_FilterUsers extends Engine_Form
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

    $username = new Zend_Form_Element_Text('username');
    $username
      ->setLabel('User Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

    $firstName = new Zend_Form_Element_Text('first_name');
    $firstName
      ->setLabel('First Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

    $lastName = new Zend_Form_Element_Text('last_name');
    $lastName
      ->setLabel('Last Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

    $profileType = new Zend_Form_Element_Text('level');
    $profileType
      ->setLabel('User Level')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

    $this->addElement('Hidden', 'order', array(
      'order' => 10001,
    ));

    $this->addElement('Hidden', 'order_direction', array(
      'order' => 10002,
    ));

    $this->addElement('Hidden', 'user_id', array(
      'order' => 10003,
    ));
    
    $this->addElement('Hidden', 'level_id', array(
      'order' => 10004,
    ));
    
    $featured = new Zend_Form_Element_Checkbox('featured');
    $featured
      ->setLabel('Show Only Featured')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'APPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'featured_checkbox','id' => 'featured_checkbox'))
      ->setAttrib("onchange","startSearch(this);");

    
    $mvp = new Zend_Form_Element_Checkbox('mvp');
    $mvp
      ->setLabel('Show Only MVP')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'APPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'mvp_checkbox','id' => 'mvp_checkbox'))
      ->setAttrib("onchange","startSearch(this);");
    
    $expert = new Zend_Form_Element_Checkbox('expert');
    $expert
      ->setLabel('Show Only Experts')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'APPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'expert_checkbox','id' => 'expert_checkbox'))
      ->setAttrib("onchange","startSearch(this);");
    
    $this->addElements(array(
      $username,
      $firstName,
      $lastName,
      $profileType,
      $featured,
      $mvp,
      $expert
    ));
    

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}