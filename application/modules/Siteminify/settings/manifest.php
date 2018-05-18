<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
  'package' =>
  array(
    'type' => 'module',
    'name' => 'siteminify',
    'version' => '4.9.2',
    'path' => 'application/modules/Siteminify',
    'title' => 'Minify Plugin - Speed up your Website',
    'description' => 'Minify Plugin - Speed up your Website',
    'author' => '<a href="http:\\\\www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'callback' =>
    array(
      'path' => 'application/modules/Siteminify/settings/install.php',
      'class' => 'Siteminify_Installer',
    ),
    'actions' =>
    array(
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' =>
    array(
      0 => 'application/modules/Siteminify',
      1 => 'siteminify',
    ),
    'files' =>
    array(
      0 => 'application/languages/en/siteminify.csv',
    ),
  ),
);
