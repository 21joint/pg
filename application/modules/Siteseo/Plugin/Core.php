<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2017-01-06 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Plugin_Core {

    public function onRenderLayoutDefault($event = null) {

        // SET META TAGS THROUGH SEO META TAGS HELPER
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $view->siteseoMetaTags();
    }
}