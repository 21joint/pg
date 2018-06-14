<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxPrivacyController extends Engine_Content_Widget_Abstract {

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
      
        $settings = Engine_Api::_()->getApi('settings', 'core');
        //$auth = Engine_Api::_()->authorization()->context;

        $this->view->form = $form = new User_Form_Settings_Privacy(array(
            'item' => $subject,
        ));
        $form->setAttrib('class', 'global_form ajax-form-' . $content_id);

        if( Engine_Api::_()->authorization()->isAllowed('user', $subject, 'block') ) {
            foreach ($subject->getBlockedUsers() as $blocked_user_id) {
              $this->view->blockedUsers[] = Engine_Api::_()->user()->getUser($blocked_user_id);
            }
        } else {
            $form->removeElement('blockList');
        }

        if( !Engine_Api::_()->getDbtable('permissions', 'authorization')->isAllowed($subject, $subject, 'search') ) {
            $form->removeElement('search');
        }

        // Hides options from the form if there are less then one option.
        if( count($form->privacy->options) <= 1 ) {
            $form->removeElement('privacy');
        }
        if( count($form->comment->options) <= 1 ) {
            $form->removeElement('comment');
        }

        // Populate form
        $form->populate($subject->toArray());

        // Set up activity options
        $defaultPublishTypes = array('post', 'signup', 'status');
        if( $form->getElement('publishTypes') ) {
         $actionTypes = Engine_Api::_()->getDbtable('actionTypes', 'activity')   ->getEnabledActionTypesAssoc();
        foreach( $defaultPublishTypes as $key ) {
            unset($actionTypes[$key]);
        }

        foreach( array_keys($actionTypes) as $key ) {
            if( substr($key, 0, 5) == 'post_' ) {
                $defaultPublishTypes[] = $key;
                unset($actionTypes[$key]);
            }
        }

        $form->publishTypes->setMultiOptions($actionTypes);
        $actionTypesEnabled = Engine_Api::_()->getDbtable('actionSettings', 'activity')->getEnabledActions($subject);
            $form->publishTypes->setValue($actionTypesEnabled);
        }

        // render content
        $this->view->showContent = true;  

    }
    else {
      $this->view->showContent = true;
    }

  }

}