<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxNotificationsController extends Engine_Content_Widget_Abstract {

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
      
        // Build the different notification types
        $modules = Engine_Api::_()->getDbtable('modules', 'core')->getModulesAssoc();
        $notificationTypes = Engine_Api::_()->getDbtable('notificationTypes', 'activity')->getNotificationTypes();
        $notificationSettings = Engine_Api::_()->getDbtable('notificationSettings', 'activity')->getEnabledNotifications($subject);

        $notificationTypesAssoc = array();
        $notificationSettingsAssoc = array();
        foreach( $notificationTypes as $type ) {
            if( isset($modules[$type->module]) ) {
                $category = 'ACTIVITY_CATEGORY_TYPE_' . strtoupper($type->module);
                $translateCategory = Zend_Registry::get('Zend_Translate')->_($category);
                if( $translateCategory === $category ) {
                    $elementName = preg_replace('/[^a-zA-Z0-9]+/', '_', $type->module);
                    $category = $modules[$type->module]->title;
                } else {
                    $elementName = preg_replace('/[^a-zA-Z0-9]+/', '_', strtolower($translateCategory));
                }
            } else {
                $elementName = 'misc';
                $category = 'Misc';
            }

            $notificationTypesAssoc[$elementName]['category'] = $category;
            $notificationTypesAssoc[$elementName]['types'][$type->type] = 'ACTIVITY_TYPE_' . strtoupper($type->type);

            if( in_array($type->type, $notificationSettings) ) {
                $notificationSettingsAssoc[$elementName][] = $type->type;
            }
        }

        ksort($notificationTypesAssoc);

        $notificationTypesAssoc = array_filter(array_merge(array(
            'general' => array(),
            'misc' => array(),
        ), $notificationTypesAssoc));

        // Make form
        $this->view->form = $form = new Engine_Form(array(
            'title' => 'Notification Settings',
            'description' => 'Which of the these do you want to receive email alerts about?',
        ));
        $form->setAttrib('class', 'global_form ajax-form-' . $content_id);

        foreach( $notificationTypesAssoc as $elementName => $info ) {
            $form->addElement('MultiCheckbox', $elementName, array(
                'label' => $info['category'],
                'multiOptions' => $info['types'],
                'value' => (array) @$notificationSettingsAssoc[$elementName],
            ));
        }

        $form->addElement('Button', 'execute', array(
            'label' => 'Save Changes',
            'class' => 'button primary border-radius-25',
            'type' => 'submit',
        ));


        // render content
        $this->view->showContent = true;  

    }
    else {
      $this->view->showContent = true;
    }

  }

}