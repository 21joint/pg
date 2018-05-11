<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitequicksignup',
        'version' => '4.9.4p6',
        'path' => 'application/modules/Sitequicksignup',
        'title' => 'Quick & Single Step Signup Plugin',
        'description' => 'Quick & Single Step Signup Plugin',
        'author' => 'SocialEngineAddOns',
        'callback' => array(
            'path' => 'application/modules/Sitequicksignup/settings/install.php',
            'class' => 'Sitequicksignup_Installer',
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
            0 => 'application/modules/Sitequicksignup',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sitequicksignup.csv',
        ),
    ),
    // Routes --------------------------------------------------------------------
    'routes' => array(
        'sitequicksignup_signup' => array(
            'route' => '/signup/:action/*',
            'defaults' => array(
                'module' => 'sitequicksignup',
                'controller' => 'signup',
                'action' => 'index'
            )
        ),
    ),
    // Hooks --------------------------------------------------------------------
   'hooks' => array(
        array(
            'event' => 'onUserSignupAfter',
            'resource' => 'Sitequicksignup_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutDefault',
            'resource' => 'Sitequicksignup_Plugin_Core',
          ),
  ),
);
?>