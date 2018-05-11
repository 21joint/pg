<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_AdminSettingsController extends Core_Controller_Action_Admin
{
	//ACTION FOR GLOBAL SETTINGS
  public function indexAction()
  {
    $onactive_disabled = array("sitefaq_editor", "sitefaq_tag", "sitefaq_search", "sitefaq_categories", "sitefaq_email", "sitefaq_link", "sitefaq_multilanguage", "sitefaq_languages", "sitefaq_code_share", "submit", "sitefaq_redirection");
    $afteractive_disabled = array("environment_mode", "submit_lsetting");
    
    $redirectionPrevious = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.redirection', 'home');    

    $pluginName = 'sitefaq';
    if (!empty($_POST[$pluginName . '_lsettings']))
      $_POST[$pluginName . '_lsettings'] = @trim($_POST[$pluginName . '_lsettings']);
    
    include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license1.php';
    
    $redirectionNew = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.redirection', 'home');
    if($redirectionPrevious != $redirectionNew) { 
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $db->update('engine4_core_menuitems', array('params' => '{"route":"sitefaq_general","action":"'.$redirectionNew.'"}'), array('name = ?' => 'sitefaq_core_main'));
    }    
  }

	//ACTION FOR LEVEL SETTINGS
  public function levelAction()
  {
  	//MAKE NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_level');

    //FETCH LEVEL ID 
    if( null !== ($level_id = $this->_getParam('id')) ) {
      $level = Engine_Api::_()->getItem('authorization_level', $level_id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }

    if( !$level instanceof Authorization_Model_Level ) {
      throw new Engine_Exception($this->view->translate('missing level'));
    }

		//GET LEVEL ID
    $level_id = $level->level_id;

    //GENERATE FORM
    $this->view->form = $form = new Sitefaq_Form_Admin_Settings_Level(array(
      'public' => ( in_array($level->type, array('public')) ),
      'moderator' => ( in_array($level->type, array('admin', 'moderator')) ),
    ));

		if(!empty($level_id)) {
			$form->level_id->setValue($level_id);
		}

    //GET AUTHORIZATION
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');

    if( !$this->getRequest()->isPost() ) {
      $form->populate($permissionsTable->getAllowed('sitefaq_faq', $level_id, array_keys($form->getValues())));
      return;
    }
    
		//FORM VALIDATION
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    //GET POSTED VALUE
    $values = $form->getValues();

		if($level_id != 5) {
			unset($values['dummy_sitefaq_creation']);
		}

		unset($values['dummy_sitefaq_general']);

		//BEGIN TRANSCATION
    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();

    try {
	  include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license2.php';
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

	$form->addNotice('Your changes have been saved.');
  }

  //ACTION FOR GETTING THE CATGEORIES, SUBCATEGORIES AND 3RD LEVEL CATEGORIES
  public function categoriesAction() {

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_categories');

    //GET TASK
    if (isset($_POST['task'])) {
      $task = $_POST['task'];
    } elseif (isset($_GET['task'])) {
      $task = $_GET['task'];
    } else {
      $task = "main";
    }

    //GET CATEGORIES TABLE
    $tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');
    $tableCategoryName = $tableCategory->info('name');

		//GET STORAGE API
		$this->view->storage = Engine_Api::_()->storage();

    //GET FAQs TABLE
    $tableSitefaq = Engine_Api::_()->getDbtable('faqs', 'sitefaq');

    if ($task == "savecat") {

      //GET CATEGORY ID
      $category_id = $_GET['cat_id'];

      $cat_title_withoutparse = $_GET['cat_title'];

      //GET CATEGORY TITLE
      $cat_title = str_replace("'", "\'", trim($_GET['cat_title']));

      //GET CATEGORY DEPENDANCY
      $cat_dependency = $_GET['cat_dependency'];
      $subcat_dependency = $_GET['subcat_dependency'];
      if ($cat_title == "") {
        if ($category_id != "new") {
          if ($cat_dependency == 0) {
						//ON CATEGORY DELETE
            $row_ids = $tableCategory->getSubCategories($category_id);
            foreach ($row_ids as $values) {
              $tableCategory->delete(array('subcat_dependency = ?' => $values->category_id, 'cat_dependency = ?' => $values->category_id));
              $tableCategory->delete(array('category_id = ?' => $values->category_id));
            }

						//FAQ TABLE CATEGORY DELETE WORK
            $tableSitefaq->updateFaqsCategories($category_id, 'category_delete', 0);

            $tableCategory->delete(array('category_id = ?' => $category_id));

          } else {
            $tableCategory->update(array('category_name' => $cat_title), array('category_id = ?' => $category_id, 'cat_dependency = ?' => $cat_dependency));

						//FAQ TABLE SUB-CATEGORY/3RD LEVEL DELETE WORK
            $tableSitefaq->updateFaqsCategories($category_id, '', 0);

            $tableCategory->delete(array('cat_dependency = ?' => $category_id, 'subcat_dependency = ?' => $category_id));
            $tableCategory->delete(array('category_id = ?' => $category_id));
          }
        }
        //SEND AJAX CONFIRMATION
        echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><script type='text/javascript'>";
        echo "window.parent.removecat('$category_id');";
        echo "</script></head><body></body></html>";
        exit();
      } else {
        if ($category_id == 'new') {
          $row_info = $tableCategory->fetchRow($tableCategory->select()->from($tableCategoryName, 'max(cat_order) AS cat_order'));
          $cat_order = $row_info['cat_order'] + 1;
          $row = $tableCategory->createRow();
          $row->category_name = $cat_title_withoutparse;
          $row->cat_order = $cat_order;
          $row->cat_dependency = $cat_dependency;
          $row->subcat_dependency = $subcat_dependency;
          $newcat_id = $row->save();
        } else {
          $tableCategory->update(array('category_name' => $cat_title_withoutparse), array('category_id = ?' => $category_id));
          $newcat_id = $category_id;
        }

        //SEND AJAX CONFIRMATION
        echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><script type='text/javascript'>";
        echo "window.parent.savecat_result('$category_id', '$newcat_id', '$cat_title', '$cat_dependency', '$subcat_dependency');";
        echo "</script></head><body></body></html>";
        exit();
      }
    } elseif ($task == "changeorder") {
      $divId = $_GET['divId'];
      $sitefaqOrder = explode(",", $_GET['sitefaqorder']);
      //RESORT CATEGORIES
      if ($divId == "categories") {
        for ($i = 0; $i < count($sitefaqOrder); $i++) {
          $category_id = substr($sitefaqOrder[$i], 4);
          $tableCategory->update(array('cat_order' => $i + 1), array('category_id = ?' => $category_id));
        }
      } elseif (substr($divId, 0, 7) == "subcats") {
        for ($i = 0; $i < count($sitefaqOrder); $i++) {
          $category_id = substr($sitefaqOrder[$i], 4);
          $tableCategory->update(array('cat_order' => $i + 1), array('category_id = ?' => $category_id));
        }
      } elseif (substr($divId, 0, 11) == "treesubcats") {
        for ($i = 0; $i < count($sitefaqOrder); $i++) {
          $category_id = substr($sitefaqOrder[$i], 4);
          $tableCategory->update(array('cat_order' => $i + 1), array('category_id = ?' => $category_id));
        }
      }
    }

    $categories = array();
    $category_info = $tableCategory->getCategories(null);
    foreach ($category_info as $value) {
      $sub_cat_array = array();
      $subcategories = $tableCategory->getAllCategories($value->category_id, 'subcategory_id', 0, 'subcategory_id', 0, 0, null, null);
      foreach ($subcategories as $subresults) {
        $subsubcategories = $tableCategory->getAllCategories($subresults->category_id, 'subsubcategory_id', 0, 'subsubcategory_id', 0, 0, null, null);
        $treesubarrays[$subresults->category_id] = array();

        foreach ($subsubcategories as $subsubcategoriesvalues) {

					//GET TOTAL FAQ COUNT
					$subsubcategory_faq_count = $tableSitefaq->getFaqsCount($subsubcategoriesvalues->category_id, 'subsubcategory_id', 0);

          $treesubarrays[$subresults->category_id][] = $treesubarray = array('tree_sub_cat_id' => $subsubcategoriesvalues->category_id,
              'tree_sub_cat_name' => $subsubcategoriesvalues->category_name,
							'count' => $subsubcategory_faq_count,
							'file_id' => $subsubcategoriesvalues->file_id,
              'order' => $subsubcategoriesvalues->cat_order);
        }

				//GET TOTAL FAQ COUNT
				$subcategory_faq_count = $tableSitefaq->getFaqsCount($subresults->category_id, 'subcategory_id', 0);

         $sub_cat_array[] = $tmp_array = array('sub_cat_id' => $subresults->category_id,
            'sub_cat_name' => $subresults->category_name,
            'tree_sub_cat' => $treesubarrays[$subresults->category_id],
            'count' => $subcategory_faq_count,
						'file_id' => $subresults->file_id,
            'order' => $subresults->cat_order);
      }

			//GET TOTAL FAQ COUNT
			$category_faq_count = $tableSitefaq->getFaqsCount($value->category_id, 'category_id', 0);

      $categories[] = $category_array = array('category_id' => $value->category_id,
          'category_name' => $value->category_name,
          'order' => $value->cat_order,
          'count' => $category_faq_count,
					'file_id' => $value->file_id,
          'sub_categories' => $sub_cat_array);
    }

	include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license2.php';
  }

	//ACTION FOR MAPPING OF FAQs
	Public function mappingCategoryAction()
	{
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET CATEGORY ID
		$this->view->catid = $catid = $this->_getParam('catid');

		//GET CATEGORY TITLE
		$this->view->oldcat_title = $oldcat_title = $this->_getParam('oldcat_title');

		//GET CATEGORY DEPENDANCY
		$this->view->subcat_dependency = $subcat_dependency = $this->_getParam('subcat_dependency');

    //CREATE FORM
    $this->view->form = $form = new Sitefaq_Form_Admin_Settings_Mapping();

		$this->view->close_smoothbox = 0;

    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

		if( $this->getRequest()->isPost()){ 

			//GET FORM VALUES
			$values = $form->getValues();

			//GET FAQ TABLE
			$tableFaq = Engine_Api::_()->getDbtable('faqs', 'sitefaq');

			//GET CATEGORY TABLE
			$tableCategory = Engine_Api::_()->getDbtable('categories', 'sitefaq');

			//ON CATEGORY DELETE
			$rows = $tableCategory->getSubCategories($catid);
			foreach ($rows as $row) {
				$tableCategory->delete(array('subcat_dependency = ?' => $row->category_id, 'cat_dependency = ?' => $row->category_id));
				$tableCategory->delete(array('category_id = ?' => $row->category_id));
			}

			//FAQ TABLE CATEGORY DELETE WORK
			if(isset($values['new_category_id']) && !empty($values['new_category_id']) ) {
				$tableFaq->updateFaqsCategories($catid, 'category_delete', $values['new_category_id']);
			}
			else {
				$tableFaq->updateFaqsCategories($catid, 'category_delete', 0);
			}

			$tableCategory->delete(array('category_id = ?' => $catid));
   	}

		$this->view->close_smoothbox = 1;
	}

	//ACTION FOR ADD THE CATEGORY ICON
	Public function addIconAction()
	{
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET CATEGORY ID
		$this->view->category_id = $category_id = $this->_getParam('category_id');
		$category = Engine_Api::_()->getItem('sitefaq_category', $category_id);

    //CREATE FORM
    $this->view->form = $form = new Sitefaq_Form_Admin_Settings_Addicon();

		$this->view->close_smoothbox = 0;

    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

		//UPLOAD PHOTO
		if( isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name']) )
		{
			//UPLOAD PHOTO
			$photoFile = $category->setPhoto($_FILES['photo']);

			//UPDATE FILE ID IN CATEGORY TABLE
			if(!empty($photoFile->file_id)) {
				$category->file_id = $photoFile->file_id;
				$category->save();
			}
		}

		$this->view->close_smoothbox = 1;
	}

	//ACTION FOR EDIT THE CATEGORY ICON
	Public function editIconAction()
	{
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET CATEGORY ID
		$this->view->category_id = $category_id = $this->_getParam('category_id');

		//GET CATEGORY ITEM
		$category = Engine_Api::_()->getItem('sitefaq_category', $category_id);

    //CREATE FORM
    $this->view->form = $form = new Sitefaq_Form_Admin_Settings_Editicon();

		$this->view->close_smoothbox = 0;

    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

		//UPLOAD PHOTO
		if( isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name']) )
		{
			//UPLOAD PHOTO
			$photoFile = $category->setPhoto($_FILES['photo']);

			//UPDATE FILE ID IN CATEGORY TABLE
			if(!empty($photoFile->file_id)) {
				$previous_file_id = $category->file_id;
				$category->file_id = $photoFile->file_id;
				$category->save();
			
				//DELETE PREVIOUS CATEGORY ICON
				$file = Engine_Api::_()->getItem('storage_file', $previous_file_id);
				$file->delete();
			}
		}

		$this->view->close_smoothbox = 1;
	}

  //ACTION FOR DELETE THE CATEGORY ICON
  public function deleteIconAction()
  {
		//SET LAYOUT
		$this->_helper->layout->setLayout('admin-simple');

		//GET CATEGORY ID
		$this->view->category_id = $category_id = $this->_getParam('category_id');

		//GET CATEGORY ITEM
		$category = Engine_Api::_()->getItem('sitefaq_category', $category_id);

		$this->view->close_smoothbox = 0;

		if( $this->getRequest()->isPost() && !empty($category->file_id)){

			//DELETE CATEGORY ICON
			$file = Engine_Api::_()->getItem('storage_file', $category->file_id);
			$file->delete();

			//UPDATE FILE ID IN CATEGORY TABLE
			$category->file_id = 0;
			$category->save();

			$this->view->close_smoothbox = 1;
   	}
		$this->renderScript('admin-settings/delete-icon.tpl');
	}

  //ACTINO FOR SEARCH FORM TAB
  public function formSearchAction() {

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_form_search');

		//GET SEARCH TABLE
    $tableSearchForm = Engine_Api::_()->getDbTable('searchformsetting', 'seaocore');

    //CHECK POST
    if ($this->getRequest()->isPost()) {
		
			//BEGIN TRANSCATION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      $values = $_POST;
      $rowCategory = $tableSearchForm->getFieldsOptions('sitefaq', 'category_id');
      $defaultCategory = 0;
			$defaultAddition = 0;
      try {
        foreach ($values['order'] as $key => $value) {
          $tableSearchForm->update(array('order' =>  $defaultAddition + $defaultCategory + $key + 1), array('searchformsetting_id =?' => (int) $value));

          if (!empty($rowCategory) && $value == $rowCategory->searchformsetting_id) {
            $defaultCategory = 1;
						$defaultAddition = 10000000;
					}
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }

		//MAKE QUERY
		$select = $tableSearchForm->select()->where('module = ?', 'sitefaq')->order('order');

		include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license2.php';
  }

  //ACTION FOR DISPLAY/HIDE FIELDS OF SEARCH FORM
  public function diplayFormAction() {
  	
		//UPDATE SEARCH FORM VALUES
    $field_id = $this->_getParam('id');
    $display = $this->_getParam('display');
    if (!empty($field_id)) {
      Engine_Api::_()->getDbTable('searchformsetting', 'seaocore')->update(array('display' => $display), array('module = ?' => 'sitefaq', 'searchformsetting_id = ?' => (int) $field_id));
    }

		//REDIRECT
    $this->_redirect('admin/sitefaq/settings/form-search');
  }


  //ACTION FOR REPORT OPTIONS SECTION
  public function optionAction()
  {
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_helpful');

    $this->view->subNavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitefaq_admin_submain', array(), 'sitefaq_admin_submain_option_tab');

    $faqoptionTable = Engine_Api::_()->getDbtable('options', 'sitefaq');
 
    // MAKE QUERY
    $selectFaqoptionTable = $faqoptionTable->select();
    $this->view->faqoptions = $resultFaqoptionTable = $faqoptionTable->fetchAll($selectFaqoptionTable);

  }


  //ACTION FOR MULTI DELETE FAQS
  public function multiAddAction()
  {
    if ($this->getRequest()->isPost()) {

			//GET FORM VALUES
      $values = $this->getRequest()->getPost();
      $faqoptionTable = Engine_Api::_()->getDbtable('options', 'sitefaq');
      $faqoptionTable->update(array('enable' => 0));
      foreach ($values as $key=>$value) {
        if ($key == 'add_' . $value) {

        	//GET FAQ ID
          $option_id = (int)$value;

					// MAKE QUERY
          $check_option_enable = $faqoptionTable->select()
																								->from($faqoptionTable->info('name'), array('enable'))
																								->where('option_id =?',$option_id)
																								->query()
																								->fetchColumn();

          if( empty($check_option_enable) ) {
            $faqoptionTable->update(array('enable' => 1), array('option_id = ?' => $option_id));
          }
        }
      }
    }

		//REDIRECT
    return $this->_helper->redirector->gotoRoute(array('action' => 'option'));
  }

  //ACTION FOR REPORT HELPFUL SECTION
  public function helpfulReportAction()
  {
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_helpful');

    $this->view->subNavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitefaq_admin_submain', array(), 'sitefaq_admin_submain_helpfulreport_tab');

		//GET SITEFAQ API
		$this->view->sitefaq_api = Engine_Api::_()->sitefaq();

    //FORM GENERATION
    $this->view->formFilter = $formFilter = new Sitefaq_Form_Admin_Question_Filter();
    $page = $this->_getParam('page', 1);

    $values = array();

    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }

    $values = array_merge(array(
        'order' => 'faq_id',
        'order_direction' => 'DESC',
            ), $values);

    //SEND FORM VALUES TO TPL
    $this->view->formValues = array_filter($values);
    $this->view->assign($values);

		//SEND ORDER DIRECTION TO TPL
		$this->view->order_direction = !empty($values['order_direction']) ? $values['order_direction'] : 'DESC';
   
    $faqoptionTable = Engine_Api::_()->getDbtable('options', 'sitefaq');
    $tableSitefaqoptionName = $faqoptionTable->info('name');

    //GET FAQ HELP TABLE
    $tableSitefaqHelp = Engine_Api::_()->getDbtable('helps', 'sitefaq');
    $tableSitefaqHelpName = $tableSitefaqHelp->info('name');

    //GET FAQ TABLE
    $tableSitefaq = Engine_Api::_()->getDbtable('faqs', 'sitefaq');
    $tableSitefaqName = $tableSitefaq->info('name');

		//MAKE QUERY
    $select = $faqoptionTable->select()
            ->setIntegrityCheck(false)
            ->from($tableSitefaqHelpName,array('owner_id','faq_id','modified_date'))
            ->join($tableSitefaqoptionName, "$tableSitefaqHelpName.option_id  = $tableSitefaqoptionName.option_id")
            ->join($tableSitefaqName, "$tableSitefaqName.faq_id  = $tableSitefaqHelpName.faq_id",array('title'))
            ->where($tableSitefaqHelpName .'.option_id !=?',0);

    $select->order((!empty($values['order']) ? $values['order'] : 'faq_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    //MAKE PAGINATOR
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator->setItemCountPerPage(100);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
  }

  public function editAction()
  {
    $option_id = $this->_getParam('id', null);

    // SITEFAQ OPTION TABLE OBEJECT
    $sitefaqOption = Engine_Api::_()->getItem('sitefaq_option', $option_id);

    $this->view->form = $form = new Sitefaq_Form_Admin_Settings_Edit();

    //SHOW PRE-FIELD FORM
    $form->populate($sitefaqOption->toArray());

    //IF NOT POST OR FORM NOT VALID THAN RETURN
    if ( !$this->getRequest()->isPost() ) {
      $form->populate($sitefaqOption->toArray());
      return;
    }

    //IF NOT POST OR FORM NOT VALID THAN RETURN
    if ( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    //GET FORM VALUES
    $values = $form->getValues();

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $sitefaqOption->setFromArray($values);
      $sitefaqOption->save();
      $db->commit();
    }
    catch ( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
   
		$this->_forward('success', 'utility', 'core', array(
				'smoothboxClose' => true,
				'parentRefresh' => true,
				'format' => 'smoothbox',
				'messages' => Zend_Registry::get('Zend_Translate')->_('Your reason has been edit successfully.')
		));
  }

	//ACTION FOR FAQ SECTION
  public function faqAction()
  {
		//GET NAVIGATION
  	$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      	 ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_faq');
  }

  public function readmeAction() {

  }

}