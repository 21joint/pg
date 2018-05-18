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

class Siteluminous_Widget_LandingPageCssController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $siteluminous_landing_page_included_css = Zend_Registry::isRegistered('siteluminous_landing_page_included_css') ? Zend_Registry::get('siteluminous_landing_page_included_css') : null;
    if(empty($siteluminous_landing_page_included_css))
      return $this->setNoRender();
  }

}