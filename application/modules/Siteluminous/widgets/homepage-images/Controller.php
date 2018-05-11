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

class Siteluminous_Widget_HomepageImagesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->defaultDuration = $this->_getParam("speed", 5000);
    $this->view->slideWidth = $this->_getParam("width", null);
    $this->view->slideHeight = $this->_getParam("height", 583);
    $this->view->show_login = $this->_getParam("show_login", 1);
    $this->view->show_signup = $this->_getParam("show_signup", 1);
    $this->view->show_login_popup = $this->_getParam("show_login_popup", 1);
    $this->view->show_signup_popup = $isSiteluminousEnableSignupLightbox = $this->_getParam("show_signup_popup", 1);
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    if (empty($viewer_id)) {
      $tempClassArray = array(
          'Payment_Plugin_Signup_Subscription',
          'Sladvsubscription_Plugin_Signup_Subscription'
      );
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      $subscriptionObj = $db->query('SELECT `class` FROM `engine4_user_signup` WHERE  `enable` = 1 ORDER BY `engine4_user_signup`.`order` ASC LIMIT 1')->fetch();
      if (!empty($subscriptionObj) && isset($subscriptionObj['class']) && !empty($subscriptionObj['class']) && in_array($subscriptionObj['class'], $tempClassArray)) {
        $isSubscriptionEnabled = true;
      }
    }
    if (empty($isSubscriptionEnabled) && !empty($isSiteluminousEnableSignupLightbox)) {
      $this->view->show_signup_popup = true;
    } elseif(!empty($isSubscriptionEnabled)) {
      $this->view->show_signup_popup = false;
    }
    $order = $this->_getParam("order", 2);
        
    $siteluminousManageType = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.manage.type', 1);
    $siteluminousInfoType = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.info.type', 1);
    $this->view->isSitemenuExist = $isSitemenuExist = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemenu');
    
    $coreTableObj = Engine_Api::_()->getDbtable('pages', 'core');
    $select = $coreTableObj->select()->where('name = ?', "core_index_index");
    $getLandingPageObj = $coreTableObj->fetchRow($select);
    if(!empty($getLandingPageObj) && !empty($getLandingPageObj->layout) && ($getLandingPageObj->layout == 'default-simple')) {
      $this->view->isSitemenuExist = $isSitemenuExist = null;
    }
    
    
    $hostType = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
    $siteluminous_landing_page_images = Zend_Registry::isRegistered('siteluminous_landing_page_images') ? Zend_Registry::get('siteluminous_landing_page_images') : null;
    if(empty($siteluminous_landing_page_images))
      return $this->setNoRender();
    
    $getImages = Engine_Api::_()->getItemTable('siteluminous_image')->getImages(array(
        'order' => $order,
        'enabled' => 1,
    ), array('file_id'));
        
    $tempHostType = $tempSitemenuLtype = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.global.view', 0);
    $siteluminousLtype = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.lsettings', null);
    
    
    if(!COUNT($getImages)) {
      $this->view->getImages = array("1.png", "2.png", "3.png" , "4.png");
    }else {
      $getImagesArray = $getImages->toArray();
      if(!empty($order) && $order == 1) {
        $getImagesArray = @array_reverse($getImagesArray);
      }else if(!empty($order) && $order == 2) {
        @shuffle($getImagesArray);
      }
      $this->view->getImages = $getImagesArray;
    }
    
    for ($check = 0; $check < strlen($hostType); $check++) {
      $tempHostType += @ord($hostType[$check]);
    }
    for ($check = 0; $check < strlen($siteluminousLtype); $check++) {
      $tempSitemenuLtype += @ord($siteluminousLtype[$check]);
    }
    $this->view->sitemenuEnable = false;        
    if($isSitemenuExist) {
      $this->view->sitemenuEnable = true;
      $this->view->sitemenu_mini_menu_widget = Zend_Registry::isRegistered('sitemenu_mini_menu_widget') ? Zend_Registry::get('sitemenu_mini_menu_widget') : null;
    
      $front = Zend_Controller_Front::getInstance();
      $module = $front->getRequest()->getModuleName();
      $action = $front->getRequest()->getActionName();
      $controller = $front->getRequest()->getControllerName();
      $this->view->isPost = $front->getRequest()->isPost();
      Zend_Registry::set('sitemenu_mini_menu_widget', 'enable');

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
    
    if(($siteluminousManageType != $tempHostType) || ($siteluminousInfoType != $tempSitemenuLtype)) {
      return $this->setNoRender();
    }
  }
}