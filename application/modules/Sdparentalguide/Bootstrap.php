<?php

class Sdparentalguide_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
    protected function _initFrontController() {
    	Zend_Controller_Front::getInstance()->registerPlugin(new Sdparentalguide_Plugin_Core);
        $this->initViewHelperPath();
        $this->initActionHelperPath();
    }
}