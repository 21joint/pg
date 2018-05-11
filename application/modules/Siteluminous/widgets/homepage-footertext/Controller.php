<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_Widget_HomepageFootertextController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->isSitemenuExist = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemenu');
    
    $this->view->sitemenuEnable = false;
    $this->view->show_signup_popup_footer = $this->_getParam("show_signup_popup_footer", 1);
    
    $siteluminous_landing_page_footertext = Zend_Registry::isRegistered('siteluminous_landing_page_footertext') ? Zend_Registry::get('siteluminous_landing_page_footertext') : null;
    if(empty($siteluminous_landing_page_footertext))
      return $this->setNoRender();
        
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemenu')) {
      $this->view->sitemenu_mini_menu_widget = Zend_Registry::isRegistered('sitemenu_mini_menu_widget') ? Zend_Registry::get('sitemenu_mini_menu_widget') : null;
      $this->view->sitemenuEnable = true;

      $front = Zend_Controller_Front::getInstance();
      $module = $front->getRequest()->getModuleName();
      $action = $front->getRequest()->getActionName();
      $controller = $front->getRequest()->getControllerName();
      $this->view->isPost = $front->getRequest()->isPost();

      if (($module == 'user' && $controller == 'auth' && $action == 'login') || ($module == 'core' && $controller == 'error' && $action == 'requireuser')) {
        $this->view->isUserLoginPage = true;
      }
      if ($module == 'user' && $controller == 'signup' && $action == 'index') {
        $this->view->isUserSignupPage = true;
      }
      if ($module == 'core' && $controller == 'error' && $action == 'notfound') {
        $this->view->isUserSignupPage = true;
      }
    }    
  }
}