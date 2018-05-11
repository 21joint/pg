<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteminify_Bootstrap extends Engine_Application_Bootstrap_Abstract
{

  public function __construct($application)
  {
    parent::__construct($application);
    if( APPLICATION_ENV == 'production' ) {
      $this->initViewHelperPath();
      Zend_Registry::get('Zend_View')->registerHelper(new Siteminify_View_Helper_HeadLink(), 'headLink');
      Zend_Registry::get('Zend_View')->registerHelper(new Siteminify_View_Helper_HeadScript(), 'headScript');
    }
  }
}
