<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: cache.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
if (!defined('_ADVANCED_PAGECACHE_ENABLED')) {
    $enabled_module_file = APPLICATION_PATH . '/application/settings/enabled_module_directories.php';
    if (file_exists($enabled_module_file)) {
        $enabledmodules = include $enabled_module_file;
        if (!in_array('Advancedpagecache', $enabledmodules)) {
            define('_ADVANCED_PAGECACHE_ENABLED', true);
            return;
        }
    }
    $request = new Zend_Controller_Request_Http();
    $params = $request->getParams();
    if(isset($params['igonrePageCache'])){
        define('_ADVANCED_PAGECACHE_ENABLED', true);
        return;
    }
    if(isset($_GET['PageCache'])){
        unset($_GET['PageCache']);
      //  $request->setParams($params);
    }
    
    $script = $request->getServer('SCRIPT_NAME');
    $_SERVER['SCRIPT_NAME'] = str_replace('/application/', '/', $script);
    $baseUrl = $request->getBaseUrl();
    $_SERVER['SCRIPT_NAME'] = $script;
    $requestPath = $request->getPathInfo();

    $coresettingFile = APPLICATION_PATH . '/application/settings/cache.php';
    $currentCache = array();
    if (APPLICATION_ENV == 'production' && file_exists($coresettingFile)) {
        $currentCache = include $coresettingFile;
    }
    $enabled = isset($currentCache['frontend']['core']['caching']) ? $currentCache['frontend']['core']['caching'] : true;
    if (APPLICATION_ENV != 'production' || !$enabled || $request->isXmlHttpRequest() || $request->isFlashRequest()) {
        define('_ADVANCED_PAGECACHE_ENABLED', true);
        return;
    }
    if (!$request->getCookie('en4_apc_key')) {
        $locale = 'auto';
        $language = 'auto';
        if (!empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            $l = new Zend_Locale(Zend_Locale::BROWSER);
            $locale = $l->toString();
            $language = $l->getLanguage();
        }
        // Make sure it's valid
        try {
            $locale = Zend_Locale::findLocale($locale);
        } catch (Exception $e) {
            $locale = 'en_US';
        }
        $key = 'advancedpage_non_loged_' . $locale . $language;
        if(empty($_COOKIE)) {
            $_COOKIE['en4_apc_key'] = $key;
        }
        setcookie('en4_apc_key', $key, time() + (518400), '/');
    }

    // Get configurations
    $file = APPLICATION_PATH . '/application/settings/advancedpagecache.php';
    $ignoreUrl = array(
        '/admin',
        '/utility/task',
        '/logout',
    );
    if (file_exists($file)) {
        $config = include $file;
        $ignoreUrl = array_merge($ignoreUrl, $config['ignoreUrl']);
        if (empty($config['disable_browse'])) {
            define('_ADVANCED_PAGECACHE_ENABLED', true);
            return;
        }
    }
    $isInValidPath = false;
    foreach ($ignoreUrl as $ignorePath) {
        if (substr($request->getPathInfo(), 0, strlen($ignorePath)) !== $ignorePath) {
            continue;
        }
        $isInValidPath = true;
        break;
    }

    // CHECK IF ADMIN
    if ($isInValidPath) {
        define('_ADVANCED_PAGECACHE_ENABLED', true);
        return;
    }

    $cacheOptions = array(
        'cache_with_get_variables' => true,
        'cache_with_cookie_variables' => true,
        'cache_with_session_variables' => true,
        'tags' => array('browse_cache'),
        'cache' => true
    );
    $defalutOptions = array(
        'cache' => false
    );
    $pathPrefix = '^' . rtrim($baseUrl, '/');
    $frontendOptions = array(
        'lifetime' => 1200,
        'cache_id_prefix' => 'advancedpagecache_page_',
        'default_options' => $cacheOptions,
        'memorize_headers' => array('location'),
    );
    if (file_exists($file)) {
        $cacheConfig = include $file;
        $backend = key($cacheConfig['backend']);
        $backendOptions = $cacheConfig['backend'][$backend];
        $frontendOptions = $cacheConfig['frontend'];
    } else {
        $cacheConfig['utilization_space'] = 0.5;
        $path = APPLICATION_PATH . '/temporary/sitecache';
        !@is_dir($path) && @mkdir($path, 0777, true);
        $backend = 'File';
        $backendOptions = array(
            'cache_dir' => $path
        );
    }
    if ($backend === 'File') {
        $bytes = disk_free_space(APPLICATION_PATH . '/temporary/sitecache');
        $freeSpace = round(($bytes / 1073741824), 2);
        if ($backend === 'File' && $freeSpace < $cacheConfig['utilization_space']) {
            define('_ADVANCED_PAGECACHE_ENABLED', true);
            return;
        }
    }
    // getting a Zend_Cache_Frontend_Page object
    $cookiesStore = $_COOKIE;
    $allowCookiesKey = array('en4_apc_key', 'en4_language', 'en4_locale', 'seaocore_myLocationDetails');
    $_COOKIE = array_intersect_key($_COOKIE, array_flip($allowCookiesKey));
    $cache = Zend_Cache::factory('Page', $backend, $frontendOptions, $backendOptions, false, true, true);
    $isLoaded = $cache->start(false);
    $_COOKIE = $cookiesStore;
    define('_ADVANCED_PAGECACHE_ENABLED', true);
}