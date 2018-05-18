<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$routeStart = "pageform";
$module=null;$controller=null;$action=null;$getURL = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module = $request->getModuleName(); // Return the current module name.
  $action = $request->getActionName();
  $controller = $request->getControllerName();
  $getURL = $request->getRequestUri();
}
if (empty($request) || !($module == "default" && ( strpos( $getURL, '/install') !== false))) {
  $routeStart = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.manifestUrl', "page-form");
}
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitepageform',
        'version' => '4.9.4p1',
        'path' => 'application/modules/Sitepageform',
        'title' => 'Directory / Pages - Form Extension',
        'description' => 'Directory / Pages - Form Extension',
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
        'callback' => array(
            'path' => 'application/modules/Sitepageform/settings/install.php',
            'class' => 'Sitepageform_Installer',
        ),
        'directories' =>
        array(
            0 => 'application/modules/Sitepageform',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sitepageform.csv',
        ),
    ),
    // Items ---------------------------------------------------------------------
    'items' => array(
        'sitepageform',
    ),
		// COMPATIBLE WITH MOBILE / TABLET PLUGIN --------------------------------------------------------------------
	 'sitemobile_compatible' =>true,
// Route--------------------------------------------------------------------
    'routes' => array(
        'sitepageform_general' => array(
            'route' => $routeStart.'/:action/*',
            'defaults' => array(
                'module' => 'sitepageform',
                'controller' => 'siteform',
                'action' => 'index',
            ),
            'reqs' => array(
                'action' => '(home|index|manage|create|packages)',
            ),
        ),
        'sitepageform_disable' => array(
            'route' => 'pageform/admin-manage/disableform/:id/*',
            'defaults' => array(
                'module' => 'sitepageform',
                'controller' => 'admin-manage',
                'action' => 'disable-form'
            ),
            'reqs' => array(
                'id' => '\d+'
            )
        ),
    ),
);
?>