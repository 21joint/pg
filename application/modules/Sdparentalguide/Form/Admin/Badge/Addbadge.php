<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_Form_Admin_Badge_Addbadge extends Engine_Form {

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
    
    $this->addElement("Text",'gg_contribution_level',array(
        'label' => 'Credibility Level',
        'required' => true,
        'allowEmpty' => false,
        'validators' => array(
            array('NotEmpty', true),
            array('Int', true),
        ),
    ));
    
    $levelOptions = array('' => '');
    foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level ) {
      $levelOptions[$level->level_id] = $level->getTitle();
    }
    
    $this->addElement("Select",'gg_level_id',array(
        'label' => 'Profile Level',
        'multiOptions' => $levelOptions,
        'required' => true,
        'allowEmpty' => false
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
