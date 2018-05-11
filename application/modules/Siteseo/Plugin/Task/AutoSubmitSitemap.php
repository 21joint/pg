<?php

/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: submit.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Plugin_Task_AutoSubmitSitemap extends Core_Plugin_Task_Abstract {

    public function execute() {

        $siteseoAutoSubmitSitemap = Zend_Registry::isRegistered('siteseoAutoSubmitSitemap') ? Zend_Registry::get('siteseoAutoSubmitSitemap') : null;
        if (empty($siteseoAutoSubmitSitemap))
            return;
        $setting = Engine_Api::_()->getApi('settings', 'core');
        $default = array('google', 'bing');
        $searchEngines = $setting->getSetting("siteseo.sitemap.submit.searchengines", $default);
        if(!empty($searchEngines)) {
            $regenerate = true;
            Engine_Api::_()->getApi('sitemap','siteseo')->submitSitemap($searchEngines, $regenerate);
        }
    }
}