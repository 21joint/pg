<?php

class Ggcommunity_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
    public function __construct($application)
    {
        parent::__construct($application);

        // Add view helper and action helper paths
        $this->initViewHelperPath();
        $this->initActionHelperPath();

        // Add main user javascript
        $headScript = new Zend_View_Helper_HeadScript();
        $headScript->appendFile('application/modules/Ggcommunity/externals/scripts/core.js');

        $StaticBaseUrl = Zend_Registry::get('StaticBaseUrl');

        $headLink = new Zend_View_Helper_HeadLink();
//        $headLink->appendStylesheet($StaticBaseUrl
//        .'application/modules/Ggcommunity/externals/styles/foundation/foundation.css');
    }

}