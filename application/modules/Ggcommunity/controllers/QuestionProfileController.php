<?php
class Ggcommunity_QuestionProfileController extends Core_Controller_Action_Standard
{
 
  public function init()
  {
    // Get viewer
    $viewer = Engine_Api::_()->user()->getViewer();
    // only show to member_level if authorized
    if( !$this->_helper->requireAuth()->setAuthParams('ggcommunity', $viewer, 'view_question')->isValid() ) return;

    $subject = null; 
    if( !Engine_Api::_()->core()->hasSubject() && ($id = $this->_getParam('question_id')) ) {
     
      $subject = Engine_Api::_()->getItem('ggcommunity_question', $id);
     
      if( $subject && $subject->getIdentity() ) {
        Engine_Api::_()->core()->setSubject($subject);
      }
    }
    
    $this->_helper->requireSubject(); 

  }

  public function viewAction() {
   
    // get viewer and subject
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->getSubject();

    //  count views if owner is self viewer 
    if( !$subject->getOwner()->isSelf($viewer) ) {
      $subject->view_count++;;
      $subject->save();
    }

    // Render
		$this->_helper->content
      ->setNoRender()
      ->setEnabled()
    ;
  }


  // Edit Action
	public function editAction()
	{
    
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    // check if this member level can edit question
    if( !$this->_helper->requireAuth()->setAuthParams('ggcommunity', null, 'edit_question')->isValid() ) return;

    $old_topics = json_decode($subject->topic, true);
 
    $topics_name = [];
    foreach($old_topics as $topic) {
      
      $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
      $select = $table->select()->where('topic_id = ?', $topic['topic_id']);
      $row = $table->fetchRow($select);
      $topics_name[] = $row->name;
     
    }
    $topics_name = implode(', ', $topics_name);

    // Make sure that edit button will be disabled if viewer hasn't permisson for editing question
    $this->view->can_edit = $can_edit =  Engine_Api::_()->authorization()->isAllowed('ggcommunity', null, 'edit_question');

    $this->view->form = $form = new Ggcommunity_Form_Question_Create();
 
    $values = $subject->toArray();
  
    $question_closed_date = $values['date_closed'];

    if($question_closed_date !== '0000-00-00 00:00:00') {
      $closed_date = date('Y-m-d', strtotime($question_closed_date));
      $form->getElement('date_closed')->setValue($closed_date); 
    }
    
    $form->populate($subject->toArray());
    $form->getElement('tags')->setValue($topics_name);
    
    $translate = Zend_Registry::get('Zend_Translate');

    $form->getElement('submit')->setLabel($translate->translate('Edit'));
    $form->addElement('cancel', 'Cancel',array(
        'class' => 'btn ghost large '
    ));
    $form->removeElement('submit_draft');
    
    // if this member can change close date
    $can_change = Engine_Api::_()->authorization()->isAllowed('ggcommunity', null,'edit_close_date');
    
		// check if its request
		if( !$this->getRequest()->isPost() ) {
			return;
		}
		
		// check if form is valid, get request from the POST Method
		if( !$form->isValid( $this->getRequest()->getPost() ) ) {
			return;	
		}

		// get values from form
    $values = $form->getValues();
    $topics = preg_split('/[,]+/', $values['tags']);

    // if closed date is entered by user write it in the table if, not leave this field empty
    if($can_change == 1 && $values['date_closed'] != '0000-00-00') {
      $values['date_closed'] = $values['date_closed'];
    }

		$db = Engine_Api::_()->getDbtable('questions', 'ggcommunity')->getAdapter();
		$db->beginTransaction();
	
		try
		{

      // set info from array
      $subject->setFromArray($values);
       
      // Set photo
      if( !empty($values['photo']) ) {
        $subject->setPhoto($form->photo);
      }

      $data = [];
      if( $topics ) {
       $topics_id = Engine_Api::_()->ggcommunity()->handleTopics($topics);

       foreach($topics_id as $id) {
        if($id > 0) {
          $data[] = array(
            "topic_id" => $id
          );
        }
       }

       $data = Zend_Json::encode($data);
       $subject->topic = $data;
      }

		  $subject->save();
		  $db->commit();
		} catch( Exception $e ) {
		  $db->rollBack();
		  throw $e;
		}
    
    // redirect to the edited question
		return $this->_helper->redirector->gotoRoute(array('question_id' => $subject->getIdentity()), 'question_profile', true);

  }


