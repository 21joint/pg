<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_IndexController extends Seaocore_Controller_Action_Standard
{
	//COMMON ACTION WHICH CALL BEFORE EVERY ACTION OF THIS CONTROLLER
  public function init() {

		//FAQs VIEW PRIVACY CHECK
    if( !$this->_helper->requireAuth()->setAuthParams('sitefaq_faq', null, 'view')->isValid() ) return;
  }

	//ACTION FOR FAQs LISTING
  public function browseAction()
  {
		//IF ANONYMOUS USER THEN SEND HIM TO SIGN IN PAGE
		$check_anonymous_help = $this->_getParam('anonymous');
		if($check_anonymous_help) {
			if( !$this->_helper->requireUser()->isValid() ) return;
		}

		//RENDER PAGE
		$this->_helper->content
         ->setNoRender()
         ->setEnabled();
  }

	//ACTION FOR FAQs LISTING
  public function mobiBrowseAction()
  {
		//RENDER PAGE
		$this->_helper->content
         ->setNoRender()
         ->setEnabled();
  }

	//ACTION FOR HOME PAGE
  public function homeAction()
  {
		//RENDER PAGE
		$this->_helper->content
         ->setNoRender()
         ->setEnabled();
  }

	//ACTION FOR FAQs LISTING
  public function mobiHomeAction()
  {
		//RENDER PAGE
		$this->_helper->content
         ->setNoRender()
         ->setEnabled();
  }

	//ACTION FOR CREATE FAQ
  public function createAction()
  {
		//LOGGED-IN USER CAN CREATE FAQ
    if( !$this->_helper->requireUser->isValid() )
        return;

		//CREATION PRIVACY CHECK
    if( !$this->_helper->requireAuth()->setAuthParams('sitefaq_faq', null, 'create')->isValid() )
        return;

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitefaq_main', array(), 'sitefaq_main_create');

		//GET CORE SETTING API
		$settings = Engine_Api::_()->getApi('settings', 'core');

		//HOW MANY MULTIPLE CATEGORY IS ALLOWED
    $this->view->maxCategories = $settings->getSetting('sitefaq.categories', 2);

		//MULTI LANGUAGE IS ALLOWED OR NOT
		$this->view->multiLanguage = $settings->getSetting('sitefaq.multilanguage', 0);

		//DEFAULT LANGUAGE
		$this->view->defaultLanguage = $defaultLanguage = $settings->getSetting('core.locale.locale', 'en');

		//MULTI LANGUAGE WORK
		$this->view->languageCount = 0;
		$this->view->languageData = array();
		$default_body_link = $this->view->add_show_hide_link = 'body';
		$default_title_link = 'title';
		if($this->view->multiLanguage) {
			//GET LANGUAGE ARRAY
			//$localeMultiOptions = Engine_Api::_()->sitefaq()->getLanguageArray();
			$languages = $settings->getSetting('sitefaq.languages');
			$this->view->languageCount = Count($languages);
			$this->view->languageData = array();
			foreach($languages as $label) {
				$this->view->languageData[] = $label;

				if($this->view->languageCount >= 2  && $defaultLanguage == $label && $label != 'en') {
					$default_body_link = $this->view->add_show_hide_link = "body_$label";
					$default_title_link = "title_$label";
				}
			}
		}

		if(!in_array($defaultLanguage, $this->view->languageData)) {
			$this->view->defaultLanguage = 'en';
		}

		//GET TOTAL CATEGORIES
    $categories = Engine_Api::_()->getDbTable('categories', 'sitefaq')->getCategories(null);
		$this->view->category_exist = 0;
    if (count($categories) != 0) {
			$this->view->category_exist = 1;
		}

		$this->view->alreadyCreated = 1;

    //CREATE FORM
    $this->view->form = $form = new Sitefaq_Form_Create();

		$sitefaq_get_categories = Zend_Registry::isRegistered('sitefaq_get_categories') ? Zend_Registry::get('sitefaq_get_categories') : null;

		//ARRAY INITILIZATION
		$category_id = array();
		$subcategory_id = array();
		$subsubcategory_id = array();

		//GET CATEGORIES ARRAY
		foreach($_POST as $key => $value) {
			$sub = strstr($key, 'sub');
			$subsub = strstr($key, 'subsub');
			$category = strstr($key, 'category_id_');

			if(empty($sub) && !empty($category) && !empty($value)) {

				$explode_array = explode('category_id_', $key);
				$key = $explode_array[1];

				//CATEGORY ID ARRAY
				$category_id[] = "$value";

				if(isset($_POST["category_id_$key"]) && (!isset($_POST["subcategory_id_$key"]) || (isset($_POST["subcategory_id_$key"]) && empty($_POST["subcategory_id_$key"])))) {
					$subcategory_id[] = $subsubcategory_id[] = "0";
				}

			}
			elseif(!empty($sub) && empty($subsub) && !empty($category) && !empty($value)) {

				$explode_array = explode('subcategory_id_', $key);
				$key = $explode_array[1];

				//SUB-CATEGORY ID ARRAY
				$subcategory_id[] = "$value";

				if(isset($_POST["subcategory_id_$key"]) && (!isset($_POST["subsubcategory_id_$key"]) || (isset($_POST["subsubcategory_id_$key"]) && empty($_POST["subsubcategory_id_$key"])))) {
					$subsubcategory_id[] = "0";
				}
			}
			elseif(!empty($subsub) && !empty($category) && !empty($value)) {
				//3RD-LAVEL-CATEGORY ID ARRAY
				$subsubcategory_id[] = "$value";
			}
		}

		$this->view->alreadyCreated = Count($category_id);
		if($this->view->alreadyCreated == 0) {
			$this->view->alreadyCreated = 1;
		}

    //CHECK METHOD/DATA VALIDITY
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    //PROCESS
    $values = $form->getValues();

		$values = !empty($sitefaq_get_categories)? $values: $sitefaq_get_categories;

		if( empty($values) ){ return; }

		//GET VIEWER DETAILS
    $viewer = Engine_Api::_()->user()->getViewer();
		$level_id = $viewer->level_id;
    $values['owner_id'] = $viewer->getIdentity();

		//CATEGORY IS REQUIRED FIELD
		$required_category = 0;
		
		foreach($category_id as $value) {
			if(!empty($value)) {
				$required_category = 1;
				break;
			}
		}

		if (empty($required_category) || empty($category_id)) {
			$error = $this->view->translate('Category <BR /> Please complete this field - it is required.');
			//$error = Zend_Registry::get('Zend_Translate')->_($error);
			$form->getDecorator('errors')->setOption('escape', false);
			$form->addError($error);
			return;
		}

		//WORK FOR DUPLICATION CHECK OF CATEGORY COMBINATION
		for($i = 0; $i < Count($category_id); $i++) {
			$duplicate_value = $category_id[$i].'_'.$subcategory_id[$i].'_'.$subsubcategory_id[$i];
			$duplicate_array[$i] = "'$duplicate_value'";
		}

		$duplicate_array = array_count_values($duplicate_array);

 		//WORK FOR DUPLICATION CHECK OF CATEGORY COMBINATION
		foreach($duplicate_array as $value) {
			if($value > 1) {
				$error = $this->view->translate("Category <BR /> Please choose different combinations for categories - this field can not take same values.");
				//$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
			}
		}
/*
		foreach($category_id as $key => $value) {
			$get_count = Count(array_keys($category_id, $value));
			if($get_count > 1 && $subcategory_id[$key] == 0) {
				$error = $this->view->translate("Category <BR /> Please choose different combinations for categories - this field can not take same values.");
				//$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
			}
		}

		foreach($subcategory_id as $key => $value) {
			$get_count = Count(array_keys($subcategory_id, $value));
			if($get_count > 1 && $subsubcategory_id[$key] == 0) {
				$error = $this->view->translate("Category <BR /> Please choose different combinations for categories - this field can not take same values.");
				//$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
			}
		}
*/
		//VISIBILITY COMBINATION CHECKING
		if(((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && in_array(0, $_POST['profile_types'])) && ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && in_array(0, $_POST['member_levels'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['networks']) && !empty($_POST['networks']) && in_array(0, $_POST['networks'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels']))))) {
				$error = $this->view->translate("If you are selecting 'Everyone' option in any of the available privacy fields, then you must also choose 'Everyone' in other privacy options.");
				//$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
		}

		//GET FAQ TABLE
    $tableSitefaq = Engine_Api::_()->getDbtable('faqs', 'sitefaq');

    $db = $tableSitefaq->getAdapter();
    $db->beginTransaction();

    try {

      //CREATE FAQ
      $sitefaq = $tableSitefaq->createRow();
      $sitefaq->setFromArray($values);

			if(empty($_POST['title']) && isset($_POST[$default_title_link]) && !empty($_POST[$default_title_link]) && $default_title_link != 'title') {
				$sitefaq->title = $_POST[$default_title_link];
			}

			if(empty($_POST['body']) && isset($_POST[$default_body_link]) && !empty($_POST[$default_body_link]) && $default_body_link != 'body') {
				$sitefaq->body = $_POST[$default_body_link];
			}

			//ENCODE CATEGORIES
			$sitefaq->category_id = Zend_Json_Encoder::encode($category_id);
			$sitefaq->subcategory_id = Zend_Json_Encoder::encode($subcategory_id);
			$sitefaq->subsubcategory_id = Zend_Json_Encoder::encode($subsubcategory_id);

			//GET MEMBER LEVEL SETTINGS
			if(!isset($_POST['member_levels'])) {
				$sitefaq->member_levels = '["0"]';
			}
			else {
				$sitefaq->member_levels = Zend_Json_Encoder::encode($_POST['member_levels']);
				if(strstr($sitefaq->member_levels, '"0"')) {
					$sitefaq->member_levels = '["0"]';
				}
			}

			//GET PROFILE TYPES SETTINGS
			if(!isset($_POST['profile_types'])) {
				$sitefaq->profile_types = '["0"]';
			}
			else {
				$sitefaq->profile_types = Zend_Json_Encoder::encode($_POST['profile_types']);
				if(strstr($sitefaq->profile_types, '"0"')) {
					$sitefaq->profile_types = '["0"]';
				}
			}

			//GET NETWORK SETTINGS
			if(!isset($_POST['networks'])) {
				$sitefaq->networks = '["0"]';
			}
			else {
				$sitefaq->networks = Zend_Json_Encoder::encode($_POST['networks']);
				if(strstr($sitefaq->networks, '"0"')) {
					$sitefaq->networks = '["0"]';
				}
			}

			//APPROVAL PRIVACY FOR FAQ
			$sitefaq->approved = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'approved');

			//SAVE AND COMMIT
      $sitefaq->save();
      $db->commit();

			//FAQ PRIVACY WORK
			$auth = Engine_Api::_()->authorization()->context;
			$roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
			$auth_comment = "registered";
			$commentMax = array_search($auth_comment, $roles);
			$auth_view = "everyone";
			$viewMax = array_search($auth_view, $roles);

			foreach ($roles as $i => $role) {
				$auth->setAllowed($sitefaq, $role, 'comment', ($i <= $commentMax));
				$auth->setAllowed($sitefaq, $role, 'view', ($i <= $viewMax));
			}

			//ADDING TAGS
			$keywords = '';
			if (isset($values['tags']) && !empty($values['tags'])) {
				$tags = preg_split('/[,]+/', $values['tags']);
				$tags = array_filter(array_map("trim", $tags));
				$sitefaq->tags()->addTagMaps($viewer, $tags);

				foreach($tags as $tag) {
					$keywords .= " $tag";
				}
			}

			//CUSTOM FIELD WORK
			$customfieldform = $form->getSubForm('fields');
			$customfieldform->setItem($sitefaq);
			$customfieldform->saveValues();
			//END CUSTOM FIELD WORK

			//NOT SEARCHABLE IF SAVED IN DRAFT MODE
			if(!empty($sitefaq->draft)) {
				$sitefaq->search = 0;
				$sitefaq->save();
			}

      if(!empty($sitefaq->approved) && empty($sitefaq->draft) && !empty($sitefaq->search)) {
        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $sitefaq, 'sitefaq_new');

        if( $action ) {
          Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sitefaq);
        }
      }

			//UPDATE KEYWORDS IN SEARCH TABLE
			if(!empty($keywords)) {
				Engine_Api::_()->getDbTable('search', 'core')->update(array('keywords' => $keywords), array('type = ?' => 'sitefaq_faq', 'id = ?' => $sitefaq->faq_id));
			}

			//START PAGE INTEGRATION WORK
			$page_id = $this->_getParam('page_id');
			if (!empty($page_id)) {
				$viewer = Engine_Api::_()->user()->getViewer();
				$viewer_id = $viewer->getIdentity();
				$moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepageintegration');
				if (!empty($moduleEnabled)) {
					$contentsTable = Engine_Api::_()->getDbtable('contents', 'sitepageintegration');
					$row = $contentsTable->createRow();
					$row->owner_id = $viewer_id;
					$row->resource_owner_id = $sitefaq->owner_id;
					$row->page_id = $page_id;
					$row->resource_type = 'sitefaq_faq';
					$row->resource_id = $sitefaq->faq_id;
					$row->save();
				}
			}
			//END PAGE INTEGRATION WORK
		  //START BUSINESS INTEGRATION WORK
			$business_id = $this->_getParam('business_id');
			if (!empty($business_id)) {
				$viewer = Engine_Api::_()->user()->getViewer();
				$viewer_id = $viewer->getIdentity();
				$moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessintegration');
				if (!empty($moduleEnabled)) {
					$contentsTable = Engine_Api::_()->getDbtable('contents', 'sitebusinessintegration');
					$row = $contentsTable->createRow();
					$row->owner_id = $viewer_id;
					$row->resource_owner_id = $sitefaq->owner_id;
					$row->business_id = $business_id;
					$row->resource_type = 'sitefaq_faq';
					$row->resource_id = $sitefaq->faq_id;
					$row->save();
				}
			}
			//END BUSINESS INTEGRATION WORK
		  //START GROUP INTEGRATION WORK
			$group_id = $this->_getParam('group_id');
			if (!empty($group_id)) {
				$viewer = Engine_Api::_()->user()->getViewer();
				$viewer_id = $viewer->getIdentity();
				$moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupintegration');
				if (!empty($moduleEnabled)) {
					$contentsTable = Engine_Api::_()->getDbtable('contents', 'sitegroupintegration');
					$row = $contentsTable->createRow();
					$row->owner_id = $viewer_id;
					$row->resource_owner_id = $sitefaq->owner_id;
					$row->group_id = $group_id;
					$row->resource_type = 'sitefaq_faq';
					$row->resource_id = $sitefaq->faq_id;
					$row->save();
				}
			}
			//END GROUP INTEGRATION WORK

			//REDIRECT
			$this->_redirectCustom(array('route' => 'sitefaq_general', 'action' => 'manage'));
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
  }

  public function editAction()
  {
		//LOGGED-IN USER CAN EDIT FAQ
    if( !$this->_helper->requireUser->isValid() )
        return;

		//SET FAQ SUBJECT
    if( 0 !== ($faq_id = (int) $this->_getParam('faq_id')) &&
        null !== ($sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id)) ) {
      Engine_Api::_()->core()->setSubject($sitefaq);
    }

		//REQUIRE SUBJECT CHECK
    $this->_helper->requireSubject('sitefaq_faq');

		//EDIT PRIVACY CHECK
		if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() ) {
      return;
    }

		//GET NAVIGATION
		$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitefaq_main', array(), 'sitefaq_main_manage');

		//GET CORE SETTING API
		$settings = Engine_Api::_()->getApi('settings', 'core');

		//GET VIEWER ID
    $viewer = Engine_Api::_()->user()->getViewer();

		//GET SUBJECT
    $this->view->sitefaq = $sitefaq = Engine_Api::_()->core()->getSubject();

		//GET NUMBER OF CATEGORIES ALREADY CREATED
		$categoryIds = Zend_Json_Decoder::decode($sitefaq->category_id);
		$this->view->created_categories = Count($categoryIds);

		//FORM GENERATION
    $this->view->form = $form = new Sitefaq_Form_Edit(array('item' => $sitefaq));

		//REMOVE DRAFT ELEMENT IF ALREADY PUBLISHED
    if ($sitefaq->draft == "0") {
      $form->removeElement('draft');
    }

		//HOW MANY MULTIPLE CATEGORY IS ALLOWED
    $this->view->maxCategories = $settings->getSetting('sitefaq.categories', 2);

    $this->view->menuStr = $menuStr = $settings->getSetting('faq.menus.str', 0);
    $this->view->faqLinkView = $faqLinkView = $settings->getSetting('sitefaq.getlink.view', 0);

		//MULTI LANGUAGE IS ALLOWED OR NOT
		$this->view->multiLanguage = $settings->getSetting('sitefaq.multilanguage', 0);

		//DEFAULT LANGUAGE
		$this->view->defaultLanguage = $defaultLanguage = $settings->getSetting('core.locale.locale', 'en');

		$this->view->tagLimit = $tagLimit = $settings->getSetting('faq.tag.limit', 0);

		//MULTI LANGUAGE WORK
		$this->view->languageCount = 0;
		$this->view->languageData = array();

		$faqOrder = $this->GetFaqOrder($menuStr, $faqLinkView);

		$default_body_link = $this->view->add_show_hide_link = 'body';
		$default_title_link = 'title';
		if($this->view->multiLanguage) {
			//GET LANGUAGE ARRAY
			//$localeMultiOptions = Engine_Api::_()->sitefaq()->getLanguageArray();
			$languages = $settings->getSetting('sitefaq.languages');
			$this->view->languageCount = Count($languages);
			$this->view->languageData = array();
			foreach($languages as $label) {
				$this->view->languageData[] = $label;

				if($this->view->languageCount >= 2  && $defaultLanguage == $label && $label != 'en') {
					$default_body_link = $this->view->add_show_hide_link = "body_$label";
					$default_title_link = "title_$label";
				}
			}
		}

		if(!in_array($defaultLanguage, $this->view->languageData)) {
			$this->view->defaultLanguage = 'en';
		}

		if( !empty($faqOrder) && !empty($tagLimit) && ( $faqOrder != $tagLimit ) ) {
		  $settings->setSetting('sitefaq.category', 0);
		  $settings->setSetting('faq.item.type', 0);
		}

		//GET TOTAL CATEGORIES
    $categories = Engine_Api::_()->getDbTable('categories', 'sitefaq')->getCategories(null);
		$this->view->category_exist = 0;
    if (count($categories) != 0) {
			$this->view->category_exist = 1;
		}

		//COUNT NUMBER OF CREATED CATEGORIES 
    $this->view->alreadyCreated = substr_count($sitefaq->category_id, ',');
    $this->view->alreadyCreated = $this->view->alreadyCreated + 1;

    if( !$this->getRequest()->isPost() ) {

			if($settings->getSetting('sitefaq.tag', 1)){
				//PREPARE TAGS
				$sitefaqTags = $sitefaq->tags()->getTagMaps();
				$tagString = '';
				foreach ($sitefaqTags as $tagmap) {
					if ($tagString !== '') {
						$tagString .= ', ';
					}
					$tagString .= $tagmap->getTag()->getTitle();
				}
				$this->view->tagNamePrepared = $tagString;
				$form->tags->setValue($tagString);
			}

      $form->populate($sitefaq->toArray());

			//SHOW PREFIELD LEVELS
			if ($sitefaq->member_levels) {
				if ($member_levels = $form->getElement('member_levels')) {
					$member_levels->setValue(Zend_Json_Decoder::decode($sitefaq->member_levels));
				}
			}

			//SHOW PREFIELD PROFILE TYPE
			if ($sitefaq->profile_types) {
				if ($profile_types = $form->getElement('profile_types')) {
					$profile_types->setValue(Zend_Json_Decoder::decode($sitefaq->profile_types));
				}
			}

			//SHOW PREFIELD PROFILE TYPE
			if ($sitefaq->networks) {
				if ($networks = $form->getElement('networks')) {
					$networks->setValue(Zend_Json_Decoder::decode($sitefaq->networks));
				}
			}
      return;
    }

		//GET CATEGORIES ARRAY
		foreach($_POST as $key => $value) {
			$sub = strstr($key, 'sub');
			$subsub = strstr($key, 'subsub');
			$category = strstr($key, 'category_id_');

			if(empty($sub) && !empty($category) && !empty($value)) {

				$explode_array = explode('category_id_', $key);
				$key = $explode_array[1];

				//CATEGORY ID ARRAY
				$category_id[] = "$value";

				if(isset($_POST["category_id_$key"]) && (!isset($_POST["subcategory_id_$key"]) || (isset($_POST["subcategory_id_$key"]) && empty($_POST["subcategory_id_$key"])))) {
					$subcategory_id[] = $subsubcategory_id[] = "0";
				}

			}
			elseif(!empty($sub) && empty($subsub) && !empty($category) && !empty($value)) {

				$explode_array = explode('subcategory_id_', $key);
				$key = $explode_array[1];

				//SUB-CATEGORY ID ARRAY
				$subcategory_id[] = "$value";

				if(isset($_POST["subcategory_id_$key"]) && (!isset($_POST["subsubcategory_id_$key"]) || (isset($_POST["subsubcategory_id_$key"]) && empty($_POST["subsubcategory_id_$key"])))) {
					$subsubcategory_id[] = "0";
				}
			}
			elseif(!empty($subsub) && !empty($category) && !empty($value)) {
				//3RD-LAVEL-CATEGORY ID ARRAY
				$subsubcategory_id[] = "$value";
			}
		}

		$get_category_count = Count($category_id);
		if($get_category_count != 0) {
			$this->view->alreadyCreated = $get_category_count;
		}

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

		//CATEGORY IS REQUIRED FIELD
		$required_category = 0;
		
		foreach($category_id as $value) {
			if(!empty($value)) {
				$required_category = 1;
				break;
			}
		}

		if (empty($required_category) || empty($category_id)) {
			$error = $this->view->translate('Category <BR /> Please complete this field - it is required.');
			//$error = Zend_Registry::get('Zend_Translate')->_($error);
			$form->getDecorator('errors')->setOption('escape', false);
			$form->addError($error);
			return;
		}

		//WORK FOR DUPLION
		for($i = 0; $i < Count($category_id); $i++) {
			$duplicate_value = $category_id[$i].'_'.$subcategory_id[$i].'_'.$subsubcategory_id[$i];
			$duplicate_array[$i] = "'$duplicate_value'";
		}

		$duplicate_array = array_count_values($duplicate_array);

 		//WORK FOR DUPLICATION CHECK OF CATEGORY COMBINATION
		foreach($duplicate_array as $value) {
			if($value > 1) {
				$error = $this->view->translate("Category <BR /> Please choose different combinations for categories - this field can not take same values.");
				//$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
			}
		}

		//VISIBILITY COMBINATION CHECKING
		if(((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && in_array(0, $_POST['profile_types'])) && ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['member_levels']) && !empty($_POST['member_levels']) && in_array(0, $_POST['member_levels'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['networks']) && !empty($_POST['networks']) && !in_array(0, $_POST['networks'])))) || ((isset($_POST['networks']) && !empty($_POST['networks']) && in_array(0, $_POST['networks'])) && ((isset($_POST['profile_types']) && !empty($_POST['profile_types']) && !in_array(0, $_POST['profile_types'])) || (isset($_POST['member_levels']) && !empty($_POST['member_levels']) && !in_array(0, $_POST['member_levels']))))) {
				$error = $this->view->translate("If you are selecting 'Everyone' option in any of the available privacy fields, then you must also choose 'Everyone' in other privacy options.");
				//$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
		}

    //TRANSCATION
    $db = Engine_Api::_()->getItemTable('sitefaq_faq')->getAdapter();
    $db->beginTransaction();

    try {
      $values = $form->getValues();
      $sitefaq->setFromArray($values);

			if(empty($_POST['title']) && isset($_POST[$default_title_link]) && !empty($_POST[$default_title_link]) && $default_title_link != 'title') {
				$sitefaq->title = $_POST[$default_title_link];
			}

			if(empty($_POST['body']) && isset($_POST[$default_body_link]) && !empty($_POST[$default_body_link]) && $default_body_link != 'body') {
				$sitefaq->body = $_POST[$default_body_link];
			}

			//ENCODE CATEGORIES
			$sitefaq->category_id = Zend_Json_Encoder::encode($category_id);
			$sitefaq->subcategory_id = Zend_Json_Encoder::encode($subcategory_id);
			$sitefaq->subsubcategory_id = Zend_Json_Encoder::encode($subsubcategory_id);

			//GET MEMBER LEVEL SETTINGS
			if(!isset($_POST['member_levels'])) {
				$sitefaq->member_levels = '["0"]';
			}
			else {
				$sitefaq->member_levels = Zend_Json_Encoder::encode($_POST['member_levels']);
				if(strstr($sitefaq->member_levels, '"0"')) {
					$sitefaq->member_levels = '["0"]';
				}
			}

			//GET PROFILE TYPES SETTINGS
			if(!isset($_POST['profile_types'])) {
				$sitefaq->profile_types = '["0"]';
			}
			else {
				$sitefaq->profile_types = Zend_Json_Encoder::encode($_POST['profile_types']);
				if(strstr($sitefaq->profile_types, '"0"')) {
					$sitefaq->profile_types = '["0"]';
				}
			}

			//GET NETWORK SETTINGS
			if(!isset($_POST['networks'])) {
				$sitefaq->networks = '["0"]';
			}
			else {
				$sitefaq->networks = Zend_Json_Encoder::encode($_POST['networks']);
				if(strstr($sitefaq->networks, '"0"')) {
					$sitefaq->networks = '["0"]';
				}
			}

      //SAVE AND COMMIT
      $sitefaq->save();

			//FAQ PRIVACY WORK
			$auth = Engine_Api::_()->authorization()->context;
			$roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
			$auth_comment = "registered";
			$commentMax = array_search($auth_comment, $roles);
			$auth_view = "everyone";
			$viewMax = array_search($auth_view, $roles);

			foreach ($roles as $i => $role) {
				$auth->setAllowed($sitefaq, $role, 'comment', ($i <= $commentMax));
				$auth->setAllowed($sitefaq, $role, 'view', ($i <= $viewMax));
			}

			//ADDING TAGS
			if (isset($values['tags'])) {
				$tags = preg_split('/[,]+/', $values['tags']);
				$tags = array_filter(array_map("trim", $tags));
				$sitefaq->tags()->setTagMaps($viewer, $tags);
			}

			//CUSTOM FIELD WORK
			$customfieldform = $form->getSubForm('fields');
			$customfieldform->setItem($sitefaq);
			$customfieldform->saveValues();
			//END CUSTOM FIELD WORK

			//NOT SEARCHABLE IF SAVED IN DRAFT MODE
			if(!empty($sitefaq->draft)) {
				$sitefaq->search = 0;
				$sitefaq->save();
			}

			//ADD ACTIVITY ONLY IF SITEFAQ IS PUBLISHED AND APPROVED
			if (isset($values['draft']) && $sitefaq->draft == 0 && $sitefaq->approved == 1 && !empty($sitefaq->search)) {

				//GET SITEFAQ OWNER OBJECT
				$creator = Engine_Api::_()->getItem('user', $sitefaq->owner_id);

        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($creator, $sitefaq, 'sitefaq_new');

        if( $action ) {
          Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sitefaq);
        }
			}

      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

		//REDIRECT
		$this->_redirectCustom(array('route' => 'sitefaq_general', 'action' => 'manage'));
  }

	//ACTION FOR MANAGE FAQs
  public function manageAction()
  {
		//REQUIRE USER CHECK
    if( !$this->_helper->requireUser->isValid() )
        return;

		//CREATION PRIVACY CHECK
    if( !$this->_helper->requireAuth()->setAuthParams('sitefaq_faq', null, 'create')->isValid() )
        return;

		//RENDER PAGE
		$this->_helper->content
         ->setNoRender()
         ->setEnabled();
  }

	//ACTION FOR MOBI-MANAGE FAQs
  public function mobiManageAction()
  {
		//REQUIRE USER CHECK
    if( !$this->_helper->requireUser->isValid() )
        return;

		//CREATION PRIVACY CHECK
    if( !$this->_helper->requireAuth()->setAuthParams('sitefaq_faq', null, 'create')->isValid() )
        return;

		//RENDER PAGE
		$this->_helper->content
         ->setNoRender()
         ->setEnabled();
  }

	//ACTION FOR VIEW FAQ
	public function viewAction() {

		//IF ANONYMOUS USER THEN SEND HIM TO SIGN IN PAGE
		$check_anonymous_help = $this->_getParam('anonymous');
		if($check_anonymous_help) {
			if( !$this->_helper->requireUser()->isValid() ) return;
		}

		//RENDER PAGE
		$this->_helper->content
         ->setNoRender()
         ->setEnabled();

		//SET FAQ SUBJECT
    if( 0 !== ($faq_id = (int) $this->_getParam('faq_id')) &&
        null !== ($sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id)) ) {
      Engine_Api::_()->core()->setSubject($sitefaq);
    }

    //IF LISTING IS NOT EXIST
    if (empty($sitefaq)) {
      return $this->_forwardCustom('notfound', 'error', 'core');
    }    

		//REQUIRE SUBJECT CHECK
    $this->_helper->requireSubject('sitefaq_faq');

		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//RATING PRIVACY
		$can_view = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'view');

		if($can_view != 2 && $viewer_id != $sitefaq->owner_id && $level_id != 1) {

			//GET USER PROFILES
			$viewerLevelsArray = Engine_Api::_()->sitefaq()->getViewerLevels();
			$levels = Zend_Json_Decoder::decode($sitefaq->member_levels);
			$levels_common_values = array_intersect($levels, $viewerLevelsArray);

			//GET USER PROFILES
			$viewerProfilesArray = Engine_Api::_()->sitefaq()->getViewerProfiles();
			$profiles = Zend_Json_Decoder::decode($sitefaq->profile_types);
			$profiles_common_values = array_intersect($profiles, $viewerProfilesArray);

			//GET USER NETWORKS
			$viewerNetworksArray = Engine_Api::_()->sitefaq()->getViewerNetworks();
			$networks = Zend_Json_Decoder::decode($sitefaq->networks);
			$networks_common_values = array_intersect($networks, $viewerNetworksArray);

			if(empty($networks_common_values) || empty($profiles_common_values) || empty($levels_common_values) || !empty($sitefaq->draft) || empty($sitefaq->approved) || empty($sitefaq->search)) {
				return $this->_forward('requireauth', 'error', 'core');
			}
		}

		//INCREMENT FAQ VIEWS IF VIEWER IS NOT OWNER
		if (!$sitefaq->getOwner()->isSelf($viewer)) {
			$sitefaq->view_count++;
			$sitefaq->save();
		}
                
    //NAVIGATION WORK FOR FOOTER.(DO NOT DISPLAY NAVIGATION IN FOOTER ON VIEW PAGE.)
    if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
         if(!Zend_Registry::isRegistered('sitemobileNavigationName')){
         Zend_Registry::set('sitemobileNavigationName','setNoRender');
         }
    }
}

	//ACTION FOR VIEW FAQ
	public function mobiViewAction() {

		//RENDER PAGE
		$this->_helper->content
         ->setNoRender()
         ->setEnabled();

		//SET FAQ SUBJECT
    if( 0 !== ($faq_id = (int) $this->_getParam('faq_id')) &&
        null !== ($sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id)) ) {
      Engine_Api::_()->core()->setSubject($sitefaq);
    }

		//REQUIRE SUBJECT CHECK
    $this->_helper->requireSubject('sitefaq_faq');

		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//RATING PRIVACY
		$can_view = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'view');


		if($can_view != 2 && $viewer_id != $sitefaq->owner_id && $level_id != 1) {

			//GET USER PROFILES
			$viewerLevelsArray = Engine_Api::_()->sitefaq()->getViewerLevels();
			$levels = Zend_Json_Decoder::decode($sitefaq->member_levels);
			$levels_common_values = array_intersect($levels, $viewerLevelsArray);

			//GET USER PROFILES
			$viewerProfilesArray = Engine_Api::_()->sitefaq()->getViewerProfiles();
			$profiles = Zend_Json_Decoder::decode($sitefaq->profile_types);
			$profiles_common_values = array_intersect($profiles, $viewerProfilesArray);

			//GET USER NETWORKS
			$viewerNetworksArray = Engine_Api::_()->sitefaq()->getViewerNetworks();
			$networks = Zend_Json_Decoder::decode($sitefaq->networks);
			$networks_common_values = array_intersect($networks, $viewerNetworksArray);

			if(empty($networks_common_values) || empty($profiles_common_values) || empty($levels_common_values) || !empty($sitefaq->draft) || empty($sitefaq->approved) || empty($sitefaq->search)) {
				return $this->_forward('requireauth', 'error', 'core');
			}
		}

		//INCREMENT FAQ VIEWS IF VIEWER IS NOT OWNER
		if (!$sitefaq->getOwner()->isSelf($viewer)) {
			$sitefaq->view_count++;
			$sitefaq->save();
		}
	}

  //ACTION FOR RATING FAQs
  public function ratingAction() {

		//GET VIEWER ID
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

		//GET RATING
    $rating = $this->_getParam('rating');

		//GET FAQs ID
    $faq_id = $this->_getParam('faq_id');

		//GET RATING TABLE
    $tableRating = Engine_Api::_()->getDbtable('ratings', 'sitefaq');

		//BEGIN TRANSCATION
    $db = $tableRating->getAdapter();
    $db->beginTransaction();

    try {
      $tableRating->setFaqRating($faq_id, $viewer_id, $rating);

      $total = $tableRating->countRating($faq_id);

      $sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id);

			//UPDATE CURRENT AVERAGE RATING IN FAQs TABLE
			$sitefaq->rating = $rating = $tableRating->getAvgRating($faq_id);

			//SAVE AND COMMIT
      $sitefaq->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $data = array();
    $data[] = array(
        'total' => $total,
        'rating' => $rating,
    );
    return $this->_helper->json($data);
    $data = Zend_Json::encode($data);
    $this->getResponse()->setBody($data);
  }

  //ACTION FOR SITEFAQ PUBLISH
  public function publishAction() {

    //CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid())
      return;

    //LAYOUT
    if (null === $this->_helper->ajaxContext->getCurrentContext()) {
      $this->_helper->layout->setLayout('default-simple');
    } else {
      $this->_helper->layout->disableLayout(true);
    }

		//GET SITEFAQ ID AND OBJECT
    $sitefaq_id = $this->view->sitefaq_id = $this->getRequest()->getParam('faq_id');
		$sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $sitefaq_id);

		//SEARCHABLE OR NOT
		$this->view->show_search = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.search', 1);

    if (!$this->getRequest()->isPost())
      return;

		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();

		//WHO CAN EDIT THE SITEFAQ
    $can_edit = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitefaq_faq', 'edit');

    if (!empty($can_edit)) { 

			if (!empty($_POST['search']) || empty($this->view->show_search)) {
				$sitefaq->search = 1;
			}
			else {
				$sitefaq->search = 0;
			}

			$sitefaq->modified_date = new Zend_Db_Expr('NOW()');
			$sitefaq->draft = 0;
			
			$sitefaq->save();

			$this->view->success = true;

			//ADD ACTIVITY ONLY IF SITEFAQ IS PUBLISHED AND APPROVED
			if ($sitefaq->draft == 0 && $sitefaq->approved == 1 && $sitefaq->search == 1) {

				//GET SITEFAQ OWNER OBJECT
				$creator = Engine_Api::_()->getItem('user', $sitefaq->owner_id);

        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($creator, $sitefaq, 'sitefaq_new');

        if( $action ) {
          Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sitefaq);
        }
			}
    }

    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('Published successfully !')
    ));
  }

	//ACTION FOR ASK QUESTION
  public function questionAction()
  { 
		//CREATION PRIVACY CHECK
    if( !$this->_helper->requireAuth()->setAuthParams('sitefaq_faq', null, 'question')->isValid() )
        return;

    //LAYOUT
    if (null === $this->_helper->ajaxContext->getCurrentContext()) {
      $this->_helper->layout->setLayout('default-simple');
    } else {
      $this->_helper->layout->disableLayout(true);
    }

    //CREATE FORM
    $this->view->form = $form = new Sitefaq_Form_Question();

    //CHECK METHOD/DATA VALIDITY
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    //PROCESS
    $values = $form->getValues();

    $db = Engine_Api::_()->getDbtable('faqs', 'sitefaq')->getAdapter();
    $db->beginTransaction();

    try {

      //CREATE FAQ
      $tableSitefaq = Engine_Api::_()->getDbtable('questions', 'sitefaq');
      $sitefaq = $tableSitefaq->createRow();

      $sitefaq->setFromArray($values);
	
      $sitefaq->save();

			//EMAIL NOTIFICATION TO ADMIN
			$sitefaq_email = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.email', 1);
			if(!empty($sitefaq_email)) {
				$email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
				Engine_Api::_()->getApi('mail', 'core')->sendSystem($email, 'SITEFAQ_QUESTION_NOTIFICATION_EMAIL', array(
						'site_title' => Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 'Advertisement'),
						'question' => $sitefaq->title,
						'email' => $email,
						'queue' => true
				));
			}

      //COMMIT
      $db->commit();

			$this->_forward('success', 'utility', 'core', array(
					'smoothboxClose' => 400,
					//'parentRefresh' => 10,
					'messages' => array('Your question has been submitted.')
			));
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
  }

	//ACTION FOR FAQ SUGGESTION DROP-DOWN
  public function getItemAction() {

		//GET SEARCHABLE TEXT AND CONTENT LIMIT
		$params = array();
		$params['search_text'] = $search_text = $this->_getParam('text', null);
		$params['limit'] = $this->_getParam('limit', 9);
		$privacy = $this->_getParam('privacy');
		if($privacy) {
			//GET VIEWER
			$viewer = Engine_Api::_()->user()->getViewer();
			$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

			//GET USER LEVEL ID
			if (!empty($viewer_id)) {
				$level_id = $viewer->level_id;
			} else {
				$level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
			}

			$values = array();
			if($level_id != 1) {
				$sitefaq_api = Engine_Api::_()->sitefaq();
				$params['networks'] = $sitefaq_api->getViewerNetworks();
				$params['profile_types'] = $sitefaq_api->getViewerProfiles();
				$params['member_levels'] = $sitefaq_api->getViewerLevels();
			}
		}

		//GET CONTENT
		$data = array();
		$moduleContents = Engine_Api::_()->getItemTable('sitefaq_faq')->getSuggestList($params);
    foreach ($moduleContents as $moduleContent) {
      $data[] = array(
              'id' => $moduleContent->faq_id,
							'label' => $moduleContent->getTitle(),
              'url' => $this->view->htmlLink($moduleContent->getHref(), $moduleContent->getTitle())
      );
    }

		if(Count($data) >= 9) {

			//BROWSE PAGE URL AND LABEL
			$url = $this->view->url(array('action' => 'browse'), 'sitefaq_general', true);
			$url = $url."?search=$search_text";
			$label = Zend_Registry::get('Zend_Translate')->_("See all results for ").$search_text;

      $data[] = array(
              'id' => 'faq_see_all',
							'label' => $label,
              'url' =>  $this->view->htmlLink($url, $label)
      );
		}

    return $this->_helper->json($data);
  }

  //ACTION FOR MARKING HELPFUL FAQs
  public function helpfulAction() {

		//GET VIEWER ID
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

		if( !$this->_helper->requireUser()->isValid() ) return;
		
		//GET RATING
    $helpful = $this->_getParam('helpful');

    //GET OPTIONID
    $option_id = $this->_getParam('option_id',0);
 
    //SHOW THE OPTIONS
    $this->view->options = Engine_Api::_()->getDbtable('options', 'sitefaq')->markSitefaqOption();

		//GET RATING
    $this->view->statisticsHelpful = $this->_getParam('statisticsHelpful', 1);

		//GET FAQ ID
    $faq_id = $this->_getParam('faq_id');

		//MAKE ENTRY FOR HELPFUL

    Engine_Api::_()->getDbtable('helps', 'sitefaq')->setHelful($faq_id, $viewer_id, $helpful,$option_id);

		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//SEND FAQ ID TO TPL
		$this->view->faq_id = $faq_id;

		//HELPFUL PRIVACY
		$this->view->helpful_allow = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'helpful');

		//GET HELPFUL TABLE
		$tableHelp = Engine_Api::_()->getDbTable('helps', 'sitefaq');

		//CHECK FOR PREVIOUS MARK
		if(!empty($this->view->helpful_allow)) {
			$this->view->previousHelpMark = $tableHelp->getHelpful($faq_id, $viewer_id);
		}

		//TOTAL HELPFUL COUNT
		$this->view->totalHelpCount = $tableHelp->countHelpful($faq_id, 1);
  }

	//ACTION TO GET SUB-CATEGORY
  public function subCategoryAction() {

		//GET CATEGORY ID
    $category_id_temp = $this->_getParam('category_id_temp');

		//INTIALIZE ARRAY
		$this->view->subcats = $data = array();

		//RETURN IF CATEGORY ID IS EMPTY
    if (empty($category_id_temp))
      return;

		//GET CATEGORY TABLE
		$tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');

		//GET CATEGORY
    $category = $tableCategory->getCategory($category_id_temp);
    if (!empty($category->category_name)) {
      $categoryName = $tableCategory->getCategorySlug($category->category_name);
    }

		//GET SUB-CATEGORY
    $subCategories = $tableCategory->getSubCategories($category_id_temp);
  
    foreach ($subCategories as $subCategory) {
      $content_array = array();
      $content_array['category_name'] = Zend_Registry::get('Zend_Translate')->_($subCategory->category_name);
      $content_array['category_id'] = $subCategory->category_id;
      $content_array['categoryname_temp'] = $categoryName;
      $data[] = $content_array;
    }
 
    $this->view->subcats = $data;
  }

  //ACTION FOR FETCHING SUB-CATEGORY
  public function subsubCategoryAction() {

		//GET SUB-CATEGORY ID
    $subcategory_id_temp = $this->_getParam('subcategory_id_temp');

		//INTIALIZE ARRAY
		$this->view->subsubcats = $data = array();

		//RETURN IF SUB-CATEGORY ID IS EMPTY
    if(empty($subcategory_id_temp))
      return;
    
		//GET CATEGORY TABLE
		$tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');

		//GET SUB-CATEGORY
    $subCategory = $tableCategory->getCategory($subcategory_id_temp);
    if (!empty($subCategory->category_name)) {
      $subCategoryName = $tableCategory->getCategorySlug($subCategory->category_name);
    }

		//GET 3RD LEVEL CATEGORIES
    $subCategories = $tableCategory->getSubCategories($subcategory_id_temp);
    foreach ($subCategories as $subCategory) {
      $content_array = array();
      $content_array['category_name'] = Zend_Registry::get('Zend_Translate')->_($subCategory->category_name);
			$content_array['category_id'] = $subCategory->category_id;
      $content_array['categoryname_temp'] = $subCategoryName;
      $data[] = $content_array;
    }
    $this->view->subsubcats = $data;
  }

  //ACTION FOR PRINT FAQs
  public function printAction() {

		//SET LAYOUT
		$this->_helper->layout->setLayout('default-simple');

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

		//GET USER LEVEL ID
		if (!empty($viewer_id)) {
			$this->view->level_id = $level_id = $viewer->level_id;
		} else {
			$this->view->level_id = $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
		}

		//CHECK FAQ VIEW PRIVACY
    $can_view = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'view');

		//DON'T RENDER IF NOT VIEWALBE
		if(empty($can_view)) {
      return $this->_forward('requireauth', 'error', 'core');
		}

		//GET VALUES
		$values = $_GET;

		//GET VALUES FROM URL
		$this->view->truncation = $values['truncation'];
		$this->view->statisticsRating = $values['statisticsRating'];
		$this->view->statisticsHelpful = $values['statisticsHelpful'];
		$this->view->statisticsComment = $values['statisticsComment'];
		$this->view->statisticsView = $values['statisticsView'];

		//GET FORM
		$form = new Sitefaq_Form_Search();

		//GET CUSTOM VALUES
		$customFieldValues = array_intersect_key($values, $form->getFieldElements());

		//FAQ AUTHORIZATION VALUES
    $values['approved'] = "1";
		$values['draft'] = "0";
		$values['searchable'] = "1";

		if($level_id != 1) {
			$sitefaq_api = Engine_Api::_()->sitefaq();
			$values['networks'] = $sitefaq_api->getViewerNetworks();
			$values['profile_types'] = $sitefaq_api->getViewerProfiles();
			$values['member_levels'] = $sitefaq_api->getViewerLevels();
		}

    //GET PAGINATOR
    $this->view->paginator = Engine_Api::_()->getDbtable('faqs', 'sitefaq')->getSitefaqsPaginator($values, $customFieldValues);
		$total_sitefaqs = $values['total_sitefaqs'];
		$this->view->paginator->setItemCountPerPage($total_sitefaqs);
		$this->view->paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  //ACTION FOR PRINT FAQs
  public function printViewAction() {

		//SET LAYOUT
		$this->_helper->layout->setLayout('default-simple');

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

		//GET USER LEVEL ID
		if (!empty($viewer_id)) {
			$this->view->level_id = $level_id = $viewer->level_id;
		} else {
			$this->view->level_id = $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
		}

		//CHECK FAQ VIEW PRIVACY
    $can_view = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'view');

		//DON'T RENDER IF NOT VIEWALBE
		if(empty($can_view)) {
      return $this->_forward('requireauth', 'error', 'core');
		}

		//GET FAQ ID AND OBJECT
    $faq_id = (int) $this->_getParam('faq_id');
    $this->view->sitefaq = $sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id);

		//SHOW RATING IF RATING WIDGET IS PLACED
		$this->view->statisticsRating = Engine_Api::_()->sitefaq()->existWidget('view_ratings');

		//RATING PRIVACY
		$this->view->can_rate = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'rating');

		//GET RATING TABLE
    $this->view->rating_count = Engine_Api::_()->getDbTable('ratings', 'sitefaq')->countRating($sitefaq->getIdentity());

		//CUSTOM FIELD WORK
    $this->view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');
    $this->view->fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($sitefaq);
		//END CUSTOM FIELD WORK
  }

  //ACTION FOR CONSTRUCT TAG CLOUD
  public function tagscloudAction() {

		//GET NAVIGATION
		$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitefaq_main', array(), '');
		
    //CONSTRUCTING TAG CLOUD
    $tag_array = array();
    $tag_cloud_array = Engine_Api::_()->sitefaq()->getTags(0, 0, 0);

    foreach ($tag_cloud_array as $vales) {
      $tag_array[$vales['text']] = $vales['Frequency'];
      $tag_id_array[$vales['text']] = $vales['tag_id'];
    }

    if (!empty($tag_array)) {
      $max_font_size = 18;
      $min_font_size = 12;
      $max_frequency = max(array_values($tag_array));
      $min_frequency = min(array_values($tag_array));
      $spread = $max_frequency - $min_frequency;
      if ($spread == 0) {
        $spread = 1;
      }
      $step = ($max_font_size - $min_font_size) / ($spread);

      $tag_data = array('min_font_size' => $min_font_size, 'max_font_size' => $max_font_size, 'max_frequency' => $max_frequency, 'min_frequency' => $min_frequency, 'step' => $step);
      $this->view->tag_data = $tag_data;
      $this->view->tag_id_array = $tag_id_array;
    }
    $this->view->tag_array = $tag_array;
  }

  //ACTION FOR DELETE THE FAQ
  public function deleteAction()
  {
    //LAYOUT
    if (null === $this->_helper->ajaxContext->getCurrentContext()) {
      $this->_helper->layout->setLayout('default-simple');
    } else {
      $this->_helper->layout->disableLayout(true);
    }

		//LOGGED-IN USER CAN EDIT FAQ
    if( !$this->_helper->requireUser->isValid() )
        return;

		//SET FAQ SUBJECT
    if( 0 !== ($faq_id = (int) $this->_getParam('faq_id')) &&
        null !== ($sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id)) ) {
      Engine_Api::_()->core()->setSubject($sitefaq);
    }

		//EDIT PRIVACY CHECK
		if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'delete')->isValid() ) {
      return;
    }

		//GET FAQ ID
		$this->view->faq_id = $faq_id = $this->_getParam('faq_id');

		//RETURN IF NOT POSTED
    if (!$this->getRequest()->isPost())
      return;

		//DELETE FAQ OBJECT
		Engine_Api::_()->getItem('sitefaq_faq', $faq_id)->delete();

		//URL OF MANAGE PAGE
		$url = $this->_helper->url->url(array('action' => 'manage'), 'sitefaq_general');

		$this->_forward('success', 'utility', 'core', array(
				'smoothboxClose' => true,
				'parentRedirect' => $url,
				'parentRedirectTime' => '15',
				'messages' => Zend_Registry::get('Zend_Translate')->_('Successfully deleted FAQ !')
		));
	}


  private function GetFaqOrder($str, $object) {
    if( empty($str) || empty($object) )
      return;

		$objOrder = $strOrder = 0;
    $getStr = '';
    $object = convert_uudecode($object);
    $object = substr($object, 0, 8);
    for( $limit = 0; $limit < strlen($object); $limit++ ) {
      $objOrder += ord($object[$limit]);
    }

    for( $strlim = 0; $strlim < strlen($str); $strlim++ ) {
      $strOrder += ord($str[$strlim]);
    }

    return ($objOrder + $strOrder);
  }

	//ACTION FOR UPLOADING IMAGES THROUGH WYSIWYG EDITOR
  public function uploadPhotoAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();

    $this->_helper->layout->disableLayout();

    if( !Engine_Api::_()->authorization()->isAllowed('album', $viewer, 'create') ) {
      return false;
    }

    if( !$this->_helper->requireAuth()->setAuthParams('album', null, 'create')->isValid() ) return;

    if( !$this->_helper->requireUser()->checkRequire() )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Max file size limit exceeded (probably).');
      return;
    }

    if( !$this->getRequest()->isPost() )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
     $fileName = Engine_Api::_()->seaocore()->tinymceEditorPhotoUploadedFileName();
    if( !isset($_FILES[$fileName]) || !is_uploaded_file($_FILES[$fileName]['tmp_name']) )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
      return;
    }

    $db = Engine_Api::_()->getDbtable('photos', 'album')->getAdapter();
    $db->beginTransaction();

    try
    {
      $viewer = Engine_Api::_()->user()->getViewer();

      $photoTable = Engine_Api::_()->getDbtable('photos', 'album');
      $photo = $photoTable->createRow();
      $photo->setFromArray(array(
        'owner_type' => 'user',
        'owner_id' => $viewer->getIdentity()
      ));
      $photo->save();

      $photo->setPhoto($_FILES[$fileName]);

      $this->view->status = true;
      $this->view->name = $_FILES[$fileName]['name'];
      $this->view->photo_id = $photo->photo_id;
      $this->view->photo_url = $photo->getPhotoUrl();

      $table = Engine_Api::_()->getDbtable('albums', 'album');
      $album = $table->getSpecialAlbum($viewer, 'message');

      $photo->album_id = $album->album_id;
      $photo->save();

      if( !$album->photo_id )
      {
        $album->photo_id = $photo->getIdentity();
        $album->save();
      }

      $auth      = Engine_Api::_()->authorization()->context;
      $auth->setAllowed($photo, 'everyone', 'view',    true);
      $auth->setAllowed($photo, 'everyone', 'comment', true);
      $auth->setAllowed($album, 'everyone', 'view',    true);
      $auth->setAllowed($album, 'everyone', 'comment', true);

      $db->commit();

    } catch( Album_Model_Exception $e ) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = $this->view->translate($e->getMessage());
      throw $e;
      return;

    } catch( Exception $e ) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      throw $e;
      return;
    }
  }

}
