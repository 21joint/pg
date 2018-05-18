<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Ggcommunity Filter
 */

class Ggcommunity_Form_Admin_Manage_Filter extends Engine_Form
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
      ->setMethod('GET')
      //->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
    ;

    // Element: username
    $this->addElement('Text', 'username', array(
      'label' => 'GGCOMMUNITY_MANAGE_QUESTION_USERNAME',
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div')),
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
      ),
      
    ));

    // Element: First Name
    $this->addElement('Text', 'first_name', array(
      'label' => 'GGCOMMUNITY_MANAGE_QUESTION_FIRST_NAME',
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div')),
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
      ),
      
    ));


    // Element: Last Name
    $this->addElement('Text', 'last_name', array(
      'label' => 'GGCOMMUNITY_MANAGE_QUESTION_LAST_NAME',
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div')),
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),   
      ),
      
    ));

    // Element: Email
    $this->addElement('text', 'email', array(
      'label' => 'GGCOMMUNITY_MANAGE_QUESTION_EMAIL',
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div')),
        array('Label', array('tag' => null, 'placement' => 'PREPEND')), 
      ),
      'validators' => array(
        'EmailAddress'
      )
      
    ));

    $levelOptions = array('' => 'Select');
    foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level ) {
      $levelOptions[$level->level_id] = $level->getTitle();
    }

    // Element: Member Level
    $this->addElement('select', 'member_level', array(
      'label' => 'GGCOMMUNITY_MANAGE_QUESTION_MEMBER_LEVEL',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
      'multiOptions' => $levelOptions,
      
    ));


    // Element: Question Title
    $this->addElement('Text', 'question_title', array(
      'label' => 'GGCOMMUNITY_MANAGE_QUESTION_QUESTION_TITLE',
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div')),
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
      ),
      
    ));


    // Element: Approve
    $this->addElement('select', 'approved', array(
      'label' => 'Approved',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
      'multiOptions' => array(
        '-1' => 'All',
        '1' => 'Approved',
        '0' => 'Non-Approved',
      ),
      
    ));

    // Element: Feature
    $this->addElement('select', 'featured', array(
      'label' => 'Featured',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
      'multiOptions' => array(
        '-1' => 'All',
        '1' => 'Featured',
        '0' => 'Non-Featured',
      ),
      
    ));

    // Element: Sponsored
    $this->addElement('select', 'sponsored', array(
      'label' => 'Sponsored',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
      'multiOptions' => array(
        '-1' => 'All',
        '1' => 'Sponsored',
        '0' => 'Non-Sponsored',
      ),
      
    ));
  

    // Element: Open
    $this->addElement('select', 'opened', array(
      'label' => 'Opened',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
      'multiOptions' => array(
        '-1' => 'All',
        '1' => 'Opened',
        '0' => 'Non-Opened',
      ),
      
    ));

    $sortByOptions = array();
    $sortByOptions =  array(
      'question_id' => 'Select',
      'up_vote_count' => 'GGCOMMUNITY_MANAGE_QUESTION_UPVOTE',
      'down_vote_count' => 'GGCOMMUNITY_MANAGE_QUESTION_DOWNVOTE',
      'answer_count' => 'GGCOMMUNITY_MANAGE_QUESTION_ANSWERCOUNT',
      'view_count' => 'GGCOMMUNITY_MANAGE_QUESTION_VIEWCOUNT',

    );

    // Element: Sort By
    $this->addElement('select', 'sort_by', array(
      'label' => 'Sort By',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
      'multiOptions' => $sortByOptions,
      
    ));
   
    $this->addElement('Button', 'submit', array(
      'type' => 'submit',
      'label' => 'Search',
      'decorators' => array(
        'ViewHelper', 
        array('HtmlTag', array('tag' => 'div')),
        array('HtmlTag2', array('tag' => 'div')),
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
      ),
    ));

    $this->addDisplayGroup(array('username','first_name','last_name','email'),'group1');
    $this->addDisplayGroup(array('member_level','question_title','sort_by'),'group2');
    $this->addDisplayGroup(array('approved','featured', 'sponsored', 'opened'),'group3');
    $this->addDisplayGroup(array('submit'),'group4');

    

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    
  }
}