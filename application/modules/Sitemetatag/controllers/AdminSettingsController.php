<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitemetatag_AdminSettingsController extends Core_Controller_Action_Admin {

	public function indexAction() {

		include_once APPLICATION_PATH . '/application/modules/Sitemetatag/controllers/license/license1.php';

	}

    //SHOWING THE PLUGIN RELETED QUESTIONS AND ANSWERS
	public function faqAction() {
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitemetatag_admin_main', array(), 'sitemetatag_admin_main_faqs');
		$this->view->target = $this->_getParam('target', false);
	}

	//SHOWING THE PLUGIN RELETED QUESTIONS AND ANSWERS
	public function readmeAction() {
		
	}
}