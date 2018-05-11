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

class Siteluminous_Widget_LandingSearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->isSiteadvsearchEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteadvsearch');
    $this->view->showLocationSearch = $this->_getParam('showLocationSearch', 0);
    $this->view->showLocationBasedContent = $this->_getParam('showLocationBasedContent', 0);
    $siteluminous_landing_page_search = Zend_Registry::isRegistered('siteluminous_landing_page_search') ? Zend_Registry::get('siteluminous_landing_page_search') : null;
    if(empty($siteluminous_landing_page_search))
      return $this->setNoRender();
  }

}