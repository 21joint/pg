<?php
/**
 * EXTFOX
 *
 * @category   Review Product
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_ReviewProductController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    if( $subject instanceof Ggcommunity_Model_Question ) {
      if($subject->draft == 1)  return $this->setNoRender();
    }
    
    $this->view->title = $this->_getParam('title');
    $this->view->image = $image = $this->_getParam('select_image');


  }

  
  

}