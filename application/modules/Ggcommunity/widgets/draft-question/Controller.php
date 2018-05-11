<?php
/**
 * EXTFOX
 *
 * @category   Draft Question
 * @package    Ggcommunity
 * 
 */
class Ggcommunity_Widget_DraftQuestionController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->title =  $this->_getParam('title');
    $this->view->content =  $this->_getParam('content');

    //don't render this widget if subject is published
    if($subject->draft == 0) return $this->setNoRender();
    

  }

}