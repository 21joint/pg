<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Addbadge.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Admin_Badge_Addbadge extends Engine_Form {

  public function init() {

    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    $this  ->setTitle('Add Badge')
    ->setDescription('You can add badge here.');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    
    $this->addElement('Text', 'title', array(
      'label' => 'Title',
      'description' => '',
      'allowEmpty' => FALSE,
      'validators' => array(
        array('NotEmpty', true),
        ),
      ));
    
    $this->addElement('Text', 'credit_count', array(
      'label' => 'Credit Values',
      'description' => '',
      'allowEmpty' => FALSE,
      'validators' => array(
        array('NotEmpty', true),
        array('Int', true),
        ),
      ));

    $this->addElement('textarea', 'description', array(
      'label' => 'Description',
      'description' => '',
      'allowEmpty' => FALSE,
      'attribs' => array('rows' => 24, 'cols' => 180),
      'validators' => array(
        array('NotEmpty', true),
        ),'filters' => array(
        'StripTags',
                                //new Engine_Filter_HtmlSpecialChars(),
        new Engine_Filter_EnableLinks(),
        new Engine_Filter_Censor(),
        ),
        ));

    $this->addElement('File', 'photo', array(
      'label' => 'Image',
      'description'=>'',
      'required' => true,
      'allowEmpty' => false,
      ));
    $this->photo->addValidator('Extension', false, 'jpg,jpeg,png,gif');

    
    
    $this->addElement('Button', 'submit', array(
      'label' => 'Create Badge',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
      ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'href' => '',
      'onClick'=> 'javascript:parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
        )
      ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');

  }

}
