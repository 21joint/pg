<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Plugin_Core {

    public function getAdminNotifications($event) {
        $browserCache = false;
        $partialCache = false;
        $url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'advancedpagecache', 'controller' => 'settings'), 'admin_default', true);
        $browser_setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache.php';
        if (file_exists($browser_setting_file)) {
            $browser_currentCache = include $browser_setting_file;
            if (isset($browser_currentCache['disable_browse']) && $browser_currentCache['disable_browse'])
                $browserCache = true;
        }
        $partial_setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache_partial.php';
        if (file_exists($partial_setting_file)) {
            $partial_currentCache = include $partial_setting_file;
            if (isset($partial_currentCache['disable_partial']) && $partial_currentCache['disable_partial'])
                $partialCache = true;
        }
        $pluginNotification = Zend_Registry::isRegistered('pluginNotification') ? Zend_Registry::get('pluginNotification') : null;
        if (empty($pluginNotification))
            return false;

        if ($partialCache && $browserCache)
            $tips = '<div class="tip apc_tips"><span>You have enabled Multiple Users and Single User Caching for ‘Page Cache Plugin’. If you want to debug something then you can disable it from <a href="' . $url . '">here</a>.</span></div>';
        elseif (!$partialCache && $browserCache)
            $tips = '<div class="tip apc_tips"><span>You have enabled Single User Caching for ‘Page Cache Plugin’. If you want to debug something then you can disable it from <a href="' . $url . '">here</a>.</span></div>';
        elseif ($partialCache && !$browserCache)
            $tips = '<div class="tip apc_tips"><span>You have enabled Multiple Users Caching for ‘Page Cache Plugin’. If you want to debug something then you can disable it from <a href="' . $url . '">here</a>.</span></div>';
        if (!empty($tips))
            $event->addResponse($tips);
    }

}
