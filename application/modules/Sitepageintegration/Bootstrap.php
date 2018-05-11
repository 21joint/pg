<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepageintegration_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  public function __construct($application) {
    parent::__construct($application);
    include_once APPLICATION_PATH . '/application/modules/Sitepageintegration/controllers/license/license.php';
  }
}