<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$routeStart = "pagereviews";
$module=null;$controller=null;$action=null;$getURL = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module = $request->getModuleName(); // Return the current module name.
  $action = $request->getActionName();
  $controller = $request->getControllerName();
  $getURL = $request->getRequestUri();
}
if (empty($request) || !($module == "default" && ( strpos( $getURL, '/install') !== false))) {
  $routeStart = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.manifestUrl', "page-reviews");
}
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitepagereview',
        'version' => '4.9.4p1',
        'path' => 'application/modules/Sitepagereview',
        'title' => 'Directory / Pages - Reviews and Ratings Extension',
        'description' => 'Directory / Pages - Reviews and Ratings Extension',
      'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'date' => 'Thrusday, 05 May 2011 18:33:08 +0000',
        'copyright' => 'Copyright 2010-2011 BigStep Technologies Pvt. Ltd.',
        'callback' => array(
            'path' => 'application/modules/Sitepagereview/settings/install.php',
            'class' => 'Sitepagereview_Installer',
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
            0 => 'application/modules/Sitepagereview',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sitepagereview.csv',
        ),
    ),
    'hooks' => array(
        array(
            'event' => 'onUserDeleteBefore',
            'resource' => 'Sitepagereview_Plugin_Core',
        ),
    ),
    'items' => array(
        'sitepagereview_review',
        'sitepagereview_reviewcat'
    ),
    'sitemobile_compatible' =>true,
    // Routes --------------------------------------------------------------------
    'routes' => array(
        'sitepagereview_create' => array(
            'route' => $routeStart.'/create/:page_id/*',
            'defaults' => array(
                'module' => 'sitepagereview',
                'controller' => 'index',
                'action' => 'create'
            ),
            'reqs' => array(
                'page_id' => '\d+'
            )
        ),
        'sitepagereview_detail_view' => array(
            'route' => $routeStart.'/:owner_id/:review_id/:slug/*',
            'defaults' => array(
                'module' => 'sitepagereview',
                'controller' => 'index',
                'action' => 'view',
                'slug' => '',
            ),
            'reqs' => array(
                'owner_id' => '\d+',
                'review_id' => '\d+'
            )
        ),
        'sitepagereview_edit' => array(
            'route' => $routeStart.'/edit/:review_id/:page_id/*',
            'defaults' => array(
                'module' => 'sitepagereview',
                'controller' => 'index',
                'action' => 'edit'
            )
        ),
        'sitepagereview_delete' => array(
            'route' => $routeStart.'/delete/:review_id/:page_id/*',
            'defaults' => array(
                'module' => 'sitepagereview',
                'controller' => 'index',
                'action' => 'delete'
            ),
            'reqs' => array(
                'review_id' => '\d+',
                'page_id' => '\d+'
            )
        ),

         'sitepagereview_home' => array(
            'route' => $routeStart.'/home/*',
            'defaults' => array(
                'module' => 'sitepagereview',
                'controller' => 'index',
                'action' => 'home',
            ),
        ), 

        'sitepagereview_browse' => array(
            'route' => $routeStart.'/browse/*',
            'defaults' => array(
                'module' => 'sitepagereview',
                'controller' => 'index',
                'action' => 'browse',
            ),
        ),
    ),
);
?>