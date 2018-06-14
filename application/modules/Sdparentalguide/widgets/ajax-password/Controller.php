<?php
/**
 * EXTFOX
 *
 */
class Sdparentalguide_Widget_AjaxPasswordController extends Engine_Content_Widget_Abstract {

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

      $this->view->form = $form = new User_Form_Settings_Password();
      $form->populate($subject->toArray());

      

      if( !$this->getRequest()->isPost() ){
        return;
      }
     

      // if( !$form->isValid($this->getRequest()->getPost()) ) {
      //   return;
      // }
    
      // // Check conf
      // if( $form->getValue('passwordConfirm') !== $form->getValue('password') ) {
      //   $form->getElement('passwordConfirm')->addError(Zend_Registry::get('Zend_Translate')->_('Passwords did not match'));
      //   return;
      // }
      
      
      // Process form
      $userTable = Engine_Api::_()->getItemTable('user');
      $db = $userTable->getAdapter();
  
      // Check old password
      $salt = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.secret', 'staticSalt');
      $select = $userTable->select()
        ->from($userTable, new Zend_Db_Expr('TRUE'))
        ->where('user_id = ?', $subject->getIdentity())
        ->where('password = ?', new Zend_Db_Expr(sprintf('MD5(CONCAT(%s, %s, salt))', $db->quote($salt), $db->quote($form->getValue('oldPassword')))))
        ->limit(1)
        ;
      $valid = $select
        ->query()
        ->fetchColumn()
        ;
  
      // if( !$valid ) {
      //   $form->getElement('oldPassword')->addError(Zend_Registry::get('Zend_Translate')->_('Old password did not match'));
      //   return;
      // }
      


      
      
      // Save
      $db->beginTransaction();
  
      try {
  
        $subject->setFromArray($form->getValues());
        $subject->save();
        
        $db->commit();
      } catch( Exception $e ) {
        $db->rollBack();
        throw $e;
      }
  
      $form->addNotice(Zend_Registry::get('Zend_Translate')->_('Settings were successfully saved.'));
    

      // render content
      $this->view->showContent = true;  

    }
    else {
      $this->view->showContent = true;
    }

  }

}