<?php

class Sitemetatag_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
 public function __construct($application)
  {
    parent::__construct($application);
    include APPLICATION_PATH . '/application/modules/Sitemetatag/controllers/license/license.php';
    // Add view helper and action helper paths
    $this->initViewHelperPath();
    //Initialize Eventdocuments helper
    Zend_Controller_Action_HelperBroker::addHelper(new Sitemetatag_Controller_Action_Helper_MetatagPage());
  }
}