  // Delete Question
  public function deleteAction() 
  {
    // In smoothbox
		$this->_helper->layout->setLayout('default-simple');
    
    // check subject and viewer
    $this->view->subject = $subject =  Engine_Api::_()->core()->getSubject();
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    $param = $this->_getParam('page', null);
    // if($param ? )
    // check if logged user is owner for this question or logged user is admin
    if( (!$subject->getOwner()->isAdmin()) || (!$subject->isOwner($viewer)) ) {
      return $this->_helper->requireSubject->forward();
    }

    $this->view->form = $form = new Ggcommunity_Form_Delete();
        
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    // check if form is valid, get request from the POST Method
    if( !$form->isValid( $this->getRequest()->getPost() ) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
  
    $db = Engine_Api::_()->getDbtable('questions', 'ggcommunity')->getAdapter();
    $db->beginTransaction();

    try {
      $subject->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
    
    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('This item has been successfully deleted.');
    return $this->_forward('success' ,'utility', 'core', array(
      'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'ggcommunity_manage', true),
      'messages' => Array($this->view->message)
    ));
  
  }


  // Publish Action
  public function publishAction() 
  {
    
    // In smoothbox
		$this->_helper->layout->setLayout('default-simple');

    // check subject and viewer
    $this->view->subject = $subject =  Engine_Api::_()->core()->getSubject();
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    
    
    // check if logged user is owner for this question or logged user is admin
    if( (!$subject->getOwner()->isAdmin()) || (!$subject->isOwner($viewer)) ) {
      return $this->_helper->requireSubject->forward();
    }

    // render new form for publishing
    $this->view->form = $form = new Ggcommunity_Form_Question_Publish();
        
    if( !$this->getRequest()->isPost() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    // check if form is valid, get request from the POST Method
    if( !$form->isValid( $this->getRequest()->getPost() ) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Form is not valid');
      return;	
    }
  
    $db = Engine_Api::_()->getDbtable('questions', 'ggcommunity')->getAdapter();
    $db->beginTransaction();
  
    if($subject['draft'] == 1) {
      $automatically_close = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.automatically.close');
      $date = date('Y-m-d H:i:s');
      $closed_date = date('Y-m-d H:i:s', strtotime($date . " + ". $automatically_close." day")); 
    } 
  
    try {	
      $subject->draft = 0;
      $subject->open = 1;
      $subject->search = 1;
      $subject->approved = 1;
      $subject->date_closed = $closed_date;
      $subject->save();

      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('This item has been published.');
    return $this->_forward('success' ,'utility', 'core', array(
      'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('question_id' => $subject->getIdentity()), 'question_profile', true),
      'messages' => Array($this->view->message)
    ));

  }

  // Copy link url 
  public function shareAction() {
    // In smoothbox
		$this->_helper->layout->setLayout('default-simple');

    // check subject and viewer
    $this->view->subject = $subject =  Engine_Api::_()->core()->getSubject();
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    // render new form for publishing
    $this->view->form = $form = new Ggcommunity_Form_Question_Share();
    $form->getElement('url')->setValue(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('question_id' => $subject->getIdentity() ), 'question_profile', true));
 
        
    if( !$this->getRequest()->isPost() ) return;

    // check if form is valid, get request from the POST Method
    if( !$form->isValid( $this->getRequest()->getPost() ) ) return;	

    $values = $form->getValues();
    $this->view->url = $url = $values['url'];
    if ($url ? $this->view->smoothboxClose == true : $this->view->smoothboxClose == false );

  }

}
