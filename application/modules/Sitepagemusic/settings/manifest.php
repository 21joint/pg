<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$routeStart = "pagemusic";
$module = null;
$controller = null;
$action = null;
$getURL = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module = $request->getModuleName(); // Return the current module name.
  $action = $request->getActionName();
  $controller = $request->getControllerName();
  $getURL = $request->getRequestUri();
}
if (empty($request) || !($module == "default" && ( strpos( $getURL, '/install') !== false))) {
  $routeStart = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.manifestUrl', "page-music");
}
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitepagemusic',
        'version' => '4.9.4p1',
        'path' => 'application/modules/Sitepagemusic',
        'title' => 'Directory / Pages - Music Extension',
        'description' => 'Directory / Pages - Music Extension',
      'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'callback' => array(
            'path' => 'application/modules/Sitepagemusic/settings/install.php',
            'class' => 'Sitepagemusic_Installer',
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
            0 => 'application/modules/Sitepagemusic',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sitepagemusic.csv',
        ),
    ),
     'sitemobile_compatible' =>true,
    'hooks' => array(
        array(
            'event' => 'onUserDeleteBefore',
            'resource' => 'Sitepagemusic_Plugin_Core',
        ),
        array(
            'event' => 'onItemDeleteAfter',
            'resource' => 'Sitepagemusic_Plugin_Core',
        )
    ),
    // Compose -------------------------------------------------------------------
    'compose' => array(
        array('_composeMusic.tpl', 'sitepagemusic'),
    ),
    'composer' => array(
        'sitepagemusic' => array(
            'script' => array('_composeMusic.tpl', 'sitepagemusic'),
            'plugin' => 'Sitepagemusic_Plugin_Composer',
            'auth' => array('sitepage_page', 'smcreate'),
        ),
    ),
    // ITEMS ---------------------------------------------------------------------
    'items' => array(
        'sitepagemusic_playlist',
        'sitepagemusic_playlist_song',
    ),
    // ROUTES --------------------------------------------------------------------
    'routes' => array(
        'sitepagemusic_create' => array(
            'route' => $routeStart . '/create/:page_id/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'index',
                'action' => 'create'
            ),
            'reqs' => array(
                'page_id' => '\d+'
            )
        ),
        'sitepagemusic_song_specific' => array(
            'route' => $routeStart . '/song/:song_id/:action/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'song',
                'action' => 'view',
            ),
            'reqs' => array(
                'song_id' => '\d+',
                'action' => '(view|delete|rename|upload|append)',
            ),
        ),
        'sitepagemusic_playlist_view' => array(
            'route' => $routeStart . '/:playlist_id/:slug/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'playlist',
                'action' => 'view',
                'slug' => '',
            ),
            'reqs' => array(
                'playlist_id' => '\d+'
            )
        ),
        'sitepagemusic_playlist_specific' => array(
            'route' => $routeStart . '/:playlist_id/:action/:slug/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'playlist',
                'action' => 'view',
            ),
            'reqs' => array(
                'playlist_id' => '\d+',
                'action' => '(view|edit|delete|sort|add-song|set-profile)',
            ),
        ),
        'sitepagemusic_extended' => array(
            'route' => $routeStart . '/:controller/:action/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'index',
                'action' => 'index',
            ),
            'reqs' => array(
                'controller' => '\D+',
                'action' => '\D+',
            ),
        ),
        'sitepagemusic_general' => array(
            'route' => $routeStart . '/:action/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'index',
            ),
            'reqs' => array(
                'action' => '(remove-song)',
            ),
        ),
        'sitepagemusic_tally' => array(
            'route' => 'pagemusictally/song/:song_id/:action/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'song',
                'action' => 'tally',
            ),
            'reqs' => array(
                'song_id' => '\d+',
            ),
        ),
        'sitepagemusic_browse' => array(
            'route' => $routeStart.'/browse/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'playlist',
                'action' => 'browse',
            ),
        ),
        'sitepagemusic_home' => array(
            'route' => $routeStart.'/home/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'playlist',
                'action' => 'home',
            ),
        ), 
         'sitepagemusic_featured' => array(
            'route' => $routeStart . '/featured/:playlist_id/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'playlist',
                'action' => 'featured'
            ),
            'reqs' => array(
                'playlist_id' => '\d+'
            )
        ),
         'sitepagemusic_featuredmusic' => array(
            'route' => $routeStart .'/admin/featuredmusic/:id/*',
            'defaults' => array(
                'module' => 'sitepagemusic',
                'controller' => 'admin-manage',
                'action' => 'featuredmusic',
            ),
            'reqs' => array(
                'id' => '\d+'
            )
        ),
    ),
);
?>