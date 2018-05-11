<?php

class Ggcommunity_AnswerIndexController extends Core_Controller_Action_Standard
{   

    // function for getting next answer
    public function indexAction() {

        //get parametars from ajax
        $this->view->answer_id = $answer_id = $this->_getParam('answer_id');
        $this->view->ajax_answer = $ajax_answer = Engine_Api::_()->getItem('ggcommunity_answer', $answer_id);
        $this->view->question_id = $question_id = $this->_getParam('subject_id');
        $shown_ids = $this->_getParam('shown_ids');

        $question = Engine_Api::_()->getItem('ggcommunity_question', $question_id);
        if(!$question) return;

        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        
        //make sure to get permission for this viewer
        $this->view->permissions = $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);

		if( !empty($question) ) {
      	 $this->view->subjectGuid = $question->getGuid(false);
		}
		  
        $table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
        $limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.answer.page');

        $select_best = $table->select()
            ->where('parent_type = ?', $question->getType())
            ->where('parent_id = ?', $question->getIdentity())
            ->where('accepted = ?', 1)
            ->limit(1)
        ;
        $this->view->best = $best = $table->fetchRow($select_best);

        $all = $table->select()
            ->where('parent_type = ?', $question->getType())
            ->where('parent_id = ?', $question->getIdentity())
            ->where('accepted != ?', 1)
            //->order('answer_id DESC')
            ->order('up_vote_count DESC')
        ;

        $all_answers = $table->fetchAll($all);
        $answer_list = array();
   
        $counter = 0;
        foreach($all_answers as $answer) {
            if(!in_array($answer->answer_id, $shown_ids))	{
                if($answer->up_vote_count <= $ajax_answer->up_vote_count) {
                    $answer_list[] =  $answer;
                    $counter++;
                }
                if($counter == $l=imit) break;
            }
        }

        foreach($answer_list as $answer) {
            $shown_ids[] = $answer->getIdentity();
        }

        $left_answers = array();
        foreach($all_answers as $answer) {
            if(!in_array($answer->answer_id, $shown_ids))	{  
                $left_answers[] =  $answer;	
            }
        }

        $this->view->paginator = $answer_list;

		$endOfAnswer = false;
		$nextid = 0;

	  	//Are we at the end?
		if(count($answer_list) < $limit) {
			$endOfAnswer = true;
			$nextid = 0;
        } 
        
        if(count($left_answers) == 0) {
            $endOfAnswer = true;
			$nextid = 0;
        }else {
            $nextid = $answer_list[count($answer_list)-1]->answer_id;
        }

        $this->view->shown_ids = $shown_ids;
		$this->view->nextid = $nextid;
        $this->view->endOfAnswer =$endOfAnswer;
        
        //If this viewer has no permission for answering question don't render this form
        if($permissions['answer_question'] != 0) {
            //Generate form for answering questions
            $this->view->form_answer = $form_answer = new Ggcommunity_Form_Answer_Create();
        }
  
        //If this viewer has no permission for commenting question don't render this form
        if($permissions['comment_question'] != 0) {
          // Generate form for commenting answers
          $this->view->form_comment = $form_comment = new Ggcommunity_Form_Comment_Create();
        }
  
        // Generate form for editing anwer(check if it is possible use same form as create answer)
        $this->view->form_answer_edit = $form_answer_edit = new Ggcommunity_Form_Answer_Edit();
    
    }


    // Create Answer
    public function createAction() {

        if (!$this->_helper->requireUser()->isValid())
        return;

        // get viewer
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        //make sure to get permission for this viewer
        $this->view->permissions = $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);

        //check if this viewer can answer question
        if( !$this->_helper->requireAuth()->setAuthParams('ggcommunity', null, 'answer_question')->isValid() ) return;

        //Process form
        $this->view->form = $form = new Ggcommunity_Form_Answer_Create();
  
        //render form for commenting answers only if this viewer can comment_answers
        if($permissions['comment_answer'] != 0) {
            $this->view->form_comment = $form_comment = new Ggcommunity_Form_Comment_Create();
        }
        
        // get params from ajax
        $question_id = $this->getParam('question_id');
        $question = Engine_Api::_()->getItem('ggcommunity_question', $question_id);
        if(!$question) return;

        // check if its request
        if(!$this->getRequest()->getPost()) return;
        
		// check if form is valid, get request from the POST Method
        if( !$form->isValid( $this->getRequest()->getPost() ) ) return;

		// get values from form
        $values = $form->getValues();
        $body = $values['body_create'];

        $table = Engine_Api::_()->getDbTable('answers', 'ggcommunity');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
           
            $answer = $table->createRow();
            $answer->user_id = $viewer->getIdentity();
            $answer->parent_type = $question->getType();
            $answer->parent_id = $question->getIdentity();
            $answer->body = $body;

            // Set photo
            /* if( !empty($values['photo']) ) {
                $answer->setPhoto($_FILES['userfile']);
            } */

            $answer->save();
            $db->commit();
            
            $question->answer_count = $question->answer_count+1;
            $question->save();

        } catch(Exception $e) {
            $db->rollBack();
            throw $e;
        }
        
        $this->view->answer = $answer;
        
    }

    // action for uploading photo in answer
    public function uploadPhotoAction() {
        $viewer = Engine_Api::_()->user()->getViewer();

        $this->_helper->layout->disableLayout();

        if( !Engine_Api::_()->authorization()->isAllowed('album', $viewer, 'create') ) {
        return false;
        }

        if( !$this->_helper->requireAuth()->setAuthParams('album', null, 'create')->isValid() ) return;

        if( !$this->_helper->requireUser()->checkRequire() )
        {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('Max file size limit exceeded (probably).');
        return;
        }

        if( !$this->getRequest()->isPost() )
        {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
        return;
        }
        if( !isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name']) )
        {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
        return;
        }

        $db = Engine_Api::_()->getDbtable('photos', 'album')->getAdapter();
        $db->beginTransaction();

        try
        {
        $viewer = Engine_Api::_()->user()->getViewer();

        $photoTable = Engine_Api::_()->getDbtable('photos', 'album');
        $photo = $photoTable->createRow();
        $photo->setFromArray(array(
            'owner_type' => 'user',
            'owner_id' => $viewer->getIdentity()
        ));
        $photo->save();

        $photo->setPhoto($_FILES['userfile']);

        $this->view->status = true;
        $this->view->name = $_FILES['userfile']['name'];
        $this->view->photo_id = $photo->photo_id;
        $this->view->photo_url = $photo->getPhotoUrl();

        $table = Engine_Api::_()->getDbtable('albums', 'album');
        $album = $table->getSpecialAlbum($viewer, 'ggcommunity');

        $photo->album_id = $album->album_id;
        $photo->save();

        if( !$album->photo_id )
        {
            $album->photo_id = $photo->getIdentity();
            $album->save();
        }

        $auth      = Engine_Api::_()->authorization()->context;
        $auth->setAllowed($photo, 'everyone', 'view',    true);
        $auth->setAllowed($photo, 'everyone', 'comment', true);
        $auth->setAllowed($album, 'everyone', 'view',    true);
        $auth->setAllowed($album, 'everyone', 'comment', true);


        $db->commit();

        } catch( Album_Model_Exception $e ) {
        $db->rollBack();
        $this->view->status = false;
        $this->view->error = $this->view->translate($e->getMessage());
        throw $e;
        return;

        } catch( Exception $e ) {
        $db->rollBack();
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
        throw $e;
        return;
        }
    }
  

}