<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxProfileController extends Engine_Content_Widget_Abstract {

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

      // General form w/o profile type
      $aliasedFields = $subject->fields()->getFieldsObjectsByAlias();
      $this->view->topLevelId = $topLevelId = 0;
      $this->view->topLevelValue = $topLevelValue = null;
      if (isset($aliasedFields['profile_type'])) {
          $aliasedFieldValue = $aliasedFields['profile_type']->getValue($subject);
          $topLevelId = $aliasedFields['profile_type']->field_id;
          $topLevelValue = (is_object($aliasedFieldValue) ? $aliasedFieldValue->value : null);
          if (!$topLevelId || !$topLevelValue) {
              $topLevelId = null;
              $topLevelValue = null;
          }
          $this->view->topLevelId = $topLevelId;
          $this->view->topLevelValue = $topLevelValue;
      }

      // Get form
      $form = $this->view->form = new Fields_Form_Standard(array(
        'item' => Engine_Api::_()->core()->getSubject(),
        'topLevelId' => $topLevelId,
        'topLevelValue' => $topLevelValue,
        'hasPrivacy' => true,
        'privacyValues' => $this->getRequest()->getParam('privacy'),
      ));
      $form
        ->setAttrib('class', 'w-100 global_form ajax-form-' . $content_id)
        ->setAttrib('id', 'extfox-settings')
      ;


      $form->populate($subject->toArray());

      // render content
      $this->view->showContent = true;  

    }
    else {
      $this->view->showContent = true;
    }

  }

}