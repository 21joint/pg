<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminManageController.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_AdminManageController extends Core_Controller_Action_Admin
{
	//ACTION FOR MANAGING FEEDBACKS
  public function indexAction()
  {
		//GET NAVIGATION
   	$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
         ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_manage');

    //FORM GENERATION
    $this->view->formFilter = $formFilter = new Feedback_Form_Admin_Manage_Filter();

		//GET PAGE ID
    $this->view->page = $page = $this->_getParam('page',1);
		
		//GET USER TABLE
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');

		//GET CATEGORY TABLE
		$tableCategory = Engine_Api::_()->getItemTable('feedback_category');
    $tableCategoryName = $tableCategory->info('name');

		//GET SEVERITY TABLE
		$tableSeverity = Engine_Api::_()->getItemTable('feedback_severity');
    $tableSeverityName = $tableSeverity->info('name');

		//GET STATUS TABLE
    $tableStatusName = Engine_Api::_()->getItemTable('feedback_status')->info('name');

		//GET FEEDBACK TABLE
    $tableFeedback = Engine_Api::_()->getDbtable('feedbacks', 'feedback');
    $tableFeedbackName = $tableFeedback->info('name');

		//MAKE QUERY
    $select = $tableFeedback->select()
    								->setIntegrityCheck(false)
										->from($tableFeedbackName)
										->joinLeft($tableUserName, "$tableFeedbackName.owner_id = $tableUserName.user_id", array('username', 'displayname'))
    								->joinLeft($tableCategoryName, "$tableFeedbackName.category_id = $tableCategoryName.category_id", 'category_name')
    								->joinLeft($tableSeverityName, "$tableFeedbackName.severity_id = $tableSeverityName.severity_id", 'severity_name')
    								->joinLeft($tableStatusName, "$tableFeedbackName.stat_id = $tableStatusName.stat_id", 'stat_name');

    //PROCESS FROM 
    $values = array();
    if( $formFilter->isValid($this->_getAllParams()) ) {
    	$values = $formFilter->getValues();
    }

    //SHOW CATEGORY
    $this->view->categories = $tableCategory->getCategories();
    
    //SHOW SEVERITY
    $this->view->severities = $tableSeverity->getSeverities();
    
    //SHOW STATUS
    $this->view->status = Engine_Api::_()->getDbtable('status', 'feedback')->getStatus();
    
    foreach( $values as $key => $value ) {
    	if( null === $value ) {
        unset($values[$key]);
      }
    }

    $values = array_merge(array(
      'order' => 'feedback_id',
      'order_direction' => 'DESC',
    ), $values);
   
		$this->view->formValues = array_filter($values);
    $this->view->assign($values); 
    
    $select->order(( !empty($values['order']) ? $values['order'] : 'feedback_id' ) . ' ' . ( !empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
    
		//MAKE PAGINATOR
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(50);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
  }
 
  //ACTION FOR BLOCK USER
 	public function blockuserAction()
 	{
		//GET NAVIGATION
   	$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
         ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_blockuser'); 

    //FORM GENERATION
    $this->view->formFilter = $formFilter = new Feedback_Form_Admin_Manage_Filterblockuser();

		//GET PAGE ID
    $this->view->page = $page = $this->_getParam('page',1);

    //GET INFO FOR SHOW LISTING FROM user and blockusers TABLE
    $tmTable = Engine_Api::_()->getDbtable('blockusers', 'feedback');
    $tmName =  $tmTable->info('name');     
    $rName = Engine_Api::_()->getDbtable('users', 'user')->info('name');
    $select = $tmTable->select()
				    				  ->setIntegrityCheck(false)
				    				  ->from($rName)
				    				  ->joinLeft($tmName, "$tmName.blockuser_id = $rName.user_id");

    //PROCESS FORM
    $values = array();
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $values = $formFilter->getValues();
    }

    foreach( $values as $key => $value ) {
    	if( null === $value ) {
        	unset($values[$key]);
      }
    }

    $values = array_merge(array(
      'order' => 'user_id',
      'order_direction' => 'DESC',
    ), $values);
    
    $this->view->assign($values);

    $select->order(( !empty($values['order']) ? $values['order'] : 'user_id' ) . ' ' . ( !empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    if( !empty($values['username']) ) {
     	$select->where('username LIKE ?', '%' . $values['username'] . '%');
    }

    if( !empty($values['email']) ) {
     	$select->where('email LIKE ?', '%' . $values['email'] . '%');
    }

    if( !empty($values['level_id']) ) {
     	$select->where('level_id = ?', $values['level_id'] );
    }
 
    //MAKE PAGINATOR
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber( $page );
 	}
 
  //ACTION FOR EDIT STATUS
  public function changeStatAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Admin_Manage_Stat();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

		//GET FEEDBACK OBJECT
		$feedback = Engine_Api::_()->getItem('feedback', $this->_getParam('id'));
    $stat['stat_id'] = $feedback->stat_id;
    $stat['status_body'] = $feedback->status_body;
    
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//GET FORM VALUES
      $values = $form->getValues();

		  //BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
     
      try {
        
				//DATABASE ENTRY
        $feedback->stat_id = $values['stat_id'];
 				$feedback->status_body = $values['status_body'];
        $feedback->setFromArray($values);
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
	
    if( !($id = $this->_getParam('id')) ) {
     	die('No identifier specified');
    }

    $form->setField($stat);

		//RENDER SCRIPT
    $this->renderScript('admin-manage/form.tpl');
  }
  
  //ACTION FOR MULTI DELETE FEEDBACK
  public function multiDeleteAction()
  {
		if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key=>$value) {
        if ($key == 'delete_' . $value) {

          $feedback_id = (int)$value;

					//CALL A FUNCTION TO DELETE FEEDBACK BELONGINGS
          Engine_Api::_()->getItem('feedback', $feedback_id)->delete();
        }
      }
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
  }
}
