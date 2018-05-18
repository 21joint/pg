<?php
/**
 * EXTFOX
 *
 * @category   Search Struggle
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_SearchStruggleController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    // get viewer and subject
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

    $this->view->form = $form = new Ggcommunity_Form_Search(); 
   
    // Check form validity?
    $values = array();
    if ($form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
    }

        $this->view->title = $this->_getParam('title');
        $this->view->description = $this->_getParam('description');
       
        $this->view->image = $this->_getParam('select');

  }

}