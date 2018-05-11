<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Send.tpl 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Admin_Credits_Send extends Engine_Form{

  public function init() {


    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    $this  ->setTitle('Send Credits')
    ->setDescription('You can send credits to site users as bonus.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    
    $levelOptions = array();
    $levelOptions[0] = "All member levels";
    foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level ) {
      $levelOptions[$level->level_id] = $level->getTitle();
    }

    // Element: level_id
    $this->addElement('Select', 'member_level', array(
      'label' => 'Member Level',
      'multiOptions' => $levelOptions,
      'onchange'=>'onLevelChange(this)'
      ));

    $this->addElement('Select','member',array(
      'label'=>'Send To',
      'multiOptions'=> array(
        1 => 'All Members',
        0 => 'Specific User'),
      'onchange'=>'onMemberChange(this)'
      ));
    $this->addElement('Text', 'user_name', array(
      'label' => 'Member Name',
      'description' => 'Start typing the name of the user.',
      'autocomplete' => 'off'));

    $this->addElement('Hidden', 'user_id', array(
      'order' => 200,
      'filters' => array(
        'HtmlEntities'
        ),
      ));
    Engine_Form::addDefaultDecorators($this->user_id);

    $this->addElement('Text', 'credit_point', array(
      'label' => 'Credit Values',
      'description' => '',
      'required' => true,
      'validators'=>array(
        array('NotEmpty', true),
        array('Int', true),
        new Engine_Validate_AtLeast(1),

        ),
      ));
    $this->addElement('textarea', 'reason', array(
      'label' => 'Reason',
      'description' => '',
      'allowEmpty' => FALSE,
      'attribs' => array('rows' => 24, 'cols' => 180, 'style' => 'width:300px; max-width:400px;height:120px;'),
      'validators' => array(
        array('NotEmpty', true),
        ),'filters' => array(
        'StripTags',
                                //new Engine_Filter_HtmlSpecialChars(),
        new Engine_Filter_EnableLinks(),
        new Engine_Filter_Censor(),
        ),
        ));



    $this->addElement('MultiCheckbox', 'send_mail', array(
      'label' => 'Send Email to Users',
      'multiOptions' => array(1=>"Yes"),
      'value' => 1,
      'onchange'=>'onMailChange(this)',
      ));

    $this->addElement('textarea', 'message', array(
      'label' => 'Message to be Send with Email',
      'attribs' => array('rows' => 24, 'cols' => 180, 'style' => 'width:300px; max-width:400px;height:120px;'),
      'filters' => array(
        'StripTags',  //new Engine_Filter_HtmlSpecialChars(),
        new Engine_Filter_EnableLinks(),
        new Engine_Filter_Censor(),
        ),
      
      ));
    $this->addElement('Button', 'submit', array(
      'label' => 'Send Credits',
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
