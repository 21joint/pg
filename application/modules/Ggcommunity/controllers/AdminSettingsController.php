<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Ggcommunity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: AdminSettingsController.php 10192 2014-05-01 13:16:24Z lucas $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Ggcommunity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Ggcommunity_AdminSettingsController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('ggcommunity_admin_main', array(), 'ggcommunity_admin_main_settings');

    $this->view->form  = $form = new Ggcommunity_Form_Admin_Global();
    
    if( $this->getRequest()->isPost() && $form->isValid($this->_getAllParams()) )
    {
      $values = $form->getValues();

      foreach ($values as $key => $value){
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }

      $translate = Zend_Registry::get('Zend_Translate');
      $form->addNotice($translate->translate('Your changes have been saved.'));
    }
  }

  
}