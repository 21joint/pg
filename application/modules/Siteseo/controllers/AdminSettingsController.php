<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_AdminSettingsController extends Core_Controller_Action_Admin {

	public function indexAction() {
		include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license1.php';
		$this->view->isActivated = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteseo.isActivate', false);
		$this->view->hasSitemetatag = Engine_Api::_()->hasModuleBootstrap('sitemetatag');
		$this->view->isSitemetatagActivated = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitemetatag.isActivate', false);
	}

    //SETTINGS FOR SCHEMA MARKUP
	public function schemaAction() {
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_schema');
		$this->view->form = $form = new Siteseo_Form_Admin_Schema();
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			$values = $form->getValues();
			include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license2.php';
			$form->addNotice('Your changes have been saved successfully.');
		}
	}

	// READ ME ACTION USED BEFORE PLUGIN ACTIVATION
	public function readmeAction() {
	}
    //SHOWING THE PLUGIN RELETED QUESTIONS AND ANSWERS
	public function faqAction() {
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_faqs');
		$this->view->target = $this->_getParam('target', false);
	}        

    //SHOW SEO TIPS FOR WEBSITE ADMINS
	public function seoTipsAction() {
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_seotips');
		$this->view->target = $this->_getParam('target', false);
	}

    //SHOW SEO TOOLS FOR WEBSITE ADMINS
	public function seoToolsAction() {
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_seotools');
		$this->view->target = $this->_getParam('target', false);
	}

    //SHOW SUPPORT SECTION FOR WEBSITE ADMINS
	public function supportAction() {
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_support');
		$this->view->target = $this->_getParam('target', false);
	}

    //UPLOAD GOOGLE SEARCH CONSOLE KEY FOR SEARCH CONSOLE INTEGRATION
	public function uploadSearchConsoleKeyAction() {
		$this->view->form = $form = new Siteseo_Form_Admin_SearchConsoleKey();
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			$ext = str_replace(".", "", strrchr($_FILES['jsonkey']['name'], "."));
			if (!in_array($ext, array('json', 'JSON'))) {
				$error = "Invalid file extension. Key should be in 'json' format.";
				$error = Zend_Registry::get('Zend_Translate')->_($error);
				$form->getDecorator('errors')->setOption('escape', false);
				$form->addError($error);
				return;
			}

			$fileName = $_FILES['jsonkey']['name'];
			$tempFile = $_FILES['jsonkey']['tmp_name'];
			$basePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'searchconsole';
			if (!file_exists($basePath)) {
				@mkdir($basePath);
				@chmod($basePath, 0777);
			}
			$filePath = $basePath . DIRECTORY_SEPARATOR . $fileName;
			move_uploaded_file($tempFile, $filePath);
			@chmod($filePath, 0777);
			Engine_Api::_()->getApi('settings', 'core')->setSetting('siteseo.google.service.account.key', $filePath);
			$this->_forward('success', 'utility', 'core', array(
				'smoothboxClose' => true,
				'parentRedirect' => 'admin/siteseo/settings/support/target/4',
				'parentRedirectTime' => '15',
				'format' => 'smoothbox',
				'messages' => array('Your key have been uploaded !')
				));
		}
	}
}