<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Stat.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Form_Admin_Manage_Stat extends Engine_Form
{
  protected $_field;

  public function init()
  { 
    $this
    ->setMethod('post')
    ->setAttrib('class', 'global_form_box');
        
    // prepare severities
		$status = Engine_Api::_()->getDbtable('status', 'feedback')->getStatus();
    if (count($status)!=0) {
    	$status_prepared[0]= "";
      foreach ($status as $stat) {
        $status_prepared[$stat->stat_id]= $stat->stat_name;
      }

      // category field
      $this->addElement('Select', 'stat_id', array(
            'label' => 'Status',
            'multiOptions' => $status_prepared
      ));
    }
      
    $this->addElement('Textarea', 'status_body', array(
      'label' => 'Comment', 'style' => 'width:200px;',
      'filters' => array(
        'StripTags',
        new Engine_Filter_HtmlSpecialChars(),
        new Engine_Filter_EnableLinks(),
        new Engine_Filter_Censor(),
      ),
    ));
    
    $id = new Zend_Form_Element_Hidden('id');

    $this->addElements(array(
      $id,
      
    ));
    
    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Status',
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

  public function setField($stat)
  { 
    $this->_field = $stat;
    // Set up elements
		if(!empty($stat['stat_id'])) {
			$this->stat_id->setValue($stat['stat_id']);
		}
		if(!empty($stat['status_body'])) {
			$this->status_body->setValue($stat['status_body']);
		}

    // @todo add the rest of the parameters
  }
}
