<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegifplayer
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegifplayer_AdminSettingsController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {

    include_once APPLICATION_PATH . '/application/modules/Sitegifplayer/controllers/license/license1.php';
  }

  public function faqAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitegifplayer_admin_main', array(), 'sitegifplayer_admin_main_faq');
  }

  public function readmeAction()
  {
    
  }

}
