<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$routeStart = "pageoffer";
$module=null;$controller=null;$action=null;$getURL = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module = $request->getModuleName(); // Return the current module name.
  $action = $request->getActionName();
  $controller = $request->getControllerName();
  $getURL = $request->getRequestUri();
}
if (empty($request) || !($module == "default" && ( strpos( $getURL, '/install') !== false))) {
  $routeStart = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.manifestUrl', "page-offers");
}
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitepageoffer',
        'version' => '4.9.4p2',
        'path' => 'application/modules/Sitepageoffer',
        'title' => 'Directory / Pages - Offers Extension',
        'description' => 'Directory / Pages - Offers Extension',
      'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'date' => 'Thrusday, 05 May 2011 18:33:08 +0000',
        'copyright' => 'Copyright 2010-2011 BigStep Technologies Pvt. Ltd.',
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
            'path' => 'application/modules/Sitepageoffer/settings/install.php',
            'class' => 'Sitepageoffer_Installer',
        ),
        'directories' =>
        array(
            0 => 'application/modules/Sitepageoffer',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sitepageoffer.csv',
        ),
    ),
    'sitemobile_compatible' =>true,
    
    'hooks' => array(
        array(
            'event' => 'onUserDeleteBefore',
            'resource' => 'Sitepageoffer_Plugin_Core',
        ),
    ),
    // Items ---------------------------------------------------------------------
    'items' => array(
        'sitepageoffer_offer',
        'sitepageoffer_photo',
        'sitepageoffer_claim',
        'sitepageoffer_album'
    ),
    // Route--------------------------------------------------------------------
    'routes' => array(
        'sitepageoffer_general' => array(
            'route' => $routeStart.'/:action/*',
            'defaults' => array(
                'module' => 'sitepageoffer',
                'controller' => 'index',
                'action' => 'index',
            ),
            'reqs' => array(
                'action' => '(index|create|edit|delete|sticky|print|getoffer|resendoffer)',
            ),
        ),
        'sitepageoffer_hotoffer' => array(
            'route' => $routeStart.'/hotoffer/:id/*',
            'defaults' => array(
                'module' => 'sitepageoffer',
                'controller' => 'admin',
                'action' => 'hotoffer',
            ),
            'reqs' => array(
                'id' => '\d+'
            )
        ),
        'sitepageoffer_details' => array(
            'route' => $routeStart.'/detail/:id/*',
            'defaults' => array(
                'module' => 'sitepageoffer',
                'controller' => 'admin',
                'action' => 'detail',
            ),
            'reqs' => array(
                'id' => '\d+'
            )
        ),
        'sitepageoffer_delete' => array(
            'route' => $routeStart.'/delete/:id/*',
            'defaults' => array(
                'module' => 'sitepageoffer',
                'controller' => 'admin',
                'action' => 'delete',
            ),
            'reqs' => array(
                'id' => '\d+'
            )
        ),
        'sitepageoffer_view' => array(
            'route' => $routeStart . '/:user_id/:offer_id/:slug/*',
            'defaults' => array(
                'module' => 'sitepageoffer',
                'controller' => 'index',
                'action' => 'view',
                'slug' => '',
            ),
            'reqs' => array(
                'user_id' => '\d+'
            )
        ),
        'sitepageoffer_browse' => array(
            'route' => $routeStart.'/browse/*',
            'defaults' => array(
                'module' => 'sitepageoffer',
                'controller' => 'index',
                'action' => 'browse',
            ),
        ),
        'sitepageoffer_home' => array(
            'route' => $routeStart.'/home/*',
            'defaults' => array(
                'module' => 'sitepageoffer',
                'controller' => 'index',
                'action' => 'home',
            ),
        ),
    ),
);
?>