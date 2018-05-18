<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 6590 2016-3-3 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Bootstrap extends Engine_Application_Bootstrap_Abstract {

    public function __construct($application) {

        parent::__construct($application);
        include APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license.php';
    }

    protected function _initFrontController() {
    	Zend_Controller_Front::getInstance()->registerPlugin(new Sitecredit_Plugin_Core);
        $this->initViewHelperPath();
        $this->initActionHelperPath();
    }
    
}
