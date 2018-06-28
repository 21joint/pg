<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxPreferencesController extends Engine_Content_Widget_Abstract {

    public function indexAction() {

        $feedType = $this->_getParam('type', null);
        $content_id = $this->view->identity;
    
        // Don't render this if not authorized
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        if( !Engine_Api::_()->core()->hasSubject() || $viewer->getIdentity() < 1 ) {
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
    
          $this->view->form = $form = new Sdparentalguide_Form_Signup_Interests;
          $form->setAttrib('class', 'preference-holder global_form ajax-form-' . $content_id);
          
          // render content
          $this->view->showContent = true;  
    
        }
        else {
          $this->view->showContent = true;
        }
    
    }
}