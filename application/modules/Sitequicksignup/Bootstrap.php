<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Bootstrap extends Engine_Application_Bootstrap_Abstract {

    protected function _initFrontController() {
        Zend_Controller_Front::getInstance()->registerPlugin(new Sitequicksignup_Plugin_Core);
    }

}
