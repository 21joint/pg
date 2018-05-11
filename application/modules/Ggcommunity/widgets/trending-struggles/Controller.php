<?php
/**
 * EXTFOX
 *
 * @category   Trending Struggles
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_TrendingStrugglesController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->title = $this->_getParam('title');

    $limit = 10;
    $params = array(
      'front' => array(
        'param' => 'trending'
      )
    );
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('ggcommunity_question')->getQuestionsPaginator($params);
    $paginator->setItemCountPerPage($limit);

    //don't render this widget if subject is not published
    if(count($paginator) < 1) return $this->setNoRender();

  }

  
  

}