<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageform_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  protected function _initFrontController() {
    $this->initActionHelperPath();
    include APPLICATION_PATH . '/application/modules/Sitepageform/controllers/license/license.php';
  }

}
?>
