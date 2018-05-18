<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Filterblockuser.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Form_Admin_Manage_Filterblockuser extends Engine_Form
{
  	public function init()
  	{
	    $this->clearDecorators()
	      ->addDecorator('FormElements')
	      ->addDecorator('Form')
	      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
	      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'));
	
	    $this
	      ->setAttribs(array(
	        'id' => 'filter_form',
	        'class' => 'global_form_box',
	      ));
	
	    $username = new Zend_Form_Element_Text('username');
	    $username
	      ->setLabel('Username')
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
	      ->addDecorator('HtmlTag', array('tag' => 'div'));
	
	    $levelMultiOptions = array(0 => ' ');
	
	    $levels = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll();
	    foreach( $levels as $row )
	    {
	      $levelMultiOptions[$row->level_id] = $row->getTitle();
	    }
	
	    $level_id = new Zend_Form_Element_Select('level_id');
	    $level_id
	      ->setLabel('Level')
	      ->clearDecorators()
	      ->addDecorator('ViewHelper')
	      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
	      ->addDecorator('HtmlTag', array('tag' => 'div'))
	      ->setMultiOptions($levelMultiOptions);
	
	    $submit = new Zend_Form_Element_Button('search', array('type' => 'submit'));
	    $submit
	      ->setLabel('Search')
	      ->clearDecorators()
	      ->addDecorator('ViewHelper')
	      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons'))
	      ->addDecorator('HtmlTag2', array('tag' => 'div'));
	
	    $this->addElement('Hidden', 'order', array(
	      'order' => 10001,
	    ));
	
	    $this->addElement('Hidden', 'order_direction', array(
	      'order' => 10002,
	    ));
	
	    
	    $this->addElements(array(
	      $username,
	      $email,
	      $level_id,
	      $submit,
	    ));
	
	    // Set default action
	    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    }
}
