<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Plugin_Loader extends Zend_Controller_Plugin_Abstract {

  public function preDispatch(Zend_Controller_Request_Abstract $request) {
    $loader = Engine_Loader::getInstance();
    if (get_class($loader) == 'Engine_Loader') {
      Pgservicelayer_Loader::hook();
      Sitemailtemplates_Loader::hook();
    } else if (get_class($loader) == 'Semods_Loader') {
      Pgservicelayer_ConflictThirdPartySemodsLoader::hook();
    }
  }

}
