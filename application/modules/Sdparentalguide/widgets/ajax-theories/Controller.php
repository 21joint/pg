<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxTheoriesController extends Engine_Content_Widget_Abstract {

  public function indexAction() { 

    $feedType = $this->_getParam('type', null);

    // Don't render this if not authorized
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    if( !Engine_Api::_()->core()->hasSubject()  || $viewer->getIdentity() < 1 ) {
      return $this->setNoRender();
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
  
        $table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');

        $select = $table->select()
          ->where('user_id = ?',$subject->getIdentity() )
          ->group('parent_id')
          ->order('parent_id DESC')
        ;

    
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);

        // Set item count per page and current page number
        $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 2));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
      

  
        // render content
        $this->view->showContent = true;  
  
    }   else {

        $this->view->showContent = true;
    }
    

  }

}