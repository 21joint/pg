<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'siteluminous',
        'version' => '4.9.4p6',
        'path' => 'application/modules/Siteluminous',
        'title' => 'Responsive Luminous Theme',
        'description' => 'Responsive Luminous Theme',
        'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'callback' =>
        array(
            'path' => 'application/modules/Siteluminous/settings/install.php',
            'class' => 'Siteluminous_Installer',
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
            0 => 'application/modules/Siteluminous',
            1 => 'application/themes/luminous',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/siteluminous.csv',
        ),
    ),
    'hooks' => array(
        array(
            'event' => 'onRenderLayoutDefault',
            'resource' => 'Siteluminous_Plugin_Core'
        ),
    ),
    //Items ---------------------------------------------------------------------
    'items' => array(
        'siteluminous_image',
    )
);
?>