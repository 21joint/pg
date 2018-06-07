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
                //$this->_helper->requireAuth()->setAuthParams($item, $viewer, 'comment');
            }
        }
        $this->_helper->requireUser();
    }

    public function userPrivacyAction() {
        
        $viewer = Engine_Api::_()->user()->getViewer();
        $subject = Engine_Api::_()->core()->getSubject();

        if(!$subject->isSelf($viewer)) return;

        $type = $this->_getParam('type', null);
        $user_id = $this->_getParam('id', null);

        // setup privacy for public
        if($type == 'add') {
            $subject->search = 1;
        } else {
            $subject->search = 0;
        }
        $subject->save();

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('User have been saved.');


    }

    

}
