<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'siteseo',
    'version' => '4.9.4p3',
    'path' => 'application/modules/Siteseo',
    'title' => 'Ultimate SEO / Sitemaps Plugin',
    'description' => 'Ultimate SEO / Sitemaps Plugin',
    'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'callback' => 
    array (
      'path' => 'application/modules/Siteseo/settings/install.php',
      'class' => 'Siteseo_Installer',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' => 
    array (
      0 => 'application/modules/Siteseo',
    ),
    'files' => array(
      'application/languages/en/siteseo.csv',
    ),
  ),
  // hooks
  'hooks' => array(
    array(
      'event' => 'onRenderLayoutDefault',
      'resource' => 'Siteseo_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'siteseo_contenttype',
  ),
  // Routes 
  'routes' => array(
    'siteseo_sitemap' => array(
      'route' => 'siteseo/sitemap',
      'defaults' => array(
        'module' => 'siteseo',
        'controller' => 'url',
        'action' => 'sitemap'
      )
    ),
  )
); ?>