<?php
/**
 * EXTFOX
 *
 * @category   Top Theorist
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_TopTheoristController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->more = $more = $this->_getParam('more');

    $limit = 10;
    // Should we consider views or comments popular?
    $popularType = $this->_getParam('popularType', 'member');
    if( !in_array($popularType, array('view', 'member')) ) {
      $popularType = 'member';
    }
    $this->view->popularType = $popularType;
    $this->view->popularCol = $popularCol = $popularType . '_count';

    // Get paginator
    $table = Engine_Api::_()->getDbtable('users', 'user');
    $select = $table->select()
      ->where('search = ?', 1)
      ->where('enabled = ?', 1)
      ->where($popularCol . ' >= ?', 0)
      ->order($popularCol . ' DESC')
      ;

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);

    $paginator->setItemCountPerPage($limit);

    //don't render this widget if subject is not published
    if(count($paginator) < 1) return $this->setNoRender();

  }

  
  

}