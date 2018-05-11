<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegifplayer
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2017-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegifplayer_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  public function __construct($application)
  {

    parent::__construct($application);
    include APPLICATION_PATH . '/application/modules/Sitegifplayer/controllers/license/license.php';
  }

  protected function _initFrontController()
  {
    $gifPlayerActive = Zend_Registry::isRegistered('sitegifplayerLoaded') ? Zend_Registry::get('sitegifplayerLoaded') : 0;
    if( empty($gifPlayerActive) ) {
      return;
    }
    $this->initViewHelperPath();
    $headScript = new Zend_View_Helper_HeadScript();
    if( Zend_Registry::isRegistered('StaticBaseUrl') ) {
      $headScript->appendFile(Zend_Registry::get('StaticBaseUrl')
        . 'application/modules/Sitegifplayer/externals/scripts/core.js');
    } else {
      $headScript->appendFile('application/modules/Sitegifplayer/externals/scripts/core.js');
    }
  }

}
