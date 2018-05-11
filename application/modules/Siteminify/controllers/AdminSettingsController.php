<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteminify_AdminSettingsController extends Core_Controller_Action_Admin {

    public function indexAction() {
        include APPLICATION_PATH . '/application/modules/Siteminify/controllers/license/license1.php';
    }

    public function readmeAction() {

    }

    public function guidlineAction() {
      $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteminify_admin_main', array(), 'siteminify_admin_main_guidline');
    }

    public function faqAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteminify_admin_main', array(), 'siteminify_admin_main_faq');
        $this->view->action = 'faq';
        $this->view->faq_type = $this->_getParam('faq_type', 'general');
    }

}
