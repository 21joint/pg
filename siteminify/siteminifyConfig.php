<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: siteminifyConfig.php 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
defined('_ENGINE') or die('Access Denied');


$min_documentRoot = _ENGINE_R_BASE;

/**
 * For best performance, specify your temp directory here. Otherwise Minify
 * will have to load extra code to guess. Some examples below:
 */
$min_cachePath = _ENGINE_R_BASE . '/temporary/cache';

$cachePath = _ENGINE_R_BASE . '/application/settings/cache.php';

if( file_exists($cachePath) ) {
  $cacheData = include $cachePath;

  if( isset($cacheData['backend']) &&  array_key_exists('Apc', $cacheData['backend']) ) {
    require dirname(__FILE__) . '/lib/Minify/Cache/APC.php';
    $min_cachePath = new Minify_Cache_APC();
  }
}