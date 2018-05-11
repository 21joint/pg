<?php

class Ggcommunity_AnswerProfileController extends Core_Controller_Action_Standard
{
  
  public function init()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;

    $subject = null;
    if( !Engine_Api::_()->core()->hasSubject() && ($id = $this->_getParam('answer_id')) ) {
      $subject = Engine_Api::_()->getItem('ggcommunity_answer', $id);
      if( $subject && $subject->getIdentity() ) {
        Engine_Api::_()->core()->setSubject($subject);
      }
    }
    
    $this->_helper->requireSubject(); 

  }

  // Edit Answer
  public function editAction() {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer(); 
    $subject = Engine_Api::_()->core()->getSubject();

    if(!$subject->getOwner()->isSelf($viewer) && !$viewer->isAdmin() ) return;

    //Process form
    $form = new Ggcommunity_Form_Answer_Edit();

    // check if its request
		if(!$this->getRequest()->getPost()) return;
        
		// check if form is valid, get request from the POST Method
		if( !$form->isValid( $this->getRequest()->getPost() ) ) 	return;

		// get values from form
    $values = $form->getValues();
    $body = $values['body'];

    $subject->body = $body;
    $subject->save();

    $this->view->answer = $subject; 

  }

  // Delete Answer
  public function deleteAction() {

    // smoothbox
    $this->_helper->layout->setLayout('default-simple');

    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $question = Engine_Api::_()->getItem('ggcommunity_question',$subject->parent_id);

    if(!$subject->getOwner()->isSelf($viewer) && !$viewer->isAdmin() ) return;

    $this->view->form = $form = new Ggcommunity_Form_Delete();

    if( !$this->getRequest()->isPost() ) return;

    // check if form is valid, get request from the POST Method
    if( !$form->isValid( $this->getRequest()->getPost() ) ) return;	
  
    $db = Engine_Api::_()->getDbtable('answers', 'ggcommunity')->getAdapter();
    $db->beginTransaction();

    try {	
      $subject->delete();
      $db->commit();
      // make sure to decrease counter for answers
      $this->view->count = $question->answer_count = $question->answer_count-1;
      $question->save();
      // tell smoothbox to close
      $this->view->status  = true;
      $this->view->message=Zend_Registry::get('Zend_Translate')->_('This item has been successfully deleted.');
      $this->view->smoothboxClose = true;
      return $this->render('deleted');

    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
      $this->view->status = false;
    }
    

  }

  public function bestAction() {

    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();
    $object = $subject->getParent();

    if( !$this->_helper->requireAuth()->setAuthParams('ggcommunity', $viewer, 'best_answer')->isValid() ) return;

    $this->view->form = $form = new Ggcommunity_Form_Answer_Best();

    // check if its post
    if( !$this->getRequest()->isPost() ) return;

    // check if form is valid, get request from the POST Method
    if( !$form->isValid( $this->getRequest()->getPost() ) ) return;	

    //find best (accepeted) answer for this question
    $best = Engine_Api::_()->getItemTable('ggcommunity_answer')->getBest($subject->parent_id);

    // if there is best answer for this question unmark it
    if($best) {
      $this->view->best = $best;
      $best->accepted = 0;
      $best->save();
    }

    $db = Engine_Api::_()->getDbtable('answers', 'ggcommunity')->getAdapter();
    $db->beginTransaction();

    try {	

      $subject->accepted = 1;
      $subject->save();
      $db->commit();

      if($object->accepted_answer == 0) {
        $object->accepted_answer = 1;
        $object->save();
      }

      // tell smoothbox to close
      $this->view->status  = true;
      $this->view->message= Zend_Registry::get('Zend_Translate')->_('This item has been chosen as Best.');
      $this->view->smoothboxClose = true;
      return $this->render('bestAnswer');

    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
      $this->view->status = false;
    }

  }
 
  

}
