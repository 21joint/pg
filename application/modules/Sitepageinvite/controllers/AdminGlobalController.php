<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminglobalController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageinvite_AdminGlobalController extends Core_Controller_Action_Admin {

    public function __call($method, $params) {
        /*
         * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
         * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
         * REMEMBER:
         *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
         *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
         */
        if (!empty($method) && $method == 'Sitepageinvite_Form_Admin_Global') {

        }
        return true;
    }
    
  public function globalAction() {
		if( $this->getRequest()->isPost() ) {
			$sitepageKeyVeri = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.lsettings', null);
			if( !empty($sitepageKeyVeri) ) {
				Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepage.lsettings', trim($sitepageKeyVeri));
			}
			if( $_POST['sitepageinvite_lsettings'] ) {
				$_POST['sitepageinvite_lsettings'] = trim($_POST['sitepageinvite_lsettings']);
			}
		}
		//START LANGUAGE WORK
		Engine_Api::_()->getApi('language', 'sitepage')->languageChanges();
		//END LANGUAGE WORK    
    include APPLICATION_PATH . '/application/modules/Sitepageinvite/controllers/license/license1.php';
  }

  //SHOWING THE PLUGIN RELETED QUESTIONS AND ANSWERS
  public function faqAction() {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitepageinvite_admin_main', array(), 'sitepageinvite_admin_main_faq');
  }

  public function readmeAction() {
    
  }

  public function appconfigsAction() {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitepageinvite_admin_main', array(), 'sitepageinvite_admin_main_global');
  }

}

?>