<?php
/**
 * EXTFOX
 *
 * @category   Unanswered Struggle
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_UnansweredStrugglesController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->title = $title = $this->_getParam('title');
    $this->view->upvote = $upvotes = $this->_getParam('show_votes');

    $limit = 10;
    $params = array(
      'front' => array(
        'param' => 'unanswered'
      )
    );
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('ggcommunity_question')->getQuestionsPaginator($params);
    $paginator->setItemCountPerPage($limit);

    //don't render this widget if subject is not published
    if(count($paginator) < 1) return $this->setNoRender();

  }

  
  

}