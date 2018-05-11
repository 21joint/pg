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

class Siteluminous_Widget_MenuFooterController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation("siteluminous_footer");
    
    $siteluminous_landing_page_footer_menu = Zend_Registry::isRegistered('siteluminous_landing_page_footer_menu') ? Zend_Registry::get('siteluminous_landing_page_footer_menu') : null;
    if(empty($siteluminous_landing_page_footer_menu))
      return $this->setNoRender();
  }

}