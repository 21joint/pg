<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Answer.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Admin_Question_Answer extends Engine_Form
{

  public function init()
  {  
		$this
		->setTitle('Answer This Question')
		->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
		
		$question = Zend_Controller_Front::getInstance()->getRequest()->getParam('question', '');
		$user_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('user_id', '');

		$answer_description = '';
		if($user_id) {
			$answer_description = 'Your answer will be sent as a site message to the user. If the email notification for incoming message is enabled, the user will also get an email notification for your answer.';
		}

		$this->addElement('dummy', 'title', array(
			'label' => 'Question:',
			'description' => urldecode($question),
		));

		$filter = new Engine_Filter_Html();
		$this->addElement('Textarea', 'body', array(
			'label' => 'Enter the answer:',
			'description' => $answer_description,
			'required' => true,
			'allowEmpty' => false,
			'attribs' => array('rows'=>34, 'cols'=>80, 'style'=>'height:250px;max-height:none;width:500px;max-width:none;'),
			'filters' => array(
				$filter,
				new Engine_Filter_Censor(),
			),
		));

		if(empty($user_id)) {
			$this->addElement('Text', 'admin_email', array(
				'description' => 'Your answer will be sent as email to the visitor. To send a copy to yourself, enter your email address below.',
				'required' => false,
				'allowEmpty' => true,
				'validators' => array(
					array('NotEmpty', true),
					array('EmailAddress', true))
			));

      $this->admin_email->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);
		}

    $this->addElement('Button', 'submit', array(
      'label' => 'Answer',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array(
        'ViewHelper',
      ),
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
			'onclick'=> 'javascript:parent.Smoothbox.close()',
      'link' => true,
      'prependText' => ' or ',
      'decorators' => array(
        'ViewHelper',
      ),
    ));

    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      ),
    ));
  }

}
