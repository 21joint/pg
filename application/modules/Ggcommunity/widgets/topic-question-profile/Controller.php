<?php
/**
 * EXTFOX
 *
 * @category   Topic Question Profile
 * @package    Ggcommunity
 * 
 */
class Ggcommunity_Widget_TopicQuestionProfileController extends Engine_Content_Widget_Abstract
{


  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    //make sure to get permission for this viewer
    $this->view->permissions = $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);
   
    // get all answers for this subject -> this should be created as API function in Modal DbTable Answer, getAnswer() function with parametars for which question, page(or answer_id for view more) etc
//    $answer_table = Engine_Api::_()->getDbTable('answers', 'ggcommunity');
//    $select = $answer_table->select()
//      ->where('parent_type = ?', $subject->getType())
//      ->where('parent_id = ?', $subject->getIdentity())
//    ;
//    $this->view->answers = $answers = $answer_table->fetchAll($select);
// 
//    $this->view->comments = $comments = Engine_Api::_()->getItemTable('ggcommunity_comment')->getComments($subject, 'question');
     
    
  }

  
}