<?php
/**
 * SocialEngine
 *
 * @category   Application_Theme
 * @package    Clear
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2010-09-24 9:40:21Z SocialEngineAddOns $ 
 * @author     SocialEngineAddOns
 */
return array(
  'package' => array(
    'type' => 'theme',
    'name' => 'clear',
    'version' => '4.8.9',
    'revision' => '',
    'path' => 'application/themes/clear',
    'repository' => 'socialengineaddons.com',
    'title' => 'Clear Theme',
    'thumb' => 'theme.jpg',
    'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'actions' => array(
      'install',
      'upgrade',
      'refresh',
      'remove',
    ),
    'callback' => array(
      'class' => 'Engine_Package_Installer_Theme',
    ),
    'directories' => array(
      'application/themes/clear',
    ),
  ),
  'files' => array(
    'theme.css',
    'constants.css',
  ),
) ?>