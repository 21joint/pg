<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitefaq_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  protected function _initFrontController() {

    $this->initActionHelperPath();

    $front = Zend_Controller_Front::getInstance();
    $front->registerPlugin(new Sitefaq_Plugin_Core);
  }

  public function __construct($application) {
		parent::__construct($application);
		include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license.php';
  }

}