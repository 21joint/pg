<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Siteluminous/controllers/license/license.php';
  }
}