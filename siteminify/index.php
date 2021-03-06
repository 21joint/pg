<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.php 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

/**
 * Front controller for default Minify implementation
 * 
 * DO NOT EDIT! Configure this utility via config.php and groupsConfig.php
 * 
 * @package Minify
 */

defined('_ENGINE') || define('_ENGINE', true);
define('MINIFY_MIN_DIR', dirname(__FILE__));
define('_ENGINE_R_BASE', dirname(MINIFY_MIN_DIR));

// set config path defaults
$min_configPaths = array(
  'base' => MINIFY_MIN_DIR . '/config.php',
  'siteminify' => MINIFY_MIN_DIR . '/siteminifyConfig.php',
  'groups' => MINIFY_MIN_DIR . '/groupsConfig.php'
);

// check for custom config paths
if( !empty($min_customConfigPaths) && is_array($min_customConfigPaths) ) {
  $min_configPaths = array_merge($min_configPaths, $min_customConfigPaths);
}

// load config
require $min_configPaths['base'];
require $min_configPaths['siteminify'];

require "$min_libPath/Minify/Loader.php";
Minify_Loader::register();
Minify::$uploaderHoursBehind = $min_uploaderHoursBehind;
Minify::setCache(
  isset($min_cachePath) ? $min_cachePath : ''
  , $min_cacheFileLocking
);

if( $min_documentRoot ) {
 // $_SERVER['DOCUMENT_ROOT'] = $min_documentRoot;
  Minify::$isDocRootSet = true;
}

$min_serveOptions['minifierOptions']['text/css']['symlinks'] = $min_symlinks;
// auto-add targets to allowDirs
foreach( $min_symlinks as $uri => $target ) {
  $min_serveOptions['minApp']['allowDirs'][] = $target;
}

if( $min_allowDebugFlag ) {
  $min_serveOptions['debug'] = Minify_DebugDetector::shouldDebugRequest($_COOKIE, $_GET, $_SERVER['REQUEST_URI']);
}

if( !empty($min_concatOnly) ) {
  $min_serveOptions['concatOnly'] = true;
}

if( $min_errorLogger ) {
  if( true === $min_errorLogger ) {
    $min_errorLogger = FirePHP::getInstance(true);
  }
  Minify_Logger::setLogger($min_errorLogger);
}

// check for URI versioning
if( preg_match('/&\\d/', $_SERVER['QUERY_STRING']) || isset($_GET['v']) ) {
  $min_serveOptions['maxAge'] = 31536000;
}

// need groups config?
if( isset($_GET['g']) ) {
  // well need groups config
  $min_serveOptions['minApp']['groups'] = (require $min_configPaths['groups']);
}

// serve or redirect
if( isset($_GET['f']) || isset($_GET['g']) ) {
  if( !isset($min_serveController) ) {
    $min_serveController = new Minify_Controller_MinApp();
  }

  Minify::serve($min_serveController, $min_serveOptions);
} elseif( $min_enableBuilder ) {
  header('Location: builder/');
  exit;
} else {
  header('Location: /');
  exit;
}
