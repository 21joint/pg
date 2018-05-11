<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_AdminSettingsController extends Core_Controller_Action_Admin
{
	//ACTION FOR GLOBAL SETTINGS
  public function indexAction()
  {
		if( !empty($_POST['feedback_license_key']) ) { $_POST['feedback_license_key'] = trim($_POST['feedback_license_key']); }
		$feedback_before_active_content = array('feedback_show_browse', 'feedback_default_visibility', 'feedback_public', 'feedback_post', 'feedback_option_post', 'feedback_severity', 'feedback_tag', 'feedback_allow_image', 'feedback_email_notify', 'feedback_report', 'feedback_button_position', 'feedback_button_color1', 'feedback_button_color2', 'feedback_page', 'feedback_title_truncation', 'feedback_recent_widgets', 'submit');
    include_once(APPLICATION_PATH ."/application/modules/Feedback/controllers/license/license1.php");
  }
  
	//ACTION FOR SHOW STATISTICS
  public function statisticAction()
  {
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      	 ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_statistic');
    
		//GET STATISTICS
		$this->view->statistics = Engine_Api::_()->getDbTable('feedbacks', 'feedback')->getStatistics();
  }   	 
  
  //ACTION FOR RETURN CATEGORY
  public function categoriesAction()
  {
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus','core')
      	 ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_categories');

    include_once(APPLICATION_PATH ."/application/modules/Feedback/controllers/license/license2.php");

    //CHECK POST
    if ($this->getRequest()->isPost()) {
		
			//BEGIN TRANSCATION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      $values = $_POST;
      try {
        foreach ($values['order'] as $key => $value) {
          $tableCategory->update(array('order' => $key + 1), array('category_id = ?' => (int) $value));
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

			//REDIRECT TO MANAGE PAGE
      return $this->_redirect("admin/feedback/settings/categories");
    }
  }

  //ACTION FOR RETURN BLOCK IPS
  public function blockipsAction()
  {
		//GET NAVIGATION
   	$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
         ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_blockips'); 

		//FORM GENERATION
    $this->view->formFilter = $formFilter = new Feedback_Form_Admin_Manage_Filter();

		//GET PAGE ID
    $this->view->page = $page = $this->_getParam('page',1);

    //MAKE QUERY
    $select = Engine_Api::_()->getDbtable('blockips', 'feedback')->select();
				    				  
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
      'order' => 'blockip_id',
      'order_direction' => 'DESC',
    ), $values);
    
    $this->view->assign($values);

    $select = $select->order(( !empty($values['order']) ? $values['order'] : 'blockip_id' ) . ' ' . ( !empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

		//MAKE PAGINATOR
    $this->view->blockips = $paginator = Zend_Paginator::factory($select);
    $paginator->setCurrentPageNumber($page);
  }

  //ACTION FOR RETURN SEVERITY
  public function severitiesAction()
  {
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      	 ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_severities');

    include_once(APPLICATION_PATH ."/application/modules/Feedback/controllers/license/license2.php");

    //CHECK POST
    if ($this->getRequest()->isPost()) {
		
			//BEGIN TRANSCATION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      $values = $_POST;
      try {
        foreach ($values['order'] as $key => $value) {
          $tableSeverity->update(array('order' => $key + 1), array('severity_id = ?' => (int) $value));
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

			//REDIRECT TO MANAGE PAGE
      return $this->_redirect("admin/feedback/settings/severities");

    }
  }
  
  //ACTION FOR RETURN STATUS
  public function statusAction()
  {
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      	 ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_status');

		include_once(APPLICATION_PATH ."/application/modules/Feedback/controllers/license/license2.php");
  }
  
  //ACTION FOR ADD NEW CATEGORY
  public function addCategoryAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Admin_Category();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//GET FORM VALUES
    	$values = $form->getValues();

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

        //ADD CATEGORY TO THE DATABASE
        $row = Engine_Api::_()->getDbtable('categories', 'feedback')->createRow();
        $row->user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $row->category_name = $values['label'];
        $row->save();

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
    $this->renderScript('admin-settings/form.tpl');
  }

  //ACTION FOR ADD NEW BLOCK IP
  public function addBlockipAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Admin_Blockip();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//GET FORM VALUES
    	$values = $form->getValues();

			//ADMIN CAN'T ADD THE SAME IP MORE THAN ONE TIME
			$tableBlockIp = Engine_Api::_()->getDbtable('blockips', 'feedback');

			//CHECK THAT THIS IP IS ALREADY ADDED OR NOT
			$blockIpData = $tableBlockIp->blockipAdded($values['label']);
			if(!empty($blockIpData)) {
				$error = $this->view->translate('You have already added this ip address !');
				$error = Zend_Registry::get('Zend_Translate')->_($error);

				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
			}

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

	    try{
       
        //INSERT THE BLOCK IP IN TO THE DATABASE
        $row = $tableBlockIp->createRow();
        $row->blockip_comment = $values['blockip_comment'];
        $row->blockip_feedback = $values['blockip_feedback'];
        $row->blockip_address = $values['label'];
        $row->save();
			
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
    $this->renderScript('admin-settings/add-blockip.tpl');
  }

  //ACTION FOR ADD NEW SEVERITY
  public function addSeverityAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Admin_Severity();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//GET FORM VALUES
      $values = $form->getValues();

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try{

        //INSERT THE SEVERITY IN TO THE DATABASE
        $row = Engine_Api::_()->getDbtable('severities', 'feedback')->createRow();
        $row->user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $row->severity_name = $values['label'];
        $row->save();

				//COMMIT
        $db->commit();
      }

      catch( Exception $e )
      {
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
    $this->renderScript('admin-settings/form.tpl');
  }
  
  //ACTION FOR ADD NEW STATUS
  public function addStatAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//FORM GENERATION
  	$this->view->form = $form = new Feedback_Form_Admin_Stat();
  	$form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

        //INSERT THE STATUS IN TO THE DATABASE
        $row = Engine_Api::_()->getDbtable('status', 'feedback')->createRow();
        $row->user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $row->stat_name = $this->_getParam('label');
        $stat_color = $this->_getParam('myInput'); 
        if(!empty($stat_color)) {
        	$row->stat_color = $stat_color; 
        }  
        $row->save();

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
  }
  
  //ACTION FOR DELETE CATEGORY 
  public function deleteCategoryAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//GET CATEGORY ID AND CHECK VALIDATION
    $this->view->id = $id = $this->_getParam('id');
    if(empty($id)) {
    	die('No identifier specified');
    }

    if( $this->getRequest()->isPost()) {

			//BEGIN TRANSACTION
    	$db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

				//EDIT CATEGORY ZERO IN FEEDBACK TABLE
				Engine_Api::_()->getDbtable('feedbacks', 'feedback')->update(array('category_id' => 0), array('category_id = ?' => $id));

        //DELETE CATEGORY OBJECT
        Engine_Api::_()->getItem('feedback_category', $id)->delete();

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
    $this->renderScript('admin-settings/delete.tpl');
  }

  //ACTION FOR DELETE BLOCK IP 
  public function deleteBlockipAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//GET BLOCK IP ID AND CHECK VALIDATION
    $this->view->id = $id = $this->_getParam('id');
    if(empty($id)) {
    	die('No identifier specified');
    }

		if( $this->getRequest()->isPost()) {

			//BEGIN TRANSACTION
    	$db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

        //DELETE BLOCK IP OBJECT
        Engine_Api::_()->getItem('feedback_blockip', $id)->delete();

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
    $this->renderScript('admin-settings/delete.tpl');
  }

  //ACTION FOR DELETE SEVERITY 
  public function deleteSeverityAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//GET SAVERITY ID AND CHECK VALIDATION
    $this->view->id = $id = $this->_getParam('id');
    if(empty($id)) {
    	die('No identifier specified');
    }

    if( $this->getRequest()->isPost()) {

			//BEGIN TRANSACTION
    	$db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

				//EDIT SAVERITY ZERO IN FEEDBACK TABLE
				Engine_Api::_()->getDbtable('feedbacks', 'feedback')->update(array('severity_id' => 0), array('severity_id = ?' => $id));

        //DELETE SAVERITY OBJECT
        Engine_Api::_()->getItem('feedback_severity', $id)->delete();

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
    $this->renderScript('admin-settings/delete.tpl');
  }
  
	//ACTION FOR DELETE STATUS
  public function deleteStatAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//GET STATUS ID AND CHECK VALIDATION
    $this->view->id = $id = $this->_getParam('id');
    if(empty($id)) {
    	die('No identifier specified');
    }

    if( $this->getRequest()->isPost()) {

			//BEGIN TRANSACTION
    	$db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

				//EDIT STATUS ZERO IN FEEDBACK TABLE
				Engine_Api::_()->getDbtable('feedbacks', 'feedback')->update(array('stat_id' => 0), array('stat_id = ?' => $id));

        //DELETE STATUS OBJECT
        Engine_Api::_()->getItem('feedback_status', $id)->delete();

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
    $this->renderScript('admin-settings/delete.tpl');
  }
  
  //ACTION FOR EDIT CATEGORY
  public function editCategoryAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Admin_Category();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

		//GET CATEGORY ID AND CHECK VALIDATION
		$category_id = $this->_getParam('id'); 
    if(empty($category_id)) {
    	die('No identifier specified');
    }

		//GET CATEGORY OBJECT
    $category = Engine_Api::_()->getItem('feedback_category', $category_id);
    $form->setField($category);


    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//GET FROM VALUES
      $values = $form->getValues();

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
				
				//SAVE IN DATABASE
        $category->category_name = $values['label'];
        $category->save();

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
    $this->renderScript('admin-settings/form.tpl');
  }
  
  //ACTION FOR EDIT BLOCK IP
  public function editBlockipAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Admin_Blockip();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

 		//GET BLOCK IP ID AND CHECK VALIDATION
		$blockip_id = $this->_getParam('id');
    if(empty($blockip_id)) {
    	die('No identifier specified');
    }
 
 		//GET BLOCK IP ID OBJECT
    $blockip = Engine_Api::_()->getItem('feedback_blockip', $blockip_id);
    $form->setField($blockip);

    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//GET FORM VALUES
      $values = $form->getValues();

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
				
				//SAVE VALUES IN DATABASE
        $blockip->blockip_comment = $values['blockip_comment'];
        $blockip->blockip_feedback = $values['blockip_feedback'];
        $blockip->save();

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
    $this->renderScript('admin-settings/edit-blockip.tpl');
  }
  
  //ACTION FOR EDIT SEVERITY
  public function editSeverityAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');
	
		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Admin_Severity();
		$form->setAction($this->getFrontController()->getRouter()->assemble(array()));

		//GET SEVERITY ID AND CHECK VALIDATION
		$severity_id = $this->_getParam('id');
    if(empty($severity_id)) {
      die('No identifier specified');
    }

		//GET SEVERITY OBJECT
    $severity = Engine_Api::_()->getItem('feedback_severity', $severity_id);
		$form->setField($severity);
    
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//GET FORM VALUES
      $values = $form->getValues();

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

				//SAVE IN DATABASE
        $severity->severity_name = $values['label'];
        $severity->save();

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
    $this->renderScript('admin-settings/form.tpl');
  }
  
  //ACTION FOR EDIT STATUS
  public function editStatAction()
  {
		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//GET STATUS ID AND CHECK VALIDATION
    $stat_id = $this->_getParam('id');
    if(empty($stat_id)) {
			die('No identifier specified');
    }

		//GET STATUS OBJECT
    $this->view->status = $status = Engine_Api::_()->getItem('feedback_status', $stat_id);
 
		//FORM GENERATION
  	$this->view->form = $form = new Feedback_Form_Admin_Stat();
  	$form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
       
   		  //INSERT THE STATUS IN TO THE DATABASE
        $status->stat_name = $this->_getParam('label');
        $stat_color = $this->_getParam('myInput'); 
        if(!empty($stat_color)) {
        	$status->stat_color = $stat_color; 
        }  
        $status->save();

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
  }

  //ACTINO FOR SEARCH
  public function formSearchAction() {

    // GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_form_search');

    $table = Engine_Api::_()->getDbTable('searchformsetting', 'seaocore');

    //CHECK POST
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      $values = $_POST;
      try {
        foreach ($values['order'] as $key => $value) {
          $table->update(array('order' => $key + 1), array('module = ?' => 'feedback', 'searchformsetting_id =?' => (int) $value));
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
    $this->view->searchForm = $table->fetchAll($table->select()->where('module = ?', 'feedback')->order('order'));
  }
  
  //ACTION FOR DISPLAY/HIDE FIELDS OF SEARCH FORM
  public function diplayFormAction() {
  	
    $field_id = $this->_getParam('id');
    $display = $this->_getParam('display');
    if (!empty($field_id)) {
      Engine_Api::_()->getDbTable('searchformsetting', 'seaocore')->update(array('display' => $display), array('module = ?' => 'feedback', 'searchformsetting_id =?' => (int) $field_id));
    }
    $this->_redirect('admin/feedback/settings/form-search');
  }
  
	//ACTION FOR FAQ
  public function faqAction()
  {
  	$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      	 ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_faq');
  }

	//THIS IS THE 'README ACTION' WHICH WILL CALL FIRST TIEM ONLY WHEN PLUGIN WILL INSTALL.
  public function readmeAction()
	{

  }
}
