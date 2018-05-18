<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    manifest.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitehashtag',
        'version' => '4.9.0',
        'path' => 'application/modules/Sitehashtag',
        'title' => 'Hashtags Plugin',
        'description' => 'Hashtags Plugin',
        'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'callback' => array(
            'path' => 'application/modules/Sitehashtag/settings/install.php',
            'class' => 'Sitehashtag_Installer',
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
            0 => 'application/modules/Sitehashtag',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sitehashtag.csv',
            1 => 'application/libraries/Engine/Db/Table/Row.php'
        ),
    ),
    // Mobile / Tablet Plugin Compatible
    'sitemobile_compatible' => true,
    'composer' => array(
        'hashtag' => array(
            'script' => array('_composerHashtag.tpl', 'sitehashtag')
        ),
    ),
// Routes ---------------------------------------------------------------------
    'routes' => array(
        // Public
        'sitehashtag_general' => array(
            'route' => 'hashtag/:action/*',
            'defaults' => array(
                'module' => 'sitehashtag',
                'controller' => 'index',
                'action' => 'index',
            ),
        ),
    ),
// Items ---------------------------------------------------------------------
    'items' => array(
        'sitehashtag_content',
        'sitehashtag_tag',
    ),
// Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onActivityActionCreateAfter',
            'resource' => 'Sitehashtag_Plugin_Core'
        ),
        array(
            'event' => 'onItemUpdateAfter',
            'resource' => 'Sitehashtag_Plugin_Core'
        ),
         array(
            'event' => 'onActivityActionUpdateAfter',
            'resource' => 'Sitehashtag_Plugin_Core'
        ),
        array(
            'event' => 'onActivityActionDeleteBefore',
            'resource' => 'Sitehashtag_Plugin_Core'
        ),
        array(
            'event' => 'onItemDeleteBefore',
            'resource' => 'Sitehashtag_Plugin_Core'
        ),
    ),
);
