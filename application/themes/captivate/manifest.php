<?php
/**
 * SocialEngine
 *
 * @category   Application_Theme
 * @package    captivate Theme
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2010-09-24 9:40:21Z SocialEngineAddOns $ 
 * @author     SocialEngineAddOns
 */

return array(
  'package' => array(
    'type' => 'theme',
    'name' => 'Responsive Captivate Theme',
    'version' => '4.9.4p5',
    'path' => 'application/themes/captivate',
    'title' => 'Responsive Captivate Theme',
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
      'application/themes/captivate',
    ),
  ),
  'files' => array(
    'theme.css',
    'variableConstants.css',
    'constants.css',
    'media-queries.css',
    'fonts.css',
    'customization.css'
  ),
) ?>