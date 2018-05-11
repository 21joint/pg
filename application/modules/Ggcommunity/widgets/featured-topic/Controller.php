<?php
/**
 * EXTFOX
 *
 * @category   Featured Topics
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_FeaturedTopicController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->title = $this->_getParam('title');

    $limit = 10;
    $this->view->paginator = $paginator = Engine_Api::_()->ggcommunity()->getFeaturedTopics($limit);

    if($subject instanceof Ggcommunity_Model_Question) {
       //don't render this widget if subject is not published or if there are no featured topics
      if( ($subject->draft == 1) || (count($paginator) < 1) ) return $this->setNoRender();
    } else {
      if(  count($paginator) < 1 ) return $this->setNoRender();
    }
   

  }

  
  

}
