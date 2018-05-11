<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
require_once realpath(dirname(__FILE__)) . '/seaocore_install.php';

class Sitequicksignup_Installer extends Seaocore_License_Installer
{
  protected $_installConfig = array(
    'sku' => 'sitequicksignup',
  );
  protected $_deependencyVersion = array(
    'seaocore' => '4.9.4p13',
    'sitelogin' => '4.9.4p5',
    'sitesubscription' => '4.9.4p4'
  );
}

?>
