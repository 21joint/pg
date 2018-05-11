<?php
/**
 * EXTFOX
 *
 * @category   Ggcommunity Answers
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_GgcommunityAnswerController extends Engine_Content_Widget_Abstract
{
  public function init() {

  }

  public function indexAction()
  {
    // Get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    //don't render this widget if subject is not published
    if($subject->draft == 1) return $this->setNoRender();

   


  }

}
