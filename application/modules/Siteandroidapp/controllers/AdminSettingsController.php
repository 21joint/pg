<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteandroidapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    AdminSettingsController.php 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteandroidapp_AdminSettingsController extends Core_Controller_Action_Admin {

    public function __call($method, $params) {
        /*
         * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
         * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
         * REMEMBER:
         *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
         *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
         */

        if (!empty($method) && $method == 'Siteandroidapp_Form_Admin_Settings') {
            
        }
        return true;
    }

    public function indexAction() {
        if (isset($_POST['browse_as_guest'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteandroidapp.browse.guest', $_POST['browse_as_guest']);
        }

        if (isset($_POST['siteandroidapp_sound_enable'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteandroidapp.sound.enable', $_POST['siteandroidapp_sound_enable']);
        }
        
         
        if (isset($_POST['android_member_view'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('android.member.view', $_POST['android_member_view']);
        }
        
        if (isset($_POST['siteandroidapp_version_upgrade'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteandroidapp.version.upgrade', $_POST['siteandroidapp_version_upgrade']);
        }

        if (isset($_POST['android_popup_enable'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('android.popup.enable', $_POST['android_popup_enable']);
        }

        if (isset($_POST['siteandroidapp_version_description'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteandroidapp.version.description', $_POST['siteandroidapp_version_description']);
        }

        if (isset($_POST['siteandroid_autodetect_enable'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteandroid.autodetect.enable', $_POST['siteandroid_autodetect_enable']);
        }

        if (isset($_POST['siteandroid_change_location'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteandroid.change.location', $_POST['siteandroid_change_location']);
        }
        
        if (isset($_POST['siteandroidapp_video_quality'])) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteandroidapp.video.quality', $_POST['siteandroidapp_video_quality']);
        }

        if (isset($_POST['android_enable_location'])) {
            $defaultValue = !empty($_POST['android_enable_location']) ? true : false;
            $db = Engine_Db_Table::getDefaultAdapter();
            $select = new Zend_Db_Select($db);
            $isLanguageRowExist = $select->from('engine4_siteandroidapp_menus')
                    ->where('name = ?', "seaocore_location")
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
            if (!empty($isLanguageRowExist))
                $db->query("UPDATE `engine4_siteandroidapp_menus` SET `status` = '" . $defaultValue . "' WHERE `engine4_siteandroidapp_menus`.`name` = 'seaocore_location' LIMIT 1 ;");
        }

        include_once APPLICATION_PATH . '/application/modules/Siteandroidapp/controllers/license/license1.php';
    }

    public function faqAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteandroidapp_admin_main', array(), 'siteandroidapp_admin_faq');
    }

    public function readmeAction() {
        
    }

    /*
     * Delete the old exist android app from app dashboard.
     */

    public function deleteExistingAppAction() {
        $this->_helper->layout->setLayout('admin-simple');

        if ($this->getRequest()->isPost()) {
            Engine_Api::_()->getApi('core', 'siteandroidapp')->validatePreviousMobileAPP();

            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('Deleted Successfully!'))
            ));
        }
    }

}
