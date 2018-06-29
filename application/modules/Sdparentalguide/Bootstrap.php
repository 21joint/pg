<?php

class Sdparentalguide_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
    protected function _initFrontController()
    {
        Zend_Controller_Front::getInstance()->registerPlugin(new Sdparentalguide_Plugin_Core);
        $this->initViewHelperPath();
        $this->initActionHelperPath();

        $headLink = new Zend_View_Helper_HeadLink();
        $baseUrl = Zend_Registry::get('StaticBaseUrl');

        $headScript = new Zend_View_Helper_HeadScript();
        $headScript->appendFile($baseUrl . 'application/modules/Sdparentalguide/externals/scripts/core.js');
        $headLink->appendStylesheet($baseUrl . '../styles/parental.css');
    }
}