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
    
    $sync = new Zend_Form_Element_Button('sync');
    $sync
      ->setLabel('Syncronize with Listings')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sync'))
      ->setAttrib("onclick","synchronizeTopics(this);")
      ;
    
    $sync_tags = new Zend_Form_Element_Button('sync_tags');
    $sync_tags
      ->setLabel('Syncronize with Tags')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sync_tags'))
      ->setAttrib("onclick","synchronizeTags(this);")
      ;

    $this->addElement('Hidden', 'order', array(
      'order' => 10001,
    ));

    $this->addElement('Hidden', 'order_direction', array(
      'order' => 10002,
    ));

        
    $this->addElements(array(
      $active,
      $badges,
      $sync,
      $sync_tags,
    ));
    
    $this->addDisplayGroup(array('sync','sync_tags'), "grp_sync");
    
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
    
    $listing_type = new Zend_Form_Element_Select('listingtype_id');
    $listing_type
      ->setLabel('Listing Type')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field listingtype_id'))
      ->setMultiOptions($listingtypesOptions)
      ->setAttrib("onchange","loadCategories(this);")
      ;
    
    $category_id = new Zend_Form_Element_Select('category_id');
    $category_id
      ->setLabel('Category')
      ->setAttrib('placeholder','Category')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field category','id' => 'category-wrapper'))
      ->setAttrib("onchange","loadSubCategories(this);")
      ;
    
    $subcategory_id = new Zend_Form_Element_Select('subcategory_id');
    $subcategory_id
      ->setLabel('Sub Category')
      ->setAttrib('placeholder','Sub Category')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field subcategory','id' => 'subcategory-wrapper'))
      ->removeValidator("InArray");
    
    $this->addElements(array(
      $search,
      $listing_type,
      $category_id,
      $subcategory_id,
    ));
    
    $this->addDisplayGroup(array('listingtype_id','category_id','subcategory_id'),'grp1');

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}