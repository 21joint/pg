<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Clear.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Plugin_Task_Clear extends Core_Plugin_Task_Abstract {

    public function execute() {
        
        $pluginTaskSchedular = Zend_Registry::isRegistered('pluginTaskSchedular') ? Zend_Registry::get('pluginTaskSchedular') : null;
        if (empty($pluginTaskSchedular))
            return false;
        //Get Cache file
        $cacheOptions = array(
            'cache_with_get_variables' => true,
            'cache_with_cookie_variables' => true,
            'cache_with_session_variables' => true,
            'tags' => array('browse_cache'),
            'cache' => true
        );
        $frontendOptions = array(
            'lifetime' => 1200,
            'cache_id_prefix' => 'advancedpagecache_page_',
            'default_options' => $cacheOptions,
            'memorize_headers' => array('location'),
        );
        
        // Get configurations
        $cacheSettingFile = APPLICATION_PATH . '/application/settings/advancedpagecache.php';
        if (file_exists($cacheSettingFile)) {
            $cacheConfig = include $cacheSettingFile;
            $backend = key($cacheConfig['backend']);
            $backendOptions = $cacheConfig['backend'][$backend];
            $frontendOptions = $cacheConfig['frontend'];
        } else {
            $path = APPLICATION_PATH . '/temporary/sitecache';
            !@is_dir($path) && @mkdir($path, 0777, true);
            $backend = 'File';
            $backendOptions = array(
                'cache_dir' => $path
            );
        }
        if ($backend == "File") {
            $cache = Zend_Cache::factory('Page', $backend, $frontendOptions, $backendOptions, false, true, true);
            $freeSpace = Engine_Api::_()->getApi('core', 'advancedpagecache')->getFreeDiskSpace(APPLICATION_PATH . '/temporary/sitecache');
            if (isset($cacheSettingFile['utilization_space']) && !empty($cacheSettingFile['utilization_space'])) {
                if ($freeSpace < $cacheSettingFile['utilization_space']) {
                    $cache->clean(
                            Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('browse_cache')
                    );
                }
            }
            return true;
        }
    }

}
