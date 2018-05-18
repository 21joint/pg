<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Bootstrap extends Engine_Application_Bootstrap_Abstract {

    public function __construct($application) {

        parent::__construct($application);
        include APPLICATION_PATH . '/application/modules/Advancedpagecache/controllers/license/license.php';
    }

    protected function _initCachePage() {

        include_once APPLICATION_PATH_MOD . DS . 'Advancedpagecache/cache.php';
    }

    protected function _initFrontController() {

        $front = Zend_Controller_Front::getInstance();
        $dispatcher = new Advancedpagecache_Controller_Dispatcher_Standard();
        $dispatcher->setDefaultControllerName($front->getDefaultControllerName());
        $dispatcher->setDefaultAction($front->getDefaultAction());
        $dispatcher->setDefaultModule($front->getDefaultModule());
        foreach ($front->getControllerDirectory() as $module => $directory) {
            $dispatcher->addControllerDirectory($directory, $module);
        }
        $front->setDispatcher($dispatcher);
        $this->initViewHelperPath();
        $front->registerPlugin(new Advancedpagecache_Plugin_PartialPageCache);
        $front->registerPlugin(new Advancedpagecache_Plugin_Cache);
    }

}
