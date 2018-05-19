<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Admin_Badge_FilterBadges extends Engine_Form
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

    $name = new Zend_Form_Element_Text('name');
    $name
      ->setLabel('Badge Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

//    $listingtypes = Engine_Api::_()->getDbTable("listingtypes","sitereview")->getListingTypesArray();
//    $listingtypes['0'] = 'All';
//    ksort($listingtypes);
//    
//    $listingtype_id = new Zend_Form_Element_Select('listingtype_id');
//    $listingtype_id
//      ->setLabel('Category')
//      ->clearDecorators()
//      ->addDecorator('ViewHelper')
//      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
//      ->addDecorator('HtmlTag', array('tag' => 'div'))
//      ->setMultiOptions($listingtypes)->setAttrib("onchange","searchBadges();");
    
    $topic = new Zend_Form_Element_Text('topic');
    $topic
      ->setLabel('Topic Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'topic_suggest'))
      ;
    
//    $levelOptions = Engine_Api::_()->sdparentalguide()->getBadgeLevels();
//    $levelOptions[''] = 'All';
//    ksort($levelOptions);
//    $level = new Zend_Form_Element_Select('level');
//    $level
//      ->setLabel('Level')
//      ->clearDecorators()
//      ->addDecorator('ViewHelper')
//      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
//      ->addDecorator('HtmlTag', array('tag' => 'div'))
//      ->setMultiOptions($levelOptions)->setAttrib("onchange","searchBadges();");
    
    
    $badgeTypeOptions = Engine_Api::_()->sdparentalguide()->getBadgeTypes();
    $badgeTypeOptions[''] = 'All';
    ksort($badgeTypeOptions);
    $badgeType = new Zend_Form_Element_Select('type');
    $badgeType
      ->setLabel('Badge Type')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions($badgeTypeOptions)->setAttrib("onchange","searchBadges();");
    
    $active = new Zend_Form_Element_Radio('active');
    $active
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-11' => 'All',
          '1' => 'Active',
          '0' => 'Inactive'
      ))->setAttrib("onchange","searchBadges();");
    
    $profile_display = new Zend_Form_Element_Radio('profile_display');
    $profile_display
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-11' => 'All',
          '1' => 'Display on Profile',
          '0' => 'Not Displayed on Profile'
      ))->setAttrib("onchange","searchBadges();");

    $this->addElement('Hidden', 'order', array(
      'order' => 10001,
    ));
    
    $this->addElement('Hidden', 'topic_id', array(
      'order' => 10005,
    ));

    $this->addElement('Hidden', 'order_direction', array(
      'order' => 10002,
    ));

        
    $this->addElements(array(
      $name,
//      $listingtype_id,
      $topic,
      $badgeType,
//      $level,
      $active,
      $profile_display
    ));

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}