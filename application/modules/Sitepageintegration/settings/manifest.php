<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitepageintegration',
        'version' => '4.9.4',
        'path' => 'application/modules/Sitepageintegration',
        'title' => 'Directory / Pages - Multiple Listings and Products Showcase Extension',
        'description' => 'Directory / Pages - Multiple Listings and Products Showcase Extension',
      'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'actions' =>
        array(
            0 => 'install',
            1 => 'upgrade',
            2 => 'refresh',
            3 => 'enable',
            4 => 'disable',
        ),
        'callback' =>
        array(
            'path' => 'application/modules/Sitepageintegration/settings/install.php',
            'class' => 'Sitepageintegration_Installer',
        ),
        'directories' =>
        array(
            0 => 'application/modules/Sitepageintegration',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sitepageintegration.csv',
        ),
    ),
    // Items ---------------------------------------------------------------------
    'items' => array(
      'sitepageintegration_mixsettings'
    ),
  //  'sitemobile_compatible' => true,
    // Routes --------------------------------------------------------------------
    'routes' => array(
			// Public
			'sitepageintegration_create' => array(
				'route' => 'sitepageintegration/:action/:page_id/:resource_type/*',
				'defaults' => array(
					'module' => 'sitepageintegration',
					'controller' => 'index',
				),
				'reqs' => array(
					'action' => '(index|my-pages|manage-auto-suggest|list|delete)',
					'page_id' => '\d+',
					'resource_type' => '\D+'
				)
			),
    ),
);