<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSiteFaqController.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_AdminSiteFaqController extends Core_Controller_Action_Admin
{
	//ACTION FOR GLOBAL SETTINGS
  public function manageAction()
  { 
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_sitefaq');

    //FORM GENERATION
    $this->view->formFilter = $formFilter = new Sitefaq_Form_Admin_Question_Filter();
    $page = $this->_getParam('page', 1);

		//GET CATEGORY TABLE
		$tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');
		$tableCategoryName = $tableCategory->info('name');

		//GET USER TABLE NAME
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');

		//GET HELP TABLE
		$this->view->helpTable = Engine_Api::_()->getDbtable('helps', 'sitefaq');

		//GET FAQ TABLE
    $tableSitefaq = Engine_Api::_()->getDbtable('faqs', 'sitefaq');
    $tableSitefaqName = $tableSitefaq->info('name');

		//MAKE QUERY
    $select = $tableSitefaq->select()
            ->setIntegrityCheck(false)
            ->from($tableSitefaqName)
            ->joinLeft($tableUserName, "$tableSitefaqName.owner_id = $tableUserName.user_id", 'username');

    $values = array();

    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }
    foreach ($values as $key => $value) {

      if (null == $value) {
        unset($values[$key]);
      }
    }

    //SEARCHING
    $this->view->owner = '';
    $this->view->title = '';
    $this->view->approved = '';
    $this->view->featured = '';
    $this->view->status = '';
    $this->view->category_id = '';
    $this->view->subcategory_id = '';
    $this->view->subsubcategory_id = '';

    $values = array_merge(array(
        'order' => 'faq_id',
        'order_direction' => 'DESC',
            ), $values);

		if(!empty($_POST['owner'])) { $user_name = $_POST['owner']; } elseif(!empty($_GET['owner'])) { $user_name = $_GET['owner']; }  else { $user_name = '';}

		if(!empty($_POST['title'])) { $page_name = $_POST['title']; } elseif(!empty($_GET['title'])) { $page_name = $_GET['title']; } elseif($this->_getParam('title', '')) { $page_name = $this->_getParam('title', '');} else { $page_name = '';}

		//SEARCHING
    $this->view->owner = $values['owner'] = $user_name;
		$this->view->title = $values['title'] = $page_name; 

		if (!empty($page_name)) {
			$select->where($tableSitefaqName . '.title  LIKE ?', '%' . $page_name . '%');
		}    

		if (!empty($user_name)) {
			$select->where($tableUserName . '.displayname  LIKE ?', '%' . $user_name . '%');
		}

    if (isset($_POST['search'])) {

      if (!empty($_POST['featured'])) {
        $this->view->featured = $_POST['featured'];
        $_POST['featured']--;
        $select->where($tableSitefaqName . '.featured = ? ', $_POST['featured']);
      }

      if (!empty($_POST['approved'])) {
        $this->view->approved = $_POST['approved'];
        $_POST['approved']--;
        $select->where($tableSitefaqName . '.approved = ? ', $_POST['approved']);
      }

      if (!empty($_POST['draft'])) {
        $this->view->draft = $_POST['draft'];
        $_POST['draft']--;
        $select->where($tableSitefaqName . '.draft = ? ', $_POST['draft']);
      }

      if (!empty($_POST['status'])) {
        $this->view->status = $_POST['status'];
        $_POST['status']--;
        $select->where($tableSitefaqName . '.closed = ? ', $_POST['status']);
      }
      
      if (!empty($_POST['category_id']) && empty($_POST['subcategory_id']) && empty($_POST['subsubcategory_id'] )) {
        $this->view->category_id = $_POST['category_id'];
        $select->where($tableSitefaqName . '.category_id LIKE ? ', '%"'.$_POST['category_id'].'"%');
      } 
			elseif (!empty($_POST['category_id']) && !empty($_POST['subcategory_id']) && empty($_POST['subsubcategory_id'] )) {
        $this->view->category_id = $_POST['category_id'];
        $subcategory_id = $this->view->subcategory_id = $_POST['subcategory_id'];

        $selectcategory = $tableCategory->select()
																->from($tableCategoryName, 'category_name')
																->where("(category_id = $subcategory_id)");
        $row = $tableCategory->fetchRow($selectcategory);

        if (!empty($row->category_name)) {
          $this->view->subcategory_name = $row->category_name;
        }

        $select->where($tableSitefaqName . '.category_id LIKE ? ', '%"'.$_POST['category_id'].'"%')
                ->where($tableSitefaqName . '.subcategory_id LIKE ? ', '%"'.$_POST['subcategory_id'].'"%');
      }
      elseif (!empty($_POST['category_id']) && !empty($_POST['subcategory_id']) && !empty($_POST['subsubcategory_id'])) {
        
        $this->view->category_id = $_POST['category_id'];
        $subcategory_id = $this->view->subcategory_id = $_POST['subcategory_id'];
        $subsubcategory_id = $this->view->subsubcategory_id = $_POST['subsubcategory_id'];

        $row = $tableCategory->getCategory($subcategory_id);
        if (!empty($row->category_name)) {
          $this->view->subcategory_name = $row->category_name;
        }
        $row = $tableCategory->getCategory($subsubcategory_id);
        if (!empty($row->category_name)) {
          $this->view->subsubcategory_name = $row->category_name;
        }
        $select->where($tableSitefaqName . '.category_id LIKE ? ', '%"'.$_POST['category_id'].'"%')
                ->where($tableSitefaqName . '.subcategory_id LIKE ? ', '%"'.$_POST['subcategory_id'].'"%')
                ->where($tableSitefaqName . '.subsubcategory_id LIKE ? ', '%"'.$_POST['subsubcategory_id'].'"%');;
      }
    }

		//SEND FORM VALUES TO TPL
    $this->view->formValues = array_filter($values);
    $this->view->assign($values);

		//SEND ORDER DIRECTION TO TPL
		$this->view->order_direction = !empty($values['order_direction']) ? $values['order_direction'] : 'DESC';

    $select->order((!empty($values['order']) ? $values['order'] : 'faq_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    //MAKE PAGINATOR
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator->setItemCountPerPage(50);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
	}

	//ACTION FOR MAKE THE FAQ APPROVED/DIS-APPROVED
  public function approvedAction() {

		//GET FAQ ID
  	$faq_id = $this->_getParam('faq_id');

		//BEGIN TRANSCATION
   	$db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {

			//GET FAQ OBJECT
     	$sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id);
     	if($sitefaq->approved == 0) {
   		  $sitefaq->approved = 1;	
   		}
   		else {
   			$sitefaq->approved = 0;
   		}

			//SAVE CHANGES AND COMMIT
   		$sitefaq->save();
 			$db->commit();
	 	}
   	catch( Exception $e ){
     $db->rollBack();
     throw $e;
   	}

		//REDIRECT
  	$this->_redirect('admin/sitefaq/site-faq/manage');   
 	}

	//ACTION FOR MAKE THE FAQ FEATURED/UNFEATURED
  public function featuredAction() {

		//GET FAQ ID
  	$faq_id = $this->_getParam('faq_id');

		//BEGIN TRANSCATION
   	$db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {

			//GET FAQ OBJECT
     	$sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id);
     	if($sitefaq->featured == 0) {
   		  $sitefaq->featured = 1;	
   		}
   		else {
   			$sitefaq->featured = 0;
   		}

			//SAVE CHANGES AND COMMIT
   		$sitefaq->save();
 			$db->commit();
	 	}
   	catch( Exception $e ){
     $db->rollBack();
     throw $e;
   	}

		//REDIRECT
  	$this->_redirect('admin/sitefaq/site-faq/manage');   
 	}

	//ACTION FOR SET THE FAQ WEIGHT
  public function weightAction() {

		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//GET FAQ ID
  	$faq_id = $this->_getParam('faq_id');

		$this->view->form = $form = new Sitefaq_Form_Admin_Sitefaq_Weight();

    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

		//BEGIN TRANSCATION
   	$db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {

			//GET FAQ OBJECT
     	$sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id);
   		$sitefaq->weight = $_POST['weight'];

			//SAVE CHANGES AND COMMIT
   		$sitefaq->save();
 			$db->commit();
	 	}
   	catch( Exception $e ){
     $db->rollBack();
     throw $e;
   	}

		$this->_forward('success', 'utility', 'core', array(
				'smoothboxClose' => 10,
				'parentRefresh'=> 10,
				'messages' => array('')
		));   
 	}

  //ACTION FOR DELETE THE FAQ
  public function deleteAction()
  {
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET FAQ ID
		$this->view->faq_id = $faq_id = $this->_getParam('faq_id');

		if( $this->getRequest()->isPost()){

			//DELETE FAQ OBJECT
			Engine_Api::_()->getItem('sitefaq_faq', $faq_id)->delete();

			$this->_forward('success', 'utility', 'core', array(
			   'smoothboxClose' => 10,
			   'parentRefresh'=> 10,
			   'messages' => array('')
			));
   	}
		$this->renderScript('admin-site-faq/delete.tpl');
	}
 
  //ACTION FOR MULTI DELETE FAQS
  public function multiDeleteAction()
  {
    if ($this->getRequest()->isPost()) {

			//GET FORM VALUES
      $values = $this->getRequest()->getPost();
      foreach ($values as $key=>$value) {
        if ($key == 'delete_' . $value) {

        	//GET FAQ ID
          $faq_id = (int)$value;

					//DELETE FAQ OBJECT
					Engine_Api::_()->getItem('sitefaq_faq', $faq_id)->delete();
        }
      }
    }

		//REDIRECT
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
  }

}