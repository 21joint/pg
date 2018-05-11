<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Admin_Manage_FilterListings extends Engine_Form
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

    $displayname = new Zend_Form_Element_Text('displayname');
    $displayname
      ->setLabel('Display Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

    $email = new Zend_Form_Element_Text('email');
    $email
      ->setLabel('Email')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setAttrib("inputType","email");

    $levelOptions = array('' => 'Select');
    foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level ) {
      $levelOptions[$level->level_id] = $level->getTitle();
    }
    
    $level = new Zend_Form_Element_Select('level');
    $level
      ->setLabel('Level')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions($levelOptions);
    
    $enabled = new Zend_Form_Element_Select('enabled');
    $enabled
      ->setLabel('Approved')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
        '-1' => 'Select',
        '0' => 'Not Approved',
        '1' => 'Approved',
      ));
    
    $listing_title = new Zend_Form_Element_Text('listing_title');
    $listing_title
      ->setLabel('Listing Title')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));
    
    $listingtypes = Engine_Api::_()->getDbTable("listingtypes","sitereview")->getAllListingTypes();
    $listingtypesOptions = array('' => 'Select');
    foreach($listingtypes as $listingType){
        $listingtypesOptions[$listingType->getIdentity()] = $listingType->getTitle();
    }
    
    $listing_type = new Zend_Form_Element_Select('listing_type');
    $listing_type
      ->setLabel('Listing Type')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions($listingtypesOptions)
      ->setAttrib("onchange","loadCategories(this);")
      ;
    
    $category_id = new Zend_Form_Element_Select('category_id');
    $category_id
      ->setLabel('Listing Category')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->removeValidator("InArray");
    
    $featured = new Zend_Form_Element_Select('featured');
    $featured
      ->setLabel('Featured')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '1' => 'Yes',
          '0' => 'No'
      ))->setValue("-1");
    
    $sponsored = new Zend_Form_Element_Select('sponsored');
    $sponsored
      ->setLabel('Sponsored')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '1' => 'Yes',
          '0' => 'No'
      ))->setValue("-1");
    
    $new = new Zend_Form_Element_Select('newlabel');
    $new
      ->setLabel('New')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '1' => 'Yes',
          '0' => 'No'
      ))->setValue("-1");
    
    $approved = new Zend_Form_Element_Select('approved');
    $approved
      ->setLabel('Approved')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '1' => 'Yes',
          '0' => 'No'
      ))->setValue("-1");
    
    $status = new Zend_Form_Element_Select('status');
    $status
      ->setLabel('Status')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '0' => 'Only Open Listings',
          '1' => 'Only Close Listings'
      ))->setValue("-1");
    
    $browse_by = new Zend_Form_Element_Select('order');
    $browse_by
      ->setLabel('Browse By')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '' => 'Select',
          'view_count' => 'Most Viewed',
          'listing_id' => 'Most Recent'
      ));

//    $this->addElement('Hidden', 'order', array(
//      'order' => 10001,
//    ));
//
//    $this->addElement('Hidden', 'order_direction', array(
//      'order' => 10002,
//    ));

    $this->addElement('Hidden', 'user_id', array(
      'order' => 10003,
    ));
    
    $this->addElement('Hidden', 'level_id', array(
      'order' => 10004,
    ));


    
    $this->addElements(array(
        $username,
        $displayname,
        $email,
        $level,
        $enabled,
        $listing_type,
        $listing_title,
        $category_id,
        $featured,
        $sponsored,
        $new,
        $approved,
        $status,
        $browse_by,
    ));
    
    
    $this->addDisplayGroup(array('username','displayname','email','level'),'grp1');
    $this->addDisplayGroup(array('enabled','listing_title','listing_type','category_id'),'grp2');
    $this->addDisplayGroup(array('featured','sponsored','newlabel','approved'),'grp3');
    $this->addDisplayGroup(array('status','order'),'grp4');

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}