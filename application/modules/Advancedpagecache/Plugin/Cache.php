<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Cache.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Plugin_Cache extends Zend_Controller_Plugin_Abstract {

    private $_cacheTime = 518400;

    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        if (strpos($request->getCookie('en4_apc_key'), 'advancedpage_non_loged_') !== false) {
            $viewer = Engine_Api::_()->user()->getViewer();
            if ($viewer && $viewer->getIdentity()) {
                $this->setNewPageCacheKey();
            }
        }
    }

    public function onItemDeleteAfter($event) {
        $this->setNewPageCacheKey();
    }

    public function onItemCreateAfter($event) {
        $this->setNewPageCacheKey();
    }

    public function onItemUpdateAfter($event) {
        $item = $event->getPayload();
        if ($item instanceof Core_Model_Item_Abstract && method_exists($item, 'getModifiedFieldsName')) {
            $modifiedFields = $item->getModifiedFieldsName();
            if (count($modifiedFields) == 1 && in_array('view_count', $modifiedFields)) {
                return;
            }
        }
        $this->setNewPageCacheKey();
    }

    public function onUserLoginAfter($event) {
        $this->setNewPageCacheKey();
    }

    public function onUserLogoutAfter($event) {
        $locale = 'auto';
        $language = 'auto';
        if (!empty($_COOKIE['en4_language']) && !empty($_COOKIE['en4_locale'])) {
            $locale = $_COOKIE['en4_locale'];
            $language = $_COOKIE['en4_language'];
        } else if (!empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            $l = new Zend_Locale(Zend_Locale::BROWSER);
            $locale = $l->toString();
            $language = $l->getLanguage();
        } else {
            $locale = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'auto');
            $language = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'auto');
        }
        // Make sure it's valid
        try {
            $locale = Zend_Locale::findLocale($locale);
        } catch (Exception $e) {
            $locale = 'en_US';
        }
        $key = 'advancedpage_non_loged_' . $locale . $language;
        setcookie('en4_apc_key', $key, time() + ($this->_cacheTime), '/');
    }

    private function setNewPageCacheKey() {
        $key = md5(time());
        setcookie('en4_apc_key', $key, time() + ($this->_cacheTime), '/');
    }

}
