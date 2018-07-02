<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Controller_Loader extends Engine_Loader {

    /**
     * Get current singleton instance
     * 
     * @return Engine_Loader
     */
    public static function getInstance() {
        return new self();
    }

    /**
     * Loads and instantiates a resource class
     * 
     * @param string $class
     * @return mixed
     */
    public function setComponentsObject($class, $orignalClassName = null) {
        if (empty($orignalClassName))
            $orignalClassName = $class;

        $loader = Engine_Loader::getInstance();
        return $loader->_components[$orignalClassName] = $loader->load($class);
    }

}
