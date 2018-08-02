<?php
/**
 * EXTFOX
 *
 * @category   Ggcommunity Answer Content
 * @package    Ggcommunity
 * 
 */
class Ggcommunity_Widget_AnswerContentController extends Engine_Content_Widget_Abstract
{


  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    //don't render this widget if subject is not published
    if($subject->draft == 1) return $this->setNoRender();

    //make sure to get permission for this viewer
    $this->view->permissions = $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);


    /* this is for first x answers when widget is loaded
      * take answers per page from core_settings and list them if there are more than show view more
      * View more work on two parametars nextId (id from last shown answer) and endofAnswer(if there are more to show or not)
    */
    if( !empty($subject) ) {
      $this->view->subjectGuid = $subject->getGuid(false);
    }

//    $table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
//    $limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.answer.page');
//
//    $select_best = $table->select()
//      ->where('parent_type = ?', $subject->getType())
//      ->where('parent_id = ?', $subject->getIdentity())
//      ->where('accepted = ?', 1)
//      ->limit(1)
//    ;
//    $this->view->best = $best = $table->fetchRow($select_best);

//    $select = $table->select()
//      ->where('parent_type = ?', $subject->getType())
//      ->where('parent_id = ?', $subject->getIdentity())
//      ->where('accepted != ?', 1)
//      //->order('answer_id DESC')
//      ->order('up_vote_count DESC')
//      ->limit($limit)
//    ;
//
//    $this->view->paginator = $answers = $table->fetchAll($select);
//
//    $shown_ids = [];
//    foreach($answers as $answer) {
//      $shown_ids[] = $answer->getIdentity();
//    }
//    $this->view->shown_ids = $shown_ids;
//
//    // Parametars for view more
//    $nextid = null;
//    $endOfAnswer = false;
//
//    // Are we at the end?
//    if( count($answers) < $limit ) {
//      $endOfAnswer = true;
//      $nextid = 0;
//    } else {
//      $nextid =  $answers[$limit-1]->answer_id;  
//      
//    }
//    
//    if($subject->answer_count - $limit < 1) {
//      $nextid = 0;
//    }
//
//    
//    $this->view->nextid = $nextid;
//    $this->view->endOfAnswer = $endOfAnswer;

    //If this viewer has no permission for answering question don't render this form
    if($permissions['answer_question'] != 0) {
      //Generate form for answering questions
      $this->view->form_answer = $form_answer = new Ggcommunity_Form_Answer_Create();
    }

    //If this viewer has no permission for commenting answers don't render this form
    if($permissions['comment_answer'] != 0) {
      // Generate form for commenting answers
      $this->view->form_comment = $form_comment = new Ggcommunity_Form_Comment_Create();
    }

    // Generate form for editing anwer(check if it is possible use same form as create answer)
    $this->view->form_answer_edit = $form_answer_edit = new Ggcommunity_Form_Answer_Edit();
   
    
  }

  
}