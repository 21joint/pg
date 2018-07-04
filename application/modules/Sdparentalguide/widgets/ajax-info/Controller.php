<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxInfoController extends Engine_Content_Widget_Abstract {

    public function indexAction() {

        $feedType = $this->_getParam('type', null);
        $content_id = $this->view->identity;
    
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
    
        if( $is_ajax ) {
          $this->getElement()->removeDecorator('Title');
          $this->getElement()->removeDecorator('Container');
        }
    
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
    
          // Load fields view helpers
          $view = $this->view;
          $view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');

          // Values
          $this->view->fieldStructure = $fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($subject);
          
          if( count($fieldStructure) <= 1 ) { // @todo figure out right logic
            return $this->setNoRender();
          }

          
          $table = Engine_Api::_()->getDbtable('familyMembers', 'sdparentalguide');
          $select = $table->select()
            ->where('owner_id = ?', $subject->user_id);
          ;
          

          $this->view->paginator = $paginator = Zend_Paginator::factory($select);

          $this->view->paginator->setItemCountPerPage(5);
         
    
          // render content
          $this->view->showContent = true;  
    
        }
        else {
          $this->view->showContent = true;
        }
    
    }

}