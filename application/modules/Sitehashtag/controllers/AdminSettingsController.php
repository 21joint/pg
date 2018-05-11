<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    AdminSettingsController.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_AdminSettingsController extends Core_Controller_Action_Admin {

    public function __call($method, $params) {
        /*
         * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
         * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
         * REMEMBER:
         *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
         *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
         */

        if (!empty($method) && $method == 'Sitehashtag_Form_Admin_Settings_Global') {
            
        }
        return true;
    }
    
    public function indexAction() {       
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')		
              ->getNavigation('sitehashtag_admin_main', array(), 'sitehashtag_admin_main_settings');
         $this->view->form = $form = new Sitehashtag_Form_Admin_Settings_Global();		
         if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {		
             $values = $form->getValues();
                         foreach ($values as $key => $value) {		
                                Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
                         }   
             $form->addNotice('Your changes have been saved.');
           }
         
    }

    public function faqAction() {

        // GET NAVIGATION
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitehashtag_admin_main', array(), 'sitehashtag_admin_main_faq');
        $this->view->faq = 1;
    }
    
    public function readmeAction() {
        
    }

}
