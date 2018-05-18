<?php
/**
 * EXTFOX
 *
 * @category   New Struggle
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_NewStruggleController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->permissions = $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);

    $this->view->new_struggle = $new_struggle = $this->_getParam('new_struggle');
    $this->view->tooltip = $tooltip = $this->_getParam('tooltip');

  }

  
  

}