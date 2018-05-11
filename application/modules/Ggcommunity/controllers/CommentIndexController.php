<?php
class Ggcommunity_CommentIndexController extends Core_Controller_Action_Standard
{
    
    public function indexAction() {

        //get parametars from ajax
        $this->view->comment_id = $comment_id = $this->_getParam('comment_id');
        $this->view->subject_id = $subject_id = $this->_getParam('subject_id');
        $this->view->subject_type = $subject_type = $this->_getParam('subject_type');
        $this->view->subject = $subject = Engine_Api::_()->getItem($subject_type, $subject_id);
        if(!$subject) return;

        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        
        //make sure to get permission for this viewer
        $this->view->permissions = $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);

		if( !empty($subject) ) {
      	 $this->view->subject_guid = $subject->getGuid(false);
		}
		  
        $table = Engine_Api::_()->getDbtable('comments', 'ggcommunity');
        if($subject->getType() == 'ggcommunity_answer' ? $limit = 3 : $limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.answer.page'));
     

        $all = $table->select()
        ->where('parent_type = ?', $subject->getType())
        ->where('parent_id = ?', $subject->getIdentity())
        ->order('comment_id DESC')
        ;

        $all_comments = $table->fetchAll($all);

        $comment_list = array();

        foreach($all_comments as $comment) {

            if($comment->comment_id < $comment_id) {
                $comment_list[] = $comment;
                $counter++; 
            } 
            if($counter == $limit) break;
   
        }
      
        $this->view->paginator = $comment_list;

		$endOfComment = false;
		$nextid = 0;

	  	//Are we at the end?
		if(count($comment_list) < $limit) {
			$endOfComment = true;
			$nextid = 0;
		} else {
			$nextid = $comment_list[count($comment_list)-1]->comment_id;
        }
        // find way to make sure if there are no more comments after view more do not show view more again(this happens when total are 6,9,12 etc comments)


		$this->view->nextid = $nextid;
        $this->view->endOfComment =$endOfComment;
        
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




    // Action for creating comments
    public function createAction()
	{	
        if (!$this->_helper->requireUser()->isValid())
        return;
    
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

        $parent_type = $this->getParam('parent_type');
        $parent_id = $this->getParam('parent_id');

        $object = Engine_Api::_()->getItem($parent_type, $parent_id);


        //check if this memeber level can comment on answer/question
        if($parent_type == 'ggcommunity_question') {
            if( !$this->_helper->requireAuth()->setAuthParams('ggcommunity', null, 'comment_question')->isValid() ) return;
        } else {
            if( !$this->_helper->requireAuth()->setAuthParams('ggcommunity', null, 'comment_answer')->isValid() ) return;
        }
      
        //make sure to get permission for this viewer
        $this->view->permissions = $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);
        
        //Process form
        $form = new Ggcommunity_Form_Comment_Create();

        // check if its request
        if(!$this->getRequest()->getPost()) return;
     
		// check if form is valid, get request from the POST Method
        if( !$form->isValid( $this->getRequest()->getPost() ) ) 
        return;

		// get values from form
        $values = $form->getValues();
     
        $body = $values['body'];


        $table = Engine_Api::_()->getDbTable('comments', 'ggcommunity');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {

            $row = $table->createRow();

            $row->user_id = $viewer->getIdentity();
            $row->parent_type = $parent_type;
            $row->parent_id = $parent_id;
            $row->body = $body;

            $row->save();
            $db->commit();

            $object->comment_count = $object->comment_count +1;
            $object->save();

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        } 
      
        $this->view->comment = $row;
  
    }
  
}