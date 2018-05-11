<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'captivate',
        'version' => '4.9.4p5',
        'path' => 'application/modules/Captivate',
        'title' => 'Responsive Captivate Theme',
        'description' => 'Responsive Captivate Theme',
        'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'callback' =>
        array(
            'path' => 'application/modules/Captivate/settings/install.php',
            'class' => 'Captivate_Installer',
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
            0 => 'application/modules/Captivate',
            1 => 'application/themes/captivate',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/captivate.csv',
        ),
    ),
    'hooks' => array(
        array(
            'event' => 'onRenderLayoutDefault',
            'resource' => 'Captivate_Plugin_Core'
        ),
        array(
            'event' => 'onRenderLayoutDefaultSimple',
            'resource' => 'Captivate_Plugin_Core',
        ),
    ),
    //Items ---------------------------------------------------------------------
    'items' => array(
        'captivate_image',
        'captivate_banner'
    )
);
?>