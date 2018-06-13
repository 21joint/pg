<?php

class Sdparentalguide_AjaxController extends Core_Controller_Action_Standard
{

    public function init() {

        $type = $this->_getParam('type');
        $identity = $this->_getParam('id');
        $action = $this->_getParam('action');

        if( $type && $identity && $action) {
            $item = Engine_Api::_()->getItem($type, $identity);
            if( $item instanceof Core_Model_Item_Abstract ) {
                if( !Engine_Api::_()->core()->hasSubject() ) {
                    Engine_Api::_()->core()->setSubject($item);
                }
            }
        }
        $this->_helper->requireUser();
    }

    public function userPrivacyAction() {
        
        $viewer = Engine_Api::_()->user()->getViewer();
        $subject = Engine_Api::_()->core()->getSubject();

        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");;
            return;
        }

        if(!$subject->isSelf($viewer)) return;

        $type = $this->_getParam('type', null);
        $user_id = $this->_getParam('id', null);

        // setup privacy for public
        $searchPrivacy = $subject->search;
        ($searchPrivacy == 1) ? $subject->search = 0 : $subject->search = 1;

        $subject->save();

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('User have been saved.');

    }

    public function passwordAction() {

        $this->_helper->ViewRenderer->setNoRender(true);

        $viewer = Engine_Api::_()->user()->getViewer();

        if( !$this->getRequest()->isPost() ){
            return;
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        
        // setup form
        $form = new User_Form_Settings_Password();

        // setup values
        $values = $request->getParam('values', null);
        foreach($values as $key => $value) {
            $form->getElement($key)->setValue($value);
        }

        // if form is valid
        if( !$form->isValid( $values ) ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Something went wrong.');
            return;
        }

        // Check conf
        if( $form->getValue('passwordConfirm') !== $form->getValue('password') ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Passwords did not match.');
            return;
        }
        
        // Process form
        $userTable = Engine_Api::_()->getItemTable('user');
        $db = $userTable->getAdapter();

        // Check old password
        $salt = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.secret', 'staticSalt');
        $select = $userTable->select()
            ->from($userTable, new Zend_Db_Expr('TRUE'))
            ->where('user_id = ?', $viewer->getIdentity())
            ->where('password = ?', new Zend_Db_Expr(sprintf('MD5(CONCAT(%s, %s, salt))', $db->quote($salt), $db->quote($form->getValue('oldPassword')))))
            ->limit(1)
        ;
        $valid = $select
            ->query()
            ->fetchColumn()
        ;

        if( !$valid ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Old password did not match.');
            return;
        }

        // Save
        $db->beginTransaction();

        try {

            $viewer->setFromArray($form->getValues());
            $viewer->save();

            $db->commit();
        } catch( Exception $e ) {
            $db->rollBack();
            throw $e;
        }

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Settings were successfully saved.');

    }

    public function deleteAction() {

        $this->_helper->ViewRenderer->setNoRender(true);

        $viewer = Engine_Api::_()->user()->getViewer();

        if( !$this->getRequest()->isPost() ){
            return;
        }

        if( !$this->_helper->requireAuth()->setAuthParams($viewer, null, 'delete')->isValid() ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('You are not able to delete your account.');
            return;
        };

        if( $viewer->level_id === 1 ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Super Admins can\'t be deleted.');
            return;
        }

        // Process
        $db = Engine_Api::_()->getDbtable('users', 'user')->getAdapter();
        $db->beginTransaction();

        try {
            $viewer->delete();
        
            $db->commit();
        } catch( Exception $e ) {
            $db->rollBack();
            throw $e;
        }

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your account have been deleted.');

        // Unset viewer, remove auth, clear session
        Engine_Api::_()->user()->setViewer(null);
        Zend_Auth::getInstance()->getStorage()->clear();
        Zend_Session::destroy();


        

    }

    

}
