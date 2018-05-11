<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminController.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_AdminController extends Core_Controller_Action_Admin
{
	protected $_navigation;

  //ACTION FOR MAKE THE FEEDBACK FEATURED/UNFEATURED
  public function featuredAction() 
  {
		//GET FEEDBACK ID
  	$feedback_id = $this->_getParam('id'); 
  	
		//BEGIN TRANSACTION
   	$db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
			//GET FEEDBACK OBJECT
     	$feedback = Engine_Api::_()->getItem('feedback', $feedback_id);
     	if($feedback->featured == 0) {
   			$feedback->featured = 1;	
   		}
   		else {
   			$feedback->featured = 0;
   		}

			//SAVE IN DATABASE
   		$feedback->save();

			//COMMIT
 			$db->commit();
		}
  	catch( Exception $e ) {
    	$db->rollBack();
    	throw $e;
  	}  

		//REDIRECT
  	$this->_redirect("admin/feedback/manage/index/page/".$this->_getParam('page'));   
 	}
  
 	//ACTION FOR BLOCK IP COMMENT
  public function blockipCommentAction() 
  {
		//GET BLOCK IP ID
  	$block_ip_id = $this->_getParam('id');

		//BEGIN TRANSACTION
   	$db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
			//GET BLOCK IP OBJECT
     	$blockip = Engine_Api::_()->getItem('feedback_blockip', $block_ip_id);
     	if($blockip->blockip_comment == 0) {
   			$blockip->blockip_comment = 1;	
   		}
   		else {
   			$blockip->blockip_comment = 0;
   		}

			//SAVE IN DATABASE
   		$blockip->save();

			//COMMIT
 			$db->commit();
		}
  	catch( Exception $e ) {
    	$db->rollBack();
    	throw $e;
  	} 
 
		//REDIRECT
  	$this->_redirect("admin/feedback/settings/blockips/page/".$this->_getParam('page'));   
 	}
  
 	//ACTION FOR BLOCK IP TO FEEDBACK CREATE
  public function blockipFeedbackAction() 
  {
		//GET BLOCK IP ID  	
  	$block_ip_id = $this->_getParam('id');

		//BEGIN TRANSACTION
   	$db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
			//GET BLOCK IP OBJECT
     	$blockip = Engine_Api::_()->getItem('feedback_blockip', $block_ip_id);
     	if($blockip->blockip_feedback == 0) {
   			$blockip->blockip_feedback = 1;	
   		}
   		else {
   			$blockip->blockip_feedback = 0;
   		}
			
			//SAVE IN DATABSE
   		$blockip->save();

			//COMMIT
 			$db->commit();
		}
  	catch( Exception $e ) {
    	$db->rollBack();
    	throw $e;
  	}  

		//REDIRECT
  	$this->_redirect("admin/feedback/settings/blockips/page/".$this->_getParam('page'));   
 	}
  
  //ACTION FOR BLOCK USER COMMENT
  public function blockCommentAction() 
  {
		//GET BLOCK USER ID
  	$block_user_id = $this->_getParam('id'); 
  	
  	//CHECK FOR BLOCKER USER
		$userBlockTable = Engine_Api::_()->getItemTable('feedback_blockuser');
  	$user_total = $userBlockTable->countBlockUser($block_user_id); 
  	
		//BEGIN TRANSACTION
   	$db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
     	if($user_total == 0) {
      	$userBlock = $userBlockTable->createRow();
      	$userBlock->blockuser_id = $block_user_id;
      	$userBlock->block_comment = 1;
     	}
     	else {
        $userBlock = Engine_Api::_()->getItem('feedback_blockuser', $block_user_id);
       	if($userBlock->block_comment == 0) {
   	    	$userBlock->block_comment = 1;	
   	  	}
   	  	else {
   				$userBlock->block_comment = 0;
   	  	}
   		}
   		$userBlock->save();
 			$db->commit();
	 	}
   	catch( Exception $e ){
    	$db->rollBack();
     	throw $e;
   	}
  
		//REDIRECT
  	$this->_redirect("admin/feedback/manage/blockuser/page/".$this->_getParam('page'));   
 	}
  
  //ACTION FOR BLOCK USER TO CREATE FEEDBACK
  public function blockfeedbackAction() 
  {
		//GET BLOCK USER ID
  	$block_user_id = $this->_getParam('id'); 
  	
  	//CHECK FOR BLOCKER USER
		$userBlockTable = Engine_Api::_()->getItemTable('feedback_blockuser');
  	$user_total = $userBlockTable->countBlockUser($block_user_id);
  	
		//BEGIN TRANSACTION
   	$db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
     	if($user_total == 0) {
      	$userBlock = $userBlockTable->createRow();
      	$userBlock->blockuser_id = $block_user_id;
      	$userBlock->block_feedback = 1;
     	}
     	else {
        $userBlock = Engine_Api::_()->getItem('feedback_blockuser', $block_user_id);
         if($userBlock->block_feedback == 0) {
   	    	$userBlock->block_feedback = 1;	
   	  	}
   	  	else {
   		  	$userBlock->block_feedback = 0;
   	  	}
   		}
   		$userBlock->save();
 			$db->commit();
	 	}
   	catch( Exception $e ){
    	$db->rollBack();
     	throw $e;
   	} 
 
		//REDIRECT
  	$this->_redirect("admin/feedback/manage/blockuser/page/".$this->_getParam('page'));
 	}
  
  //ACTION FOR DELTE THE FEEDBACK
  public function deleteAction()
  { 
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET FEEDBACK ID
		$this->view->feedback_id = $feedback_id = $this->_getParam('id');
	
		if( $this->getRequest()->isPost()) {

			//CALL A FUNCTION TO DELETE FEEDBACK BELONGINGS
			Engine_Api::_()->getItem('feedback', $feedback_id)->delete();

			$this->_forward('success', 'utility', 'core', array(
			   'smoothboxClose' => 10,
			   'parentRefresh'=> 10,
			   'messages' => array('')
			));
   	}
		
		$this->renderScript('admin-manage/delete.tpl');
	}
	
 //ACTION FOR DELTE THE FEEDBACK
  public function visibilityAction()
  { 
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET FEEDBACK ID
		$this->view->feedback_id = $feedback_id = $this->_getParam('id');

		//GET VISIBILITY
		$this->view->visibility = $this->_getParam('visible');
	
		if( $this->getRequest()->isPost()) {

			//BEGIN TRANSACTION
			$db = Engine_Db_Table::getDefaultAdapter();
			$db->beginTransaction();

			try {
				//GET FEEDBACK OBJECT
				$feedback = Engine_Api::_()->getItem('feedback', $feedback_id);
			 	
				if($feedback->feedback_private == 'public') {
					$feedback->feedback_private = 'private';
				}
				else {
					$feedback->feedback_private = 'public';
				}
      
				//SAVE IN DATABASE
        $feedback->save();
       
				//COMMIT
		 		$db->commit();
			}
			catch( Exception $e ) {
				$db->rollBack();
			 	throw $e;
			}
			$this->_forward('success', 'utility', 'core', array(
			   'smoothboxClose' => 10,
			   'parentRefresh'=> 10,
			   'messages' => array('')
			));
   	}

		//RENDER SCRIPT
		$this->renderScript('admin-manage/visibility.tpl');
	}
	
	//ACTION FOR SHOW DETAIL OF FEEDBACK
  public function detailAction()
  {
		//LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET FEEDBACK ID AND OBJECT
		$this->view->feedback_id = $feedback_id = $this->_getParam('id');
		$this->view->feedback = $feedback = Engine_Api::_()->getItem('feedback', $feedback_id);

		//GET CATEGORY NAME
		$this->view->category_name = '---';
		if($feedback->category_id) {
			$category = Engine_Api::_()->getItem('feedback_category', $feedback->category_id);
			if($category) {
				$this->view->category_name = $category->category_name;
			}
		}

		//GET SEVERITY NAME
		$this->view->severity_name = '---';
		if($feedback->severity_id) {
			$severity = Engine_Api::_()->getItem('feedback_severity', $feedback->severity_id);
			if($severity) {
				$this->view->severity_name = $severity->severity_name;
			}
		}
		
		//GET STATUS NAME AND COLOR
		$this->view->stat_name = '---';
		$this->view->stat_color = '';
		if($feedback->stat_id) {
			$status = Engine_Api::_()->getItem('feedback_status', $feedback->stat_id);
			if($status) {
				$this->view->stat_name = $status->stat_name;
				$this->view->stat_color = $status->stat_color;
			}
		}
		
		//RENDER SCRIPT
		$this->renderScript('admin/detail.tpl');
	}
	
  //CREATE TABS
  public function getNavigation($active = false)
  { 
    if( is_null($this->_navigation) ) {
    	$navigation = $this->_navigation = new Zend_Navigation();

	    if( Engine_Api::_()->user()->getViewer()->getIdentity() ) {
	        $navigation->addPage(array(
	          'label' => 'View Feedbacks',
	          'route' => 'feedback_admin',
	          'module' => 'feedback',
	          'controller' => 'admin',
	          'action' => 'view'
	        ));
	
	        $navigation->addPage(array(
	          'label' => 'Global Settings',
	          'route' => 'feedback_admin',
	          'module' => 'feedback',
	          'controller' => 'admin',
	          'action' => 'settings',
	          'active' => $active
	        ));
			}
    }
    return $this->_navigation;
  }
}
