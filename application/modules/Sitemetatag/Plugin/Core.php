<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2017-01-06 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitemetatag_Plugin_Core {

    public function onRenderLayoutDefault($event = null) {

        // SET META TAGS THROUGH VIEW HELPER
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $view->siteSocialMetaTag();
    }

    public function onRenderLayoutDefaultSimple($event = null) {
    	$this->onRenderLayoutDefault($event);
    }
}