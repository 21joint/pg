<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$routeStart = "pagebadges";
$routeStartP = "pageitems";
$module=null;$controller=null;$action=null;$getURL = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module = $request->getModuleName(); // Return the current module name.
  $action = $request->getActionName();
  $controller = $request->getControllerName();
  $getURL = $request->getRequestUri();
}
if (empty($request) || !($module == "default" && ( strpos( $getURL, '/install') !== false))) {
	 $routeStartP = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.manifestUrlP', "pageitems");
  $routeStart = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.manifestUrl', "page-badges");
}
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitepagebadge',
        'version' => '4.9.4p1',
        'path' => 'application/modules/Sitepagebadge',
        'title' => 'Directory / Pages - Badges Extension',
        'description' => 'Directory / Pages - Badges Extension',
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
            'path' => 'application/modules/Sitepagebadge/settings/install.php',
            'class' => 'Sitepagebadge_Installer',
        ),
        'directories' =>
        array(
            0 => 'application/modules/Sitepagebadge',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sitepagebadge.csv',
        ),
    ),
    // Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onUserDeleteBefore',
            'resource' => 'Sitepagebadge_Plugin_Core',
        ),
    ),
    // Items ---------------------------------------------------------------------
    'items' => array(
        'sitepagebadge_badge',
        'sitepagebadge_badgerequest'
    ),
    'routes' => array(
        'sitepagebadge_show' => array(
            'route' => $routeStart,
            'defaults' => array(
                'module' => 'sitepagebadge',
                'controller' => 'index',
                'action' => 'showbadges'
            )
        ),
        'sitepagebadge_request' => array(
            'route' => $routeStartP.'/badgerequest/:page_id/*',
            'defaults' => array(
                'module' => 'sitepagebadge',
                'controller' => 'index',
                'action' => 'badgerequest'
            )
        ),
				'sitepagebadge_remove' => array(
					'route' => $routeStartP.'/removebadge/:page_id',
					'defaults' => array(
						'module' => 'sitepagebadge',
						'controller' => 'index',
						'action' => 'remove'
					),
					'reqs' => array(
						'page_id' => '\d+'
					)
				),
    )
);
?>