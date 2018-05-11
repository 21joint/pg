<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    AdminSettingsController.php 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteiosapp_AdminSettingsController extends Core_Controller_Action_Admin {

    public function __call($method, $params) {
        /*
         * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
         * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
         * REMEMBER:
         *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
         *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
         */

        if (!empty($method) && $method == 'Siteiosapp_Form_Admin_Settings') {
            
        }
        return true;
    }

    public function indexAction() {        
       
        if (isset($_POST['siteiosapp_shared_secret'])) {           
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteiosapp.shared.secret', $_POST['siteiosapp_shared_secret']);
        }
        
        if (isset($_POST['browse_as_guest'])) {           
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteiosapp.browse.guest', $_POST['browse_as_guest']);
        }
        
        if (isset($_POST['siteiosapp_current_mode'])) {           
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteiosapp.current.mode', $_POST['siteiosapp_current_mode']);
        }
        
        if (isset($_POST['siteios_member_view'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('ios.member.view', $_POST['siteios_member_view']);
        }
        
         if (isset($_POST['siteiosapp_app_name'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteiosapp.app.name', $_POST['siteiosapp_app_name']);
        }

        
        if (isset($_POST['siteios_autodetect_enable'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteios.autodetect.enable', $_POST['siteios_autodetect_enable']);
        }

        if (isset($_POST['siteios_change_location'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteios.change.location', $_POST['siteios_change_location']);
        }
        
        if (isset($_POST['ios_enable_location'])) {
            $defaultValue = !empty($_POST['ios_enable_location'])? true: false;
            $db = Engine_Db_Table::getDefaultAdapter();
            $select = new Zend_Db_Select($db);
            $isLanguageRowExist = $select->from('engine4_siteiosapp_menus')
                    ->where('name = ?', "seaocore_location")
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
            if (!empty($isLanguageRowExist))
                $db->query("UPDATE `engine4_siteiosapp_menus` SET `status` = '" . $defaultValue . "' WHERE `engine4_siteiosapp_menus`.`name` = 'seaocore_location' LIMIT 1 ;");
        }
      
        include_once APPLICATION_PATH . '/application/modules/Siteiosapp/controllers/license/license1.php';
        $pluginTitle = array();
        $sitemobile = Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitemobile');
        if (isset($sitemobile) && !empty($sitemobile)) {
            $pluginTitle[] = $sitemobile->title;
        }

        $mobi = Engine_Api::_()->getDbtable('modules', 'core')->getModule('mobi');
        if (isset($mobi) && !empty($mobi)) {
            $pluginTitle[] = $mobi->title;
        }

        $mobi = Engine_Api::_()->getDbtable('modules', 'core')->getModule('mobi');
        if (isset($mobi) && !empty($mobi)) {
            $pluginTitle[] = $mobi->title;
        }

        $this->view->pluginTitle = $pluginTitle;
    }

    public function faqAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteiosapp_admin_main', array(), 'siteiosapp_admin_faq');
    }
    
    public function readmeAction(){        
    }
    
    /*
     * Delete the old exist iOS app from app dashboard.
     */

    public function deleteExistingAppAction() {
        $this->_helper->layout->setLayout('admin-simple');

        if ($this->getRequest()->isPost()) {
            Engine_Api::_()->getApi('core', 'siteiosapp')->validatePreviousMobileAPP();

            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('Deleted Successfully!'))
            ));
        }
    }

}
