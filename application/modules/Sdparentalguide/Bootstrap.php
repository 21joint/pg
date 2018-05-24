<?php

class Sdparentalguide_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
    protected function _initFrontController() {
    	Zend_Controller_Front::getInstance()->registerPlugin(new Sdparentalguide_Plugin_Core);
        $this->initViewHelperPath();
        $this->initActionHelperPath();

        $headLink = new Zend_View_Helper_HeadLink();
        $baseUrl = Zend_Registry::get('StaticBaseUrl');

        $headLink->appendStylesheet($baseUrl
        .'application/themes/guidanceguide/assets/css/root.css');

    }
}