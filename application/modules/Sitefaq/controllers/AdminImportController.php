<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminImportController.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_AdminImportController extends Core_Controller_Action_Admin {

  //ACTION FOR IMPORTING DATA FROM LISTING TO PAGE
  public function indexAction() {

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_import');
  }

  //ACTION FOR IMPORTING DATA FROM COMMUNITY ADS PLUGIN
  public function importAction() {

    //INCREASE THE MEMORY ALLOCATION SIZE AND INFINITE SET TIME OUT
    ini_set('memory_limit', '2048M');
    set_time_limit(0);

		//IF COMMUNITY AD PLUGIN IS NOT INSTALLED OR NOT ACTIVATED
		if(!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad')) {
			return;
		}

    $this->_helper->layout->setLayout('admin-simple');

    //MAKE FORM
    $this->view->form = $form = new Sitefaq_Form_Admin_Import_Import();
	include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license2.php';

		//VISIBILITY COMBINATION CHECKING
		if(((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && in_array(0, $_POST['profile_types'])) && ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && in_array(0, $_POST['member_levels'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['networks']) && !empty($_POST['networks']) && in_array(0, $_POST['networks'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels']))))) {
				$error = $this->view->translate("If you are selecting 'Everyone' option in any of the available privacy fields, then you must also choose 'Everyone' in other privacy options.");
				//$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
		}

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

			//GET FORM VALUES
			$values = $form->getValues();

			$values = !empty($getImportType)? $values: FALSE;

			if( empty($values) ){ return; }

			//GET MEMBER LEVEL SETTINGS
			if(!isset($_POST['member_levels'])) {
				$member_level_visibility = '["0"]';
			}
			else {
				$member_level_visibility = Zend_Json_Encoder::encode($_POST['member_levels']);
				if(strstr($member_level_visibility, '"0"')) {
					$member_level_visibility = '["0"]';
				}
			}

			//GET PROFILE TYPE SETTINGS
			if(!isset($_POST['profile_types'])) {
				$profile_type_visibility = '["0"]';
			}
			else {
				$profile_type_visibility = Zend_Json_Encoder::encode($_POST['profile_types']);
				if(strstr($profile_type_visibility, '"0"')) {
					$profile_type_visibility = '["0"]';
				}
			}

			//GET NETWORKS SETTINGS
			if(!isset($_POST['networks'])) {
				$network_type_visibility = '["0"]';
			}
			else {
				$network_type_visibility = Zend_Json_Encoder::encode($_POST['networks']);
				if(strstr($network_type_visibility, '"0"')) {
					$network_type_visibility = '["0"]';
				}
			}

			//GET CATEGORY TABLE
			$tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');

			//CREATE CATEGORY IF NOT EXIST
			$category_name = Zend_Registry::get('Zend_Translate')->_('Advertising');
			$parent_category_id = 0;
			$category_id = $tableCategory->createCategory($category_name, $parent_category_id, 1);
			
			//CREATE CATEGORY IF NOT EXIST
			$category_name = Zend_Registry::get('Zend_Translate')->_('General');
			$parent_category_id = $category_id;
			$sub_category_id1 = $tableCategory->createCategory($category_name, $parent_category_id, 1);
			$type = 1;

			//CREATE FAQs
			Engine_Api::_()->sitefaq()->importFaqs($parent_category_id, $sub_category_id1, $type, $member_level_visibility, $profile_type_visibility, $network_type_visibility);

			//CREATE CATEGORY IF NOT EXIST
			$category_name = Zend_Registry::get('Zend_Translate')->_('Design Your Ads');
			$parent_category_id = $category_id;
			$sub_category_id2 = $tableCategory->createCategory($category_name, $parent_category_id, 1);
			$type = 2;

			//CREATE FAQs
			Engine_Api::_()->sitefaq()->importFaqs($parent_category_id, $sub_category_id2, $type, $member_level_visibility, $profile_type_visibility, $network_type_visibility);
			
			//CREATE CATEGORY IF NOT EXIST
			$category_name = Zend_Registry::get('Zend_Translate')->_('Targeting');
			$parent_category_id = $category_id;
			$sub_category_id3 = $tableCategory->createCategory($category_name, $parent_category_id, 1);
			$type = 3;

			//CREATE FAQs
			Engine_Api::_()->sitefaq()->importFaqs($parent_category_id, $sub_category_id3, $type, $member_level_visibility, $profile_type_visibility, $network_type_visibility);

      //CLOSE THE SMOOTHBOX
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => true,
					'parentRefresh' => true,
          'format' => 'smoothbox',
					'messages' => array(Zend_Registry::get('Zend_Translate')->_('FAQs Imported Successfully!'))
      ));
    }
  }

  //ACTION FOR IMPORTING DATA FROM CSV FILE
  public function importPluginsFaqsAction() {

    //INCREASE THE MEMORY ALLOCATION SIZE AND INFINITE SET TIME OUT
    ini_set('memory_limit', '2048M');
    set_time_limit(0);

		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//GET VIEWER DETAILS
 		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$level_id = $viewer->level_id;

    //MAKE FORM
    $this->view->form = $form = new Sitefaq_Form_Admin_Import_Pluginsfaqs();
	
	include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license2.php';

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      //MAKE SURE THAT FILE EXTENSION SHOULD NOT DIFFER FROM ALLOWED TYPE
      $ext = str_replace(".", "", strrchr($_FILES['filename']['name'], "."));
      if (empty($getImportPluginFaq) || !in_array($ext, array('csv', 'CSV'))) {
        $error = $this->view->translate("Invalid file extension. Only 'csv' extension is allowed.");
        //$error = Zend_Registry::get('Zend_Translate')->_($error);
        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }

      //START READING DATA FROM CSV FILE
      $fname = $_FILES['filename']['tmp_name'];
      $fp = fopen($fname, "r");

      if (!$fp) {
        echo "$fname File opening error";
        exit;
      }
			
			$formData = array();
			$formData = $form->getValues();

			if($formData['import_seperate'] == 1) {
				while ($buffer = fgets($fp, 4096)) {
					$explode_array[] = explode('|', $buffer);
				}
			}
			else {
				while ($buffer = fgets($fp, 4096)) {
					$explode_array[] = explode(',', $buffer);
				}
			}
      //END READING DATA FROM CSV FILE

			//VISIBILITY COMBINATION CHECKING
			if(((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && in_array(0, $_POST['profile_types'])) && ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && in_array(0, $_POST['member_levels'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['networks']) && !empty($_POST['networks']) && in_array(0, $_POST['networks'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels']))))) {
					$error = $this->view->translate("If you are selecting 'Everyone' option in any of the available privacy fields, then you must also choose 'Everyone' in other privacy options.");
					//$error = Zend_Registry::get('Zend_Translate')->_($error);
					$form->getDecorator('errors')->setOption('escape', false);
					$form->addError($error);
					return;
			}

			//GET MEMBER LEVEL SETTINGS
			if(!isset($_POST['member_levels'])) {
				$member_level_visibility = '["0"]';
			}
			else {
				$member_level_visibility = Zend_Json_Encoder::encode($_POST['member_levels']);
				if(strstr($member_level_visibility, '"0"')) {
					$member_level_visibility = '["0"]';
				}
			}

			//GET PROFILE TYPE SETTINGS
			if(!isset($_POST['profile_types'])) {
				$profile_type_visibility = '["0"]';
			}
			else {
				$profile_type_visibility = Zend_Json_Encoder::encode($_POST['profile_types']);
				if(strstr($profile_type_visibility, '"0"')) {
					$profile_type_visibility = '["0"]';
				}
			}

			//GET NETWORKS SETTINGS
			if(!isset($_POST['networks'])) {
				$network_type_visibility = '["0"]';
			}
			else {
				$network_type_visibility = Zend_Json_Encoder::encode($_POST['networks']);
				if(strstr($network_type_visibility, '"0"')) {
					$network_type_visibility = '["0"]';
				}
			}

			//GET DEFAULT LANGUAGE COLUMN
// 			$title_column = Engine_Api::_()->sitefaq()->getLanguageColumn('title');
// 			$body_column = Engine_Api::_()->sitefaq()->getLanguageColumn('body');

			//FAQ PRIVACY WORK
			$auth = Engine_Api::_()->authorization()->context;
			$roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
			$auth_comment = "registered";
			$commentMax = array_search($auth_comment, $roles);
			$auth_view = "everyone";
			$viewMax = array_search($auth_view, $roles);

			$settings = Engine_Api::_()->getApi('settings', 'core');
			$multilanguage_allow = $settings->getSetting('sitefaq.multilanguage', 0);
			$languages = $settings->getSetting('sitefaq.languages');
			$total_enabled_language = Count($languages);
			$defaultLanguage = $settings->getSetting('core.locale.locale', 'en');
			if($defaultLanguage == 'auto'){
				$defaultLanguage = 'en';
			}

			$title_body_array = array();
			if(!empty($multilanguage_allow) && $total_enabled_language > 1) {
				$localeMultiOptions = Engine_Api::_()->sitefaq()->getLanguageArray();
				foreach($languages as $label) {				
					if($label != $defaultLanguage && isset($localeMultiOptions[$label]) ){
						$title_body_array[] = $label; 
					}
				}
			}

			$total_elements = Count($title_body_array);
			if(empty($total_elements)) $total_elements = 4;
			else $total_elements = ($total_elements*2) + 4;
      foreach ($explode_array as $explode_data) {

        //GET PAGE DETAILS FROM DATA ARRAY
        $values = array();
				if(!empty($multilanguage_allow) && $total_enabled_language > 1) {

					if($defaultLanguage != 'en') {
						$values["title_$defaultLanguage"] = trim($explode_data[0]);
						$values["body_$defaultLanguage"] = trim($explode_data[1]);
					}
					else {
						$values["title"] = trim($explode_data[0]);
						$values["body"] = trim($explode_data[1]);
					}

					foreach($title_body_array as $key => $value) {
						if($value == 'en') {
							$values["title"] = trim($explode_data[$key+2]);
							$values["body"] = trim($explode_data[$key+3]);
						}
						else {
							$values["title_$value"] = trim($explode_data[$key+2]);
							$values["body_$value"] = trim($explode_data[$key+3]);
						}
					}
				}
				else {
					$values['title'] = trim($explode_data[0]);
					$values['body'] = trim($explode_data[1]);
				}

        $get_category = trim($explode_data[$total_elements-2]);
        $get_subcategory = trim($explode_data[$total_elements-1]);

        //IF FAQ TITLE AND CATEGORY IS EMPTY THEN CONTINUE;
        if (empty($values['title']) || empty($values['body']) || empty($get_category)) {
          continue;
        }

				//GET FAQ CATEGORY TABLE
				$categoryTable = Engine_Api::_()->getDbTable('categories', 'sitefaq');

				//GET CATEGORY ID
				$category_id = $categoryTable->getCategoryId($get_category, 0);

				if(empty($category_id)) {
					continue;
				}

				//GET SUB-CATEGORY ID
				$sub_category_id = 0;
				if(!empty($get_subcategory)) {
					$sub_category_id = $categoryTable->getCategoryId($get_subcategory, $category_id);
				}

				$category_id = '["'.$category_id.'"]';
				$sub_category_id = '["'.$sub_category_id.'"]';

				//SAVE DATA IN FAQ TABLE
				$sitefaqTable = Engine_Api::_()->getDbTable('faqs', 'sitefaq');
				$db = $sitefaqTable->getAdapter();
				$db->beginTransaction();

				try {

					//CREATE FAQ
					$sitefaq = $sitefaqTable->createRow();
					$sitefaq->setFromArray($values);

					$sitefaq->owner_id = $viewer_id;

// 					$sitefaq->title = $values['title'];
// 					$sitefaq->body = $values['body'];
// 					$sitefaq->$title_column = $values['title'];
// 					$sitefaq->$body_column = $values['body'];

					$sitefaq->category_id = $category_id;
					$sitefaq->subcategory_id = $sub_category_id;
					$sitefaq->subsubcategory_id = '["0"]';
					$sitefaq->member_levels = $member_level_visibility;
					$sitefaq->profile_types = $profile_type_visibility;
					$sitefaq->networks = $network_type_visibility;
					$sitefaq->approved = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'approved');
					$sitefaq->save();

					//FAQ COMMENT PRIVACY
					foreach ($roles as $i => $role) {
						$auth->setAllowed($sitefaq, $role, 'comment', ($i <= $commentMax));
					}

					//FAQ VIEW PRIVACY WORK
					foreach ($roles as $i => $role) {
						$auth->setAllowed($sitefaq, $role, 'view', ($i <= $viewMax));
					}

					//COMMIT
					$db->commit();

				} catch (Exception $e) {
					$db->rollBack();
					throw $e;
				}
      }

      //CLOSE THE SMOOTHBOX
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => true,
					'parentRefresh' => true,
          'format' => 'smoothbox',
					'messages' => array(Zend_Registry::get('Zend_Translate')->_('FAQs Imported Successfully!'))
      ));
    }
  }

  //ACTION FOR IMPORTING DATA FROM CSV FILE
  public function importFaqsAction() {

    //INCREASE THE MEMORY ALLOCATION SIZE AND INFINITE SET TIME OUT
    ini_set('memory_limit', '2048M');
    set_time_limit(0);

		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

		//GET VIEWER DETAILS
 		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$level_id = $viewer->level_id;

		//CSV FILE PATH
    $logPath = APPLICATION_PATH . '/application/modules/Sitefaq/settings/import_csvs';
		@chmod($logPath, 0777);

    //GET ALL EXISTING IMPORT HISTORY FILES
    $logFiles = array();
		$existing_plugins = array('activity', 'advancedactivity', 'sitealbum', 'sitelike', 'sitepageoffer', 'sitepagebadge', 'featuredcontent', 'sitepagediscussion', 'sitepagelikebox', 'mobi', 'advancedslideshow', 'birthday', 'birthdayemail', 'communityad', 'dbbackup', 'facebookse', 'facebooksefeed', 'facebooksepage', 'feedback', 'groupdocument', 'eventdocument', 'grouppoll', 'mapprofiletypelevel', 'mcard','poke', 'sitealbum', 'sitepageinvite', 'siteslideshow', 'socialengineaddon','seaocore', 'suggestion', 'userconnection', 'sitepageform', 'sitepageadmincontact', 'sitebusinessbadge', 'sitebusinessoffer', 'sitebusinessdiscussion', 'sitebusinesslikebox', 'sitebusinessinvite', 'sitebusinessform', 'sitebusinessadmincontact', 'album', 'blog', 'classified', 'document', 'event', 'forum', 'poll', 'video', 'list', 'group', 'music', 'recipe', 'user', 'sitepage', 'sitepagenote', 'sitepagevideo','sitepagepoll', 'sitepagemusic','sitepagealbum','sitepageevent', 'sitepagereview', 'sitepagedocument', 'sitebusiness', 'sitebusinessalbum', 'sitebusinessdocument', 'sitebusinessevent', 'sitebusinessnote', 'sitebusinesspoll', 'sitebusinessmusic', 'sitebusinessvideo', 'sitebusinessreview', 'sitefaq', 'siteestore', 'sitereview', 'sitepagemember', 'sitebusinessmember', 'sitestore', 'sitegroupbadge', 'sitegroupoffer', 'sitegroupdiscussion', 'sitegrouplikebox', 'sitegroupinvite', 'sitegroupform', 'sitegroupadmincontact',  'sitegroup', 'sitegroupalbum', 'sitegroupdocument', 'sitegroupevent', 'sitegroupnote', 'sitegrouppoll', 'sitegroupmusic', 'sitegroupvideo', 'sitegroupreview',  'sitegroupmember', 'siteevent', 'siteeventdocument', 'siteeventinvite', 'siteeventrepeat', 'siteeventticket', 'siteforum', 'sitenews','sitecrowdfunding');

		$previous_files = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq_import');
		$previous_files = unserialize($previous_files);

    foreach (scandir($logPath) as $key => $file) { 

			if(strtolower(substr($file, -9)) == '_faqs.csv') {
				$explode = explode('_faqs.csv', $file);
				if(!in_array($explode[0], $previous_files) && in_array($explode[0], $existing_plugins)) {
					$is_enabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled($explode[0]);
					if (!empty($is_enabled)) {
						$logFiles[$key+1] = $file;
					}
				}

				if(!in_array($explode[0], $existing_plugins) && !in_array($explode[0], $previous_files)) {
					$logFiles[$key+1] = $file;
				}
			}
		}

		$this->view->show_form = 0;
		if(!empty($logFiles)) {
			$this->view->show_form = 1;
		}

    //MAKE FORM
    $this->view->form = $form = new Sitefaq_Form_Admin_Import_Faqs();
	include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license2.php';

		//VISIBILITY COMBINATION CHECKING
		if(((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && in_array(0, $_POST['profile_types'])) && ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && in_array(0, $_POST['member_levels'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['networks']) && !empty($_POST['networks']) && in_array(0, $_POST['networks'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels']))))) {
				$error = $this->view->translate("If you are selecting 'Everyone' option in any of the available privacy fields, then you must also choose 'Everyone' in other privacy options.");
				//$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
		}

		//GET MEMBER LEVEL SETTINGS
		if(!isset($_POST['member_levels'])) {
			$member_level_visibility = '["0"]';
		}
		else {
			$member_level_visibility = Zend_Json_Encoder::encode($_POST['member_levels']);
			if(strstr($member_level_visibility, '"0"')) {
				$member_level_visibility = '["0"]';
			}
		}

		//GET PROFILE TYPE SETTINGS
		if(!isset($_POST['profile_types'])) {
			$profile_type_visibility = '["0"]';
		}
		else {
			$profile_type_visibility = Zend_Json_Encoder::encode($_POST['profile_types']);
			if(strstr($profile_type_visibility, '"0"')) {
				$profile_type_visibility = '["0"]';
			}
		}

		//GET NETWORKS SETTINGS
		if(!isset($_POST['networks'])) {
			$network_type_visibility = '["0"]';
		}
		else {
			$network_type_visibility = Zend_Json_Encoder::encode($_POST['networks']);
			if(strstr($network_type_visibility, '"0"')) {
				$network_type_visibility = '["0"]';
			}
		}

		//FAQ PRIVACY WORK
		$auth = Engine_Api::_()->authorization()->context;
		$roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
		$auth_comment = "registered";
		$commentMax = array_search($auth_comment, $roles);
		$auth_view = "everyone";
		$viewMax = array_search($auth_view, $roles);

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

			$files_array = !empty($getImportFaqs)? $_POST['files']: FALSE;
			$file_name_array = array();
			if(!empty($files_array)) {

				foreach($files_array as $file_id) {

					foreach($logFiles as $key => $name) {
						if($key == $file_id) {
							$filename = $name;
						}
					}

					if(!empty($filename)) {

						//START READING DATA FROM CSV FILE
						$fname = $logPath.'/'.$filename;
						$fp = fopen($fname, "r");

						if (!$fp) {
							echo "$fname File opening error";
							exit;
						}
						
						$formData = array();
						$formData = $form->getValues();

						$explode_array = array();
						while ($buffer = fgets($fp, 4096)) {
							$explode_array[] = explode('|', $buffer);
						}
						//END READING DATA FROM CSV FILE

						//GET DEFAULT LANGUAGE COLUMN
// 						$title_column = Engine_Api::_()->sitefaq()->getLanguageColumn('title');
// 						$body_column = Engine_Api::_()->sitefaq()->getLanguageColumn('body');

						foreach ($explode_array as $explode_data) {

							//GET PAGE DETAILS FROM DATA ARRAY
							$values = array();
							$values['title'] = trim($explode_data[0]);
							$values['body'] = trim($explode_data[1]);
							$values['category'] = trim($explode_data[2]);
							$values['sub_category'] = trim($explode_data[3]);

							//IF FAQ TITLE AND CATEGORY IS EMPTY THEN CONTINUE;
							if (empty($values['title']) || empty($values['body']) || empty($values['category'])) {
								continue;
							}

							//GET CATEGORY TABLE
							$categoryTable = Engine_Api::_()->getDbTable('categories', 'sitefaq');

							//GET CATEGORY ID
							$parent_category_id = 0;
							$category_id = $categoryTable->createCategory($values['category'], $parent_category_id, 0);

							if(empty($category_id)) {
								continue;
							}

							//GET SUB-CATEGORY ID
							$sub_category_id = 0;
							if(!empty($values['sub_category'])) {
								$sub_category_id = $categoryTable->createCategory($values['sub_category'], $category_id, 0);
							}

							$category_id = '["'.$category_id.'"]';
							$sub_category_id = '["'.$sub_category_id.'"]';

							//SAVE DATA IN FAQ TABLE
							$sitefaqTable = Engine_Api::_()->getDbTable('faqs', 'sitefaq');
							$db = $sitefaqTable->getAdapter();
							$db->beginTransaction();

							try {

								//CREATE FAQ
								$sitefaq = $sitefaqTable->createRow();
								$sitefaq->owner_id = $viewer_id;
								$sitefaq->title = $values['title'];
								$sitefaq->body = $values['body'];
// 								$sitefaq->$title_column = $values['title'];
// 								$sitefaq->$body_column = $values['body'];
								$sitefaq->category_id = $category_id;
								$sitefaq->subcategory_id = $sub_category_id;
								$sitefaq->subsubcategory_id = '["0"]';
								$sitefaq->member_levels = $member_level_visibility;
								$sitefaq->profile_types = $profile_type_visibility;
								$sitefaq->networks = $network_type_visibility;
								$sitefaq->approved = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'approved');
								$sitefaq->save();

								//FAQ PRIVACY WORK
								foreach ($roles as $i => $role) {
									$auth->setAllowed($sitefaq, $role, 'comment', ($i <= $commentMax));
									$auth->setAllowed($sitefaq, $role, 'view', ($i <= $viewMax));
								}

								//COMMIT
								$db->commit();

							} catch (Exception $e) {
								$db->rollBack();
								throw $e;
							}
						}

						$file_name = explode('_faqs.csv', $filename);
						$file_name_array[] = $file_name[0];
					}
				}
			}

			if(Count($file_name_array) > 0) {
				$previous_files = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq_import');
				if(empty($previous_files)) {
					$previous_files = array();
					$previous_files = serialize($previous_files);
				}
				$previous_files = unserialize($previous_files);
				if(Count($previous_files) > 0) {
					$file_name_array = array_merge($previous_files, $file_name_array);
				}
				$import_value = serialize($file_name_array);

				Engine_Api::_()->getApi('settings', 'core')->setSetting('sitefaq_import', $import_value);
			}

      //CLOSE THE SMOOTHBOX
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => true,
					'parentRefresh' => true,
          'format' => 'smoothbox',
					'messages' => array(Zend_Registry::get('Zend_Translate')->_('FAQs Imported Successfully!'))
      ));
    }
  }

  //ACTION FOR DOWNLOADING THE CSV TEMPLATE FILE
  public function downloadAction() {

		$path = $this->_getPath();
		$file_path = "$path/example_faqs_import.csv";

		@chmod($path, 0777);
		@chmod($file_path, 0777);

		$file_string = "";
		$settings = Engine_Api::_()->getApi('settings', 'core');
		$multilanguage_allow = $settings->getSetting('sitefaq.multilanguage', 0);
		if(empty($multilanguage_allow)) {
			$file_string = "faq_question|faq_answer|category_name|subcategory_name";
		}
		else {
		 $defaultLanguage = $settings->getSetting('core.locale.locale', 'en');
			if($defaultLanguage == 'auto'){
				$defaultLanguage = 'en';
			}
			$languages = $settings->getSetting('sitefaq.languages');
			$localeMultiOptions = Engine_Api::_()->sitefaq()->getLanguageArray();
			$total_enabled_language = Count($languages);

			$file_string .= "faq_question|faq_answer|";

			if($total_enabled_language > 1) {
				foreach($languages as $label) {				
					if($label == $defaultLanguage) continue;
					$lang_name = $label;
					if(isset($localeMultiOptions[$label]) ){
						$lang_name = $localeMultiOptions[$label];
					}
					$file_string .= "faq_question_$lang_name|faq_answer_$lang_name|";
				}
			}

			$file_string .= "category_name|subcategory_name";
		}

		@chmod($path, 0777);
		@chmod($file_path, 0777);
		$fp = fopen(APPLICATION_PATH . '/temporary/example_faqs_import.csv', 'w+');
		fwrite($fp, $file_string);
		fclose($fp);
  
		//KILL ZEND'S OB
		$isGZIPEnabled = false;
		if (ob_get_level()) {
				$isGZIPEnabled = true;
					@ob_end_clean();
		}

		$path=APPLICATION_PATH."/temporary/example_faqs_import.csv";
		header("Content-Disposition: attachment; filename=" . urlencode(basename($path)), true);
		header("Content-Transfer-Encoding: Binary", true);
		//header("Content-Type: application/x-tar", true);
		header("Content-Type: application/force-download", true);
		header("Content-Type: application/octet-stream", true);
		header("Content-Type: application/download", true);
		header("Content-Description: File Transfer", true);
		if(empty($isGZIPEnabled)){
			header("Content-Length: " . filesize($path), true);
		}
		readfile("$path");
   

    exit();
  }

  protected function _getPath($key = 'path') {
    $basePath = realpath(APPLICATION_PATH . "/temporary");
    return $this->_checkPath($this->_getParam($key, ''), $basePath);
  }

  protected function _checkPath($path, $basePath) {
    //SANATIZE
    $path = preg_replace('/\.{2,}/', '.', $path);
    $path = preg_replace('/[\/\\\\]+/', '/', $path);
    $path = trim($path, './\\');
    $path = $basePath . '/' . $path;

    //Resolve
    $basePath = realpath($basePath);
    $path = realpath($path);

    //CHECK IF THIS IS A PARENT OF THE BASE PATH
    if ($basePath != $path && strpos($basePath, $path) !== false) {
      return $this->_helper->redirector->gotoRoute(array());
    }
    return $path;
  }

}
