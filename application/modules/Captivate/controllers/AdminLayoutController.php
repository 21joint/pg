<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminLayoutController.php 2015-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_AdminLayoutController extends Core_Controller_Action_Admin {

    public function indexAction() {

        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('captivate_admin_main', array(), 'captivate_admin_layout_index');

        //MAKE FORM
        $this->view->form = $form = new Captivate_Form_Admin_Layout();

        // Check method/data
        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $values = $form->getValues();
        foreach ($values as $key => $value) {
            $coreSettings->setSetting($key, $value);
        }

        if ($values['captivate_landing_page_layout']) {
            Engine_Api::_()->captivate()->setDefaultLayout($values);
            $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
            $URL = $view->baseUrl() . '/admin/content';
            $form->addNotice('Your changes have been saved. Please check your home page layout from <a href="' . $URL . '" target="_blank">here</a>.');
        } else {
            $form->addNotice('Your changes have been saved.');
        }
    }

}
