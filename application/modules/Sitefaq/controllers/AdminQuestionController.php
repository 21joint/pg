<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminQuestionController.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_AdminQuestionController extends Core_Controller_Action_Admin
{
	//ACTION FOR GLOBAL SETTINGS
  public function manageAction()
  {
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_question');

		//GET VIEWER ID
		$this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    //FORM GENERATION
    $this->view->formFilter = $formFilter = new Sitefaq_Form_Admin_Question_Filter();
    $page = $this->_getParam('page', 1);

    //GET QUESTION TABLE
    $tableQuestion = Engine_Api::_()->getDbtable('questions', 'sitefaq');
    $tableQuestionName = $tableQuestion->info('name');

		//GET USER TABLE
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');

    //MAKE QUERY
    $select = $tableQuestion->select()
										->setIntegrityCheck(false)
                    ->from($tableQuestionName)
										->joinLeft($tableUserName, "$tableQuestionName.user_id = $tableUserName.user_id", array('username', 'email'));

    //GET FORM VALUES
    $values = array();
    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }

    foreach ($values as $key => $value) {
      if (null === $value) {
        unset($values[$key]);
      }
    }

    $values = array_merge(array(
                'order' => 'question_id',
                'order_direction' => 'DESC',
                    ), $values);

    $this->view->assign($values);

		//SET ORDER
		$order = $values['order'];
		$order_direction = $values['order_direction'];
		$select->order("$order $order_direction");

		//SEND ORDER DIRECTION TO TPL
		$this->view->order_direction = !empty($values['order_direction']) ? $values['order_direction'] : 'DESC';

    //GET PAGINATOR
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator->setItemCountPerPage(50);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
	}

	public function answerAction() 
	{ 
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET QUESTION ID
		$this->view->question_id = $question_id = $this->_getParam('question_id');
		$question = Engine_Api::_()->getItem('sitefaq_question', $question_id);

		//IF QUESTION IS ASKED BY VIEWER
		$this->view->self_question = 0;
		if($question->user_id == Engine_Api::_()->user()->getViewer()->getIdentity()) {
			$this->view->self_question = 1;
		}

    //CREATE FORM
    $this->view->form = $form = new Sitefaq_Form_Admin_Question_Answer();

    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

		//GET FORM VALUES
		$values = $form->getValues();

		if(!empty($question->user_id)) {

			//GET USER OBJECT
			$user = Engine_Api::_()->getItem('user', $question->user_id);

			//GET VIEWER DETAIL
			$viewer = Engine_Api::_()->user()->getViewer();

			$msg_subject = Zend_Registry::get('Zend_Translate')->_("Reply to your Question: ").$question->title;

			//CREATE CONVERSATION
			$conversation = Engine_Api::_()->getItemTable('messages_conversation')->send(
				$viewer,
				$user,
				$msg_subject,
				$values['body'],
				NULL
			);

			//SEND NOTIFICATION
			Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification(
				$user,
				$viewer,
				$conversation,
				'message_new'
			);

			//INCREMENT MESSAGES COUNTER
			Engine_Api::_()->getDbtable('statistics', 'core')->increment('messages.creations');

			$question->admin_id = Engine_Api::_()->user()->getViewer()->getIdentity();
			$question->save();
		}
		elseif(!empty($question->anonymous_email)) {

			$email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
			Engine_Api::_()->getApi('mail', 'core')->sendSystem($question->anonymous_email, 'SITEFAQ_QUESTION_ANSWER_EMAIL', array(
					'site_title' => Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 'Advertisement'),
					'question' => $question->title,
					'answer' => $values['body'],
					'email' => $email,
					'queue' => true
			));

			if(isset($values['admin_email']) && !empty($values['admin_email'])) {
				Engine_Api::_()->getApi('mail', 'core')->sendSystem($values['admin_email'], 'SITEFAQ_QUESTION_ANSWER_EMAIL', array(
						'site_title' => Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 'Advertisement'),
						'question' => $question->title,
						'answer' => $values['body'],
						'email' => $email,
						'queue' => true
				));
			}

			$question->admin_id = Engine_Api::_()->user()->getViewer()->getIdentity();
			$question->save();
		}

		$this->_forward('success', 'utility', 'core', array(
			'smoothboxClose' => 10,
			'parentRefresh'=> 10,
			'messages' => array('')
		));
	}

  //ACTION FOR DELETE THE QUESTION
  public function deleteAction()
  {
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET QUESTION ID
		$this->view->question_id = $question_id = $this->_getParam('question_id');

		if( $this->getRequest()->isPost()){	

			//GET THE QUESTION OBJECT
			$question = Engine_Api::_()->getItem('sitefaq_question', $question_id);

			//DELETE QUESTION AND OTHER BELONGINGS
			$question->delete();

			$this->_forward('success', 'utility', 'core', array(
			   'smoothboxClose' => 10,
			   'parentRefresh'=> 10,
			   'messages' => array('')
			));
   	}
		$this->renderScript('admin-question/delete.tpl');
	}
 
  //ACTION FOR MULTI DELETE QUESTIONS
  public function multiDeleteAction()
  {
    if ($this->getRequest()->isPost()) {

			//GET FORM VALUES
      $values = $this->getRequest()->getPost();
      foreach ($values as $key=>$value) {
        if ($key == 'delete_' . $value) {

        	//GET QUESTION ID
          $question_id = (int)$value;

					//GET THE QUESTION OBJECT
					$question = Engine_Api::_()->getItem('sitefaq_question', $question_id);

					//DELETE QUESTION AND OTHER BELONGINGS
					$question->delete();

        }
      }
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
  }

}