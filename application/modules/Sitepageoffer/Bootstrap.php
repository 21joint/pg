<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstarp.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  protected function _initFrontController() {
    
    include APPLICATION_PATH . '/application/modules/Sitepageoffer/controllers/license/license.php';
  }
 
  public function __construct($application) {
		parent::__construct($application);
    $this->initViewHelperPath();
  }

}
?>