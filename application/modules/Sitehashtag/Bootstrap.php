<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Bootstrap.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_Bootstrap extends Engine_Application_Bootstrap_Abstract {

    public function _bootstrap($resource = null) {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Sitehashtag_Plugin_Core);
    }
}
