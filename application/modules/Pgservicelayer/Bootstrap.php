<?php

class Pgservicelayer_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
    protected function _initFrontController() {
    	Zend_Controller_Front::getInstance()->registerPlugin(new Pgservicelayer_Plugin_Loader);
    }
}