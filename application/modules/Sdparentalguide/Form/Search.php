<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Search extends Engine_Form
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
        'class' => 'global_form_box sd_listing_search',
      ))
      ->setMethod('GET');

    $searchHeading = new Engine_Form_Element_Heading('search');
    $searchHeading
      ->setLabel('Search')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND','class' => 'heading_label'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field search'));
    
    $username = new Zend_Form_Element_Text('username');
    $username
      ->setLabel('User Name')
      ->setAttrib('placeholder','User Name')      
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field username'));

    $firstName = new Zend_Form_Element_Text('first_name');
    $firstName
      ->setLabel('First Name')
      ->setAttrib('placeholder','First Name')   
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field first_name'));
    
    $lastName = new Zend_Form_Element_Text('last_name');
    $lastName
      ->setLabel('Last Name')
      ->setAttrib('placeholder','Last Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field last_name'));

    $listingtypes = Engine_Api::_()->getDbTable("listingtypes","sitereview")->getAllListingTypes();
    $listingtypesOptions = array('' => 'Listing Type');
    foreach($listingtypes as $listingType){
        $listingtypesOptions[$listingType->getIdentity()] = $listingType->getTitle();
    }
    
    $listing_type = new Zend_Form_Element_Select('listing_type');
    $listing_type
      ->setLabel('Listing Type')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field listing_type'))
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

    $this->addElement('Hidden', 'user_id', array(
      'order' => 10003,
    ));
    
    $this->addElements(array(
        $searchHeading,
        $username,
        $firstName,
        $lastName,
        $listing_type,
        $category_id,
        $subcategory_id,
    ));
    
    
    $this->addDisplayGroup(array('search','username','first_name','last_name','listing_type','category_id','subcategory_id'),'grp1');
    
    
    $includeHeading = new Engine_Form_Element_Heading('include_heading');
    $includeHeading
      ->setLabel('Include')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND','class' => 'heading_label'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field'));
    
    $include = new Engine_Form_Element_Radio('include');
    $include
      ->setLabel('Include')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field include'))
      ->setMultiOptions(array('all' => "All", 'approved' => 'Approved', 'unapproved' => 'Not Approved'))
      ->setValue("all");
    
    $complete = new Engine_Form_Element_Radio('complete');
    $complete
      ->setLabel('Complete')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field complete'))
      ->setMultiOptions(array('all' => "All", 'complete' => 'Complete', 'incomplete' => 'Not Complete'))
     ->setValue("all");
    
    $this->addElements(array(
        $includeHeading,
        $include,
        $complete
    ));
    
    $this->addDisplayGroup(array('include_heading','include','complete'),'grp2');
    
    
    $sortHeading = new Engine_Form_Element_Heading('sort_heading');
    $sortHeading
      ->setLabel('Sort By')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND','class' => 'heading_label'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field'));
    
    $sort = new Engine_Form_Element_Radio('sort');
    $sort
      ->setLabel('Sort')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field sort'))
      ->setMultiOptions(array('listing_id' => "Most Recent", 'creation_date' => 'Date Created', 'modified_date' => 'Date Updated'))
      ->setValue("listing_id");
    
    
    $this->addElements(array(
        $sortHeading,
        $sort,
    ));
    
    $this->addElement('Button', 'search', array(
      'type' => 'button',
      'label' => 'Search',
      'ignore' => true,
      'onclick' => 'searchListings(this);'
    ));
    
    $this->search->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field search'));
    
    $this->addElement('Button', 'save_search', array(
      'type' => 'button',
      'label' => 'Save & Search',
      'ignore' => true,
      'onclick' => 'saveNSearchListings(this);'
    ));
    
    $this->save_search->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field save_search'));
    
    $this->addDisplayGroup(array('sort_heading','sort','search','save_search'),'grp3');

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}