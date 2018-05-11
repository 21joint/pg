<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminFaqController.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_AdminFaqController extends Core_Controller_Action_Admin {
  public function indexAction() {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitereaction_admin_main', array(), 'sitereaction_admin_main_faq');
    if (!$this->getRequest()->isPost()) {
      return;
    }
  }

}
