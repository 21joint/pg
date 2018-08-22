<?php

class Ggcommunity_QuestionIndexController extends Core_Controller_Action_Standard
{
  
  public function createAction()
	{	
		
		if (!$this->_helper->requireUser()->isValid())
    return;

		$viewer = Engine_Api::_()->user()->getViewer();
    $user_id = $viewer->getIdentity();
    
    // check if this member level can create question
    if( !$this->_helper->requireAuth()->setAuthParams('ggcommunity', null, 'create_question')->isValid() ) return;

    $form = $this->view->form = new Ggcommunity_Form_Question_Create();

    // if this memeber level can approve automatically his own question
    $can_approve = Engine_Api::_()->authorization()->isAllowed('ggcommunity', null, 'approve_question');
    
    // if this member can change close date 
    $can_change = Engine_Api::_()->authorization()->isAllowed('ggcommunity', null, 'edit_close_date');
  
    // Render
		$this->_helper->content
      //->setNoRender()
      ->setEnabled()
    ;

		// check if its request
		if(!$this->getRequest()->getPost())	return;

		// check if form is valid, get request from the POST Method
		if( !$form->isValid( $this->getRequest()->getPost() ) )	return;

		// get values from form
    $values = $form->getValues();

    $topics = preg_split('/[,]+/', $values['tags']);

    if( empty($values['user_id']) ) {
      $values['user_id'] = $user_id;
    }

    if($can_approve == 1) {
      $values['approved'] = 1;

      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
      $time = time();
      date_default_timezone_set($oldTz);

      $values['approved_date'] = date('y-m-d h:m:s', $time);
    } else {
      $values['approved'] = 0;
    }

    // if closed date is entered by user write it in the table if, not leave this field empty
    if($can_change == 1 && !empty($values['date_closed'])) {

      $date = strtotime($values['date_closed']);
      $date = date("Y-m-d H:m:s", $date);
      $current_date  = date('Y-m-d H:i:s');
      if(strtotime($date)  >= strtotime($current_date)) {
        $values['date_closed'] = $date;
      } else {
        $form->getElement('date_closed')->setErrors(array('Choosen date is smaller than current date. Please choose date greater than today ' . date("Y-m-d")));
        return;
      }
    
    } 
  
    // If user can't change closed date or deside to not change closed date get automacally closed date and add to current date
    if($can_change != 1 || empty($values['date_closed'])) {
      $automatically_close = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.automatically.close');
      $date = date('Y-m-d H:i:s');
      $closed_date = date('Y-m-d H:i:s', strtotime($date . " + ". $automatically_close." day")); 
      $values['date_closed'] = $closed_date;
    }
 

    // if this question is saved as draft make sure that search, open and approved value will be 0
    if($values['draft'] == 1) {
      $values['date_closed'] = NULL;
      $values['approved'] = 0;
      $values['search'] = 0;
      $values['open'] = 0;
    } else {
      $values['search'] = 1;
      $values['open'] = 1;
    }

    $table = Engine_Api::_()->getDbtable('questions', 'ggcommunity');
		$db = $table->getAdapter();
		$db->beginTransaction();

		try {

      $question = $table->createRow(); 
 
      $question->setFromArray($values);

      // Set photo
      if( !empty($values['photo']) ) {
        $question->setPhoto($form->photo);
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

        // topic create into json
        $data = Zend_Json::encode($data);
        $question->topic = $data;
      }
 

      $question->save();
      $db->commit();
      
      Engine_Api::_()->ggcommunity()->addTopicMap($question, $topics_id);
		  
		} catch( Exception $e ) {
		  $db->rollBack();
		  throw $e;
		}

    // redirect to the new question
    return $this->_helper->redirector->gotoRoute(array('question_id' => $question->getIdentity()), 'question_profile', true);

  }

  public function topicAction() {

    $limit = 100;
    $text = $this->_getParam('text');

    $topics = Engine_Api::_()->ggcommunity()->getTopicsByText($text, $limit);
    $data = [];

    foreach( $topics as $topic ) {
      $data[] = array(
        'id' => $topic->topic_id,
        'label' => $topic->name
      );
    }

    if ($this->_getParam('sendNow', true)) {
      return $this->_helper->json($data);
    } else {
      $this->_helper->viewRenderer->setNoRender(true);
      $data = Zend_Json::encode($data);
      $this->getResponse()->setBody($data);
    }
    
  }

  //  View Action
  public function listAction() {

    // get viewer and subject
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->setSubject($viewer);;

    $this->view->type = $type = $this->_getParam('type', null);

    // Render
		$this->_helper->content
      ->setNoRender()
      ->setEnabled()
    ;

  }

  // Browse Action
  public function browseAction() {

    // get viewer and subject
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->setSubject($viewer);

    $param = $this->_getParam('param', null);

    $this->view->form = $form = new Ggcommunity_Form_Search();

    $form->getElement('submit')->setLabel('Search');
    // Check form validity?
    $values = array();
    if ($form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
    }

    $this->view->query = $values['query'];

    $params = array(
      'front' =>array(
        'query' => $values['query'],
        'param' => $param
      )
    );

    $page = $this->_getParam('page',1);
    $limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.question.page');
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('ggcommunity_question')->getQuestionsPaginator($params);

    $this->view->paginator->setItemCountPerPage($limit);
    $this->view->paginator->setCurrentPageNumber($page);

    $this->view->params = $test = array_filter($params['front']);

    // Render
		$this->_helper->content
      //->setNoRender()
      ->setEnabled()
    ;

  }

  public function leaderboardAction() {

    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->setSubject($viewer);

    
    
    // Render
		$this->_helper->content
      //->setNoRender()
      ->setEnabled()
    ;

  }

  public function manageAction() {
  
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->setSubject($viewer);


    $this->view->form = $form = new Ggcommunity_Form_Search();
    $form->getElement('submit')->setLabel('Search');
    $form->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'ggcommunity_manage'));

    // Check form validity?
    $values = array();
    if ($form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
    }

    $this->view->query = $values['query'];

    $params = array(
      'front' =>array(
        'query' => $values['query'],
        'owner' => true
      )
    );

    $items_per_page = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.question.page');
    $page = $this->_getParam('page',1);
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('ggcommunity_question')->getQuestionsPaginator($params);
    $paginator->setItemCountPerPage($items_per_page);
    $paginator->setCurrentPageNumber($page);

    $this->view->params = $test = array_filter($params['front']);

    // Render
    $this->_helper->content
      //->setNoRender()
      ->setEnabled()
    ;
    
  }


}
