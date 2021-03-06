<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxMyGuidesController extends Engine_Content_Widget_Abstract {

  public function indexAction() { 

    $feedType = $this->_getParam('type', null);

    // Don't render this if not authorized
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    if( !Engine_Api::_()->core()->hasSubject()  || $viewer->getIdentity() < 1 ) {
      return $this->setNoRender();
    }
    
    $this->view->title = $this->_getParam("title",'My Guides');
    if(!empty($this->view->title)){
        $this->view->title = $this->view->translate($this->view->title);
    }

    // Get subject and check auth
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user');
    if( !$subject->authorization()->isAllowed($viewer, 'edit') ) {
      return $this->setNoRender();
    }

    //build ajax
    $this->view->isajax = $is_ajax = $this->_getParam('isajax', '');

    if(!$this->view->isajax) {
        $this->view->params = $params = $this->_getAllParams();
        if ($this->_getParam('loaded_by_ajax', true)) {
          $this->view->loaded_by_ajax = true;
          ;
          if ($this->_getParam('is_ajax_load', false)) {
            $this->view->is_ajax_load = true;
            $this->view->loaded_by_ajax = false;
            if (!$this->_getParam('onloadAdd', false))
              $this->getElement()->removeDecorator('Title');
              $this->getElement()->removeDecorator('Container');
          } else {
            return;
          }
        }  
      
        // render content
        $this->view->showContent = true;  
  
    }   else {

        $this->view->showContent = true;
    }    
  }
}