<?php
/**
 * EXTFOX
 *
 * @category   Featured Topics
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_FeaturedTopicsController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->title = $this->_getParam('title');

    $limit = 10;
    $this->view->paginator = $paginator = Engine_Api::_()->ggcommunity()->getFeaturedTopics($limit);

    //don't render this widget if there are no featured topics
    if(count($paginator) < 1) return $this->setNoRender();

  }

  
  

}
