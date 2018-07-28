<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_ConflictThirdPartySemodsLoader extends Semods_Loader {

  static $_hooked = false;
  var $_trampolines = array('Core_Api_Search' => 'Pgservicelayer_Api_Search');
  var $_loader;

  public static function hook() {
    if (self::$_hooked) {
      return;
    }
    self::$_hooked = true;

    new self();
  }

  public function __construct() {
    $this->_loader = Engine_Loader::getInstance();

    Engine_Loader::setInstance($this);
    $this->_prefixToPaths = $this->_loader->_prefixToPaths;
    $this->_components = $this->_loader->_components;
  }

  public function load($class) {

    if ($class == 'Core_Api_Search') {
      $class = "Pgservicelayer_Api_Search";
    }
    if ($class == 'Siteadvsearch_Api_Search') {
      $class = "Pgservicelayer_Api_Search";
    }
    if ($class == 'Core_Api_Mail') {
      $class = "Sitemailtemplates_Api_Mail";
    }
    if ($class == 'User_Model_DbTable_Membership') {
      $class = "Pgservicelayer_Model_DbTable_Membership";
    }

    return parent::load($class);
  }

  public function addTrampoline($origin, $trampoline) {
    $this->_trampolines[$origin] = $trampoline;
  }

}