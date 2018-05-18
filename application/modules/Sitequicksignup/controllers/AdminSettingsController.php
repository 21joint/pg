<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_AdminSettingsController extends Core_Controller_Action_Admin {

    public function indexAction() {

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitequicksignup_admin_main', array(), 'sitequicksignup_admin_main_settings');
        include APPLICATION_PATH . '/application/modules/Sitequicksignup/controllers/license/widgetSettings.php';

        $this->view->form = $form = new Sitequicksignup_Form_Admin_Global();

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            $values = $form->getValues();
            foreach ($values as $key => $value) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
            }
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $db->query("UPDATE `engine4_sitequicksignup_signup` SET `enable` = ".$values['sitequicksignup_subscription_enabled']." WHERE `class` LIKE '%Plugin_Signup_Subscription'");
            $form->addNotice('Your changes have been saved.');
        }
    }

    public function faqAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitequicksignup_admin_main', array(), 'sitequicksignup_admin_main_faq');
        $this->view->faq_id = $this->_getParam('faq_id', 'faq_1');
    }

    //ACTINO FOR SEARCH FORM TAB
    public function formOrderAction() {

        //GET NAVIGATION
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitequicksignup_admin_main', array(), 'sitequicksignup_admin_main_formorder');

        //GET SEARCH TABLE
        $tableOrderForm = Engine_Api::_()->getDbTable('fieldorder', 'sitequicksignup');

        //CHECK POST
        if ($this->getRequest()->isPost()) {

            //BEGIN TRANSCATION
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $values = $_POST;
            $rowProfielTypes = $tableOrderForm->getFieldsOptions('profiletypes');
            $defaultProfileType = 0;
            $defaultAddition = 0;
            $count = 100000;
            try {
                foreach ($values['order'] as $key => $value) {
                    $multiplyAddition = $count * 20;
                    $tableOrderForm->update(array('order' => $defaultAddition + $defaultProfileType + $key + $multiplyAddition + 1), array('fieldorder_id = ?' => (int) $value));
                    if (!empty($rowProfielTypes) && $value == $rowProfielTypes->fieldorder_id) {
                        $defaultProfileType = 1;
                        $defaultAddition = 10000000;
                    }
                    $count++;
                }
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }

        //MAKE QUERY
        $select = $tableOrderForm->select()->order('order');
        $this->view->orderForm = $tableOrderForm->fetchAll($select);
    }

    //ACTION FOR DISPLAY/HIDE FIELDS OF SEARCH FORM
    public function diplayFormAction() {

        $name = $this->_getParam('name');
        $display = $this->_getParam('display');
        Engine_Api::_()->getApi('settings', 'core')->setSetting($name, $display);
        $this->_redirect('admin/sitequicksignup/settings/form-order');
    }

}
