<?php

class Sdparentalguide_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
    protected function _initFrontController()
    {
        Zend_Controller_Front::getInstance()->registerPlugin(new Sdparentalguide_Plugin_Core);
        $this->initViewHelperPath();
        $this->initActionHelperPath();
        $staticBaseUrl = Zend_Registry::get('StaticBaseUrl');
        $headLink = new Zend_View_Helper_HeadLink();
        $headScript = new Zend_View_Helper_HeadScript();

        $headLink->appendStylesheet($staticBaseUrl.'application/modules/Sdparentalguide/externals/build/styles/prg.bundle.css');
        $headScript->appendFile($staticBaseUrl.'application/modules/Sdparentalguide/externals/build/scripts/prg.bundle.js');
    }
}