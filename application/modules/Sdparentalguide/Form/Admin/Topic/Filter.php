<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Admin_Topic_Filter extends Engine_Form
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
    
    
    $this->addElement('Dummy', 'topic_buttons', array(
      'decorators' => array(
        array('ViewScript', array(
          'viewScript' => '_topicButtons.tpl',
        ))
      )
    ));
    
    $active = new Zend_Form_Element_Radio('active');
    $active
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'topic_active'))
      ->setMultiOptions(array(
          '-11' => 'All',
          '1' => 'Active',
          '0' => 'Inactive'
      ))->setAttrib("onchange","searchTopics();");
    
    $badges = new Zend_Form_Element_Radio('badges');
    $badges
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'badges'))
      ->setMultiOptions(array(
          '-111' => 'All',
          '1' => 'Badges',
      ))->setAttrib("onchange","searchTopics();");
    
    $featured = new Zend_Form_Element_Radio('featured');
    $featured
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'badges'))
      ->setMultiOptions(array(
          '-1111' => 'All',
          '1' => 'Featured',
      ))->setAttrib("onchange","searchTopics();");
    

    $this->addElement('Hidden', 'order', array(
      'order' => 10001,
    ));

    $this->addElement('Hidden', 'order_direction', array(
      'order' => 10002,
    ));

        
    $this->addElements(array(
      $active,
      $badges,
      $featured,
    ));
    
    
    $search = new Zend_Form_Element_Text('search');
    $search
      ->setLabel('Search')
      ->setAttrib('placeholder','Search')      
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field search'));
    
    $listingtypes = Engine_Api::_()->getDbTable("listingtypes","sitereview")->getAllListingTypes();
    $listingtypesOptions = array('' => 'Listing Type');
    foreach($listingtypes as $listingType){
        $listingtypesOptions[$listingType->getIdentity()] = $listingType->getTitle();
    }
        
    $this->addElements(array(
      $search,
    ));
    
    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}