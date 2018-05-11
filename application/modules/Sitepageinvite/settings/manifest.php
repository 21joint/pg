<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$routeStart = "pageinvites";
$module=null;$controller=null;$action=null;$getURL = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module = $request->getModuleName(); // Return the current module name.
  $action = $request->getActionName();
  $controller = $request->getControllerName();
  $getURL = $request->getRequestUri();
}
if (empty($request) || !($module == "default" && ( strpos( $getURL, '/install') !== false))) {
  $routeStart = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageinvite.manifestUrl', "page-invites");
}
return array(
    // Package -------------------------------------------------------------------
    'package' => array(
        'type' => 'module',
        'name' => 'sitepageinvite',
        'version' => '4.9.4p1',
        'path' => 'application/modules/Sitepageinvite',
        'repository' => 'null',
        'title' => 'Directory / Pages - Inviter Extension',
        'description' => 'Directory / Pages - Inviter Extension',
      'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'date' => 'Thrusday, 05 May 2011 18:33:08 +0000',
        'copyright' => 'Copyright 2010-2011 BigStep Technologies Pvt. Ltd.',
        'actions' => array(
            'install',
            'upgrade',
            'refresh',
            'enable',
            'disable',
        ),
        'callback' => array(
            'path' => 'application/modules/Sitepageinvite/settings/install.php',
            'class' => 'Sitepageinvite_Installer',
        ),
        'directories' => array(
            'application/modules/Sitepageinvite',
        ),
        'files' => array(
            'application/languages/en/sitepageinvite.csv',
        ),
    ),
    // Routes --------------------------------------------------------------------
    'routes' => array(
        'sitepageinvite_invite' => array(
            'route' => $routeStart.'/invitefriends/:user_id/:sitepage_id/',
            'defaults' => array(
                'module' => 'sitepageinvite',
                'controller' => 'index',
                'action' => 'friendspageinvite'
            ),
            'reqs' => array(
                'user_id' => '\d+',
                'sitepage_id' => '\d+'
            )
        ),
        'sitepageinvite_invitefriends' => array(
            'route' => $routeStart.'/inviteusers/:user_id/:sitepage_id/',
            'defaults' => array(
                'module' => 'sitepageinvite',
                'controller' => 'index',
                'action' => 'inviteusers'
            ),
            'reqs' => array(
                'user_id' => '\d+',
                'sitepage_id' => '\d+'
            )
        ),
        'sitepageinvite_app_config' => array(
            'route' => 'admin/sitepageinvite/global/appconfigs',
            'defaults' => array(
                'module' => 'sitepageinvite',
                'controller' => 'admin-global',
                'action' => 'appconfigs'
            )
        ),
        'sitepageinvite_global_global' => array(
            'route' => 'admin/sitepageinvite/global/global',
            'defaults' => array(
                'module' => 'sitepageinvite',
                'controller' => 'admin-global',
                'action' => 'global'
            )
        )
    )
);
?>
