<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Content.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_View_Helper_Content extends Engine_View_Helper_Content {

    /**
     * Name of current area
     * 
     * @var string
     */
    protected $_name;

    /**
     * Render a content area by name
     * 
     * @param string $name
     * @return string
     */
    public function content($name = null) {
        // Direct access
        if (func_num_args() == 0) {
            return $this;
        }

        if (func_num_args() > 1) {
            $name = func_get_args();
        }

        $content = Engine_Content::getInstance();
        Zend_Registry::set('Internal_Action', in_array($name, array('header', 'footer')));
        $return = $content->render($name);
        Zend_Registry::set('Internal_Action', false);
        return $return;
    }

}
