<?php
/**
 * EXTFOX
 *
 * @category   Ggcommunity Question Comment Content
 * @package    Ggcommunity
 * 
 */
class Ggcommunity_Widget_QuestionCommentContentController extends Engine_Content_Widget_Abstract
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

    // Generate form for editing anwer(check if it is possible use same form as create answer)
    $this->view->form_answer_edit = $form_answer_edit = new Ggcommunity_Form_Answer_Edit();

   
    //If this viewer has no permission for commenting question don't render this form
    if($permissions['comment_question'] != 0) {
      // Generate form for commenting answers
      $this->view->form_comment = $form_comment = new Ggcommunity_Form_Comment_Create();
    } else {
      return;
    }

    if( !empty($subject) ) {
      $this->view->subjectGuid = $subject->getGuid(false);
    }

    $table = Engine_Api::_()->getDbtable('comments', 'ggcommunity');
    $limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.answer.page');

    $select = $table->select()
      ->where('parent_type = ?', $subject->getType())
      ->where('parent_id = ?', $subject->getIdentity())
      ->order('comment_id DESC')
      ->limit($limit)
    ;
    $this->view->paginator = $comments = $table->fetchAll($select);

    // Parametars for view more
    $nextid = null;
    $endOfComment = false;

    // Are we at the end?
    if( count($comments) < $limit ) {
      $endOfComment = true;
      $nextid = 0;
    } else {
      $nextid =  $comments[$limit-1]->comment_id;  
      
    }
    
    if($subject->comment_count - $limit < 1) {
      $nextid = 0;
    }
  
    $this->view->nextid = $nextid;
    $this->view->endOfComment = $endOfComment;
    
  }

  
}