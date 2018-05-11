<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Create.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_Form_Admin_Collection_Search_Create extends Engine_Form {
  protected $_bgcolor = '#000000';
  protected $_photoRequired = true;
  protected function setBackGroundColor($color) {
    $this->_bgcolor = $color;
    return $this;
  }

  public function init() {

    // Init form
    $this
      ->setTitle('Add New Search Option For Search List')
      ->setDescription('Create a new search option which are display in search list.')
      ->setAttrib('class', 'global_form')
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;


    // Init name
    $this->addElement('Text', 'title', array(
      'label' => 'Title',
      'maxlength' => '40',
      'allowEmpty' => false,
      'required' => true,
      'filters' => array(
        new Engine_Filter_HtmlSpecialChars(),
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63')),
      )
    ));
    $this->addElement('Text', 'keyword', array(
      'label' => 'Search Word',
      'description' =>  'By this all the stickers related to this search word will get listed.',
      'maxlength' => '40',
      'allowEmpty' => false,
      'required' => true,
      'filters' => array(
        new Engine_Filter_HtmlSpecialChars(),
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63')),
      )
    ));
    //COLOR VALUE FOR BACKGROUND COLOR
    $this->addElement('Text', 'background_color', array(
      'decorators' => array(
        array('ViewScript', array(
            'viewScript' => '_formImageRainbowSearchBackground.tpl',
            'bgcolor' => $this->_bgcolor,
            'class' => 'form element'
          )))
    ));

    $this->addElement('File', 'photo', array(
        'label' => 'Icon',
        'required' => $this->_photoRequired,
      ));
    $this->photo->addValidator('Extension', false, 'jpg,png,gif,jpeg');

    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Save',
      'type' => 'submit',
      'order' => 999
    ));
  }

}
