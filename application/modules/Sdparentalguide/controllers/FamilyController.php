<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_FamilyController extends Core_Controller_Action_Standard
{
    public function init(){
        if (!Engine_Api::_()->core()->hasSubject()) {
            // Can specifiy custom id
            $id = $this->_getParam('id', null);
            $subject = null;
            if (null === $id) {
                $subject = Engine_Api::_()->user()->getViewer();
                Engine_Api::_()->core()->setSubject($subject);
            } else {
                $subject = Engine_Api::_()->getItem('user', $id);
                Engine_Api::_()->core()->setSubject($subject);
            }
        }       
        
    }
    public function indexAction(){
        $id = $this->_getParam('id', null);
        if (!empty($id)) {
            $params = array('id' => $id);
        } else {
            $params = array();
        }
        // Set up navigation
        $this->view->navigation = $navigation = Engine_Api::_()
            ->getApi('menus', 'core')
            ->getNavigation('user_edit', array('params' => $params));
        
        $this->_helper->requireUser();
        $this->_helper->requireSubject('user');
        
        $this->view->form = $form = new Sdparentalguide_Form_Signup_Family();
        $form->removeElement("skip-link");
        $form->removeDisplayGroup("buttons");
        $form->removeElement("continue");
        $this->view->user = $user = Engine_Api::_()->core()->getSubject();
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    }
    public function addAction(){
        $this->view->form = $form = new Sdparentalguide_Form_Family_Add();
        // Check method/data validitiy
        if( !$this->getRequest()->isPost() ) {
          return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) ) {
          return;
        }
        
        // Process
        $table = Engine_Api::_()->getDbtable('familyMembers', 'sdparentalguide');
        $values = $form->getValues();
        $viewer = Engine_Api::_()->user()->getViewer();
        $values['owner_id'] = $viewer->getIdentity();
        $values['birthdate'] = date("Y-m-d",strtotime($values['birthdate']));
        $values['type'] = $values['relationship'];
        $values['dob'] = $values['birthdate'];
        $user_id = $this->getParam("user_id");
        if(!empty($user_id)){
            $values['owner_id'] = $user_id;
        }

        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            
          $member = $table->createRow();
          $member->setFromArray($values);
          $member->save();
          
          // Commit
          $db->commit();

          $this->view->smoothboxClose = true;
          $this->view->member = $member;
        } catch( Exception $e ) {
            throw $e;
        }
    }
    public function editAction(){
        $this->view->form = $form = new Sdparentalguide_Form_Family_Add();
        $table = Engine_Api::_()->getDbtable('familyMembers', 'sdparentalguide');
        
        $this->view->id = $memberId = $this->getParam('id');
        $member = $table->getById($memberId);
        if(empty($member)){
            return $this->_forward('requireauth', 'error', 'core');
        }
        
        $formParams = $member->toArray();
        $formParams['birthdate'] = $member->dob;
        $formParams['relationship'] = $member->type;
        $form->populate($formParams);
        
        // Check method/data validitiy
        if( !$this->getRequest()->isPost() ) {
          return;
        }

        if( !$form->isValid($this->getRequest()->getPost()) ) {
          return;
        }
        
        // Process
        $table = Engine_Api::_()->getDbtable('familyMembers', 'sdparentalguide');
        $values = $form->getValues();
        $viewer = Engine_Api::_()->user()->getViewer();
        $values['user_id'] = $viewer->getIdentity();
        $values['birthdate'] = date("Y-m-d",strtotime($values['birthdate']));
        $values['type'] = $values['relationship'];
        $values['dob'] = $values['birthdate'];

        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            
          $member->setFromArray($values);
          $member->save();
          
          // Commit
          $db->commit();

          $this->view->smoothboxClose = true;
          $this->view->member = $member;
        } catch( Exception $e ) {
            throw $e;
        }
    }
    
    public function deleteAction(){
        $table = Engine_Api::_()->getDbtable('familyMembers', 'sdparentalguide');
        
        $this->view->id = $memberId = $this->getParam('id');
        $member = $table->getById($memberId);
        if(empty($member)){
            $this->view->status = false;
            return;
        }
        $member->deleted = 1;
        $member->save();
        $this->view->status = true;
    }
}
