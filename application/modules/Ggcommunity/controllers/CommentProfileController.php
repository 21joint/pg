<?php
class Ggcommunity_CommentProfileController extends Core_Controller_Action_Standard
{
 
    public function init() {

        if( !$this->_helper->requireUser()->isValid() ) return;

        $subject = null; 
        if( !Engine_Api::_()->core()->hasSubject() && ($id = $this->_getParam('comment_id')) ) {
     
            $subject = Engine_Api::_()->getItem('ggcommunity_comment', $id);
        
            if( $subject && $subject->getIdentity() ) {
                Engine_Api::_()->core()->setSubject($subject);
            }
        }
        $this->_helper->requireSubject();
    }
  
    //Edit Comment
    public function editAction(){	
        // get viewer and subject
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer(); 
        $subject = Engine_Api::_()->core()->getSubject();

        if(!$subject->getOwner()->isSelf($viewer) && !$viewer->isAdmin() ) return;

        //Process form
        $form = new Ggcommunity_Form_Comment_Create();

        // check if its request
        if(!$this->getRequest()->getPost()) return;
        
		// check if form is valid, get request from the POST Method
		if( !$form->isValid( $this->getRequest()->getPost() ) ) return;
		
		// get values from form
        $values = $form->getValues();
        $body = $values['body'];

        $subject->body = $body;
        $subject->save();

        $this->view->comment = $subject;
    }

    // Delete Comment
    public function deleteAction() {
        
        // smoothbox
        $this->_helper->layout->setLayout('default-simple');

        // get viewer and subject
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer(); 
        $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();
        $this->view->type = $type = $subject->parent_type;
        $this->view->object = $object = $subject->getParent();

        //if this post is not from this vieweer or viewer is not self admin don't allow deleting
        if(!$subject->getOwner()->isSelf($viewer) && !$viewer->isAdmin() ) return;

        $this->view->form = $form = new Ggcommunity_Form_Delete();

        if( !$this->getRequest()->isPost() ) {
            return;
        }
        // check if form is valid, get request from the POST Method
        if( !$form->isValid( $this->getRequest()->getPost() ) ) {
            return;	
        }

        $db = Engine_Api::_()->getDbtable('comments', 'ggcommunity')->getAdapter();
        $db->beginTransaction();

        try {

            $subject->delete();
            $db->commit();
            
            // make sure to decrease counter for comments
            $this->view->comment_count = $object->comment_count = $object->comment_count-1;
            $object->save();
    
            // tell smoothbox to close
            $this->view->status  = true;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('This item has been successfully deleted.');
            $this->view->smoothboxClose = true;
            return $this->render('deleted');

        } catch( Exception $e ) {
            $db->rollBack();
            throw $e;
            $this->view->status = false;
        }

        
        
    }
    
    
  
}