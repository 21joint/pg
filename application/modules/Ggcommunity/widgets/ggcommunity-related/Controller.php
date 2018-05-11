<?php
/**
 * EXTFOX
 *
 * @category   Related Questions
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_GgcommunityRelatedController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->title = $this->_getParam('title');

    // get topic for this subject
    $topics = json_decode($subject->topic, true);

    $ids = [];
    foreach($topics as $topic) {
      $ids[]= $topic['topic_id'];
    };
 
    //find all question that have same topics id with paginator
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('ggcommunity_question')->getQuestionsByTopic($subject->getIdentity(),$ids);
    
    
    //don't render this widget if subject is not published
    if( ($subject->draft == 1) || (count($paginator) < 1) ) return $this->setNoRender();
 

  }

  
  

}
