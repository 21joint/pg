<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<?php 
$routeStartP = "credits";
$routeStartS = "credit";
$module=null;$controller=null;$action=null;$getURL = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module = $request->getModuleName(); // Return the current module name.
  $action = $request->getActionName();
  $controller = $request->getControllerName();
  $getURL = $request->getRequestUri();
}
if (empty($request) || !($module == "default" && ( strpos( $getURL, '/install') !== false))) {
  $routeStartP = Engine_Api::_()->getApi('settings', 'core')->getSetting('credit.manifestUrlP', "credits");
  $routeStartS = Engine_Api::_()->getApi('settings', 'core')->getSetting('credit.manifestUrlS', "credit");
}

return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'sitecredit',
    'version' => '4.9.4p5',
    'path' => 'application/modules/Sitecredit',
    'title' => 'Credits, Reward Points and Virtual Currency - User Engagement',
    'description' => 'This plugin enables users to earn credits / reward points by performing various activities: content creation, inviting friends to join the community using referral sign-ups, liking a post, by commenting, etc. on SocialEngine community websites.',
    'author' => 'SocialEngineAddOns',
    'callback' => 
    array (
      'path' => 'application/modules/Sitecredit/settings/install.php',
      'class' => 'Sitecredit_Installer',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' => 
    array (
      0 => 'application/modules/Sitecredit',
    ),
    'files' => 
    array (
      0 => 'application/languages/en/sitecredit.csv',
    ),
  ),
  'items' => array(
    'activitycredit','offer','bonuses','credit','badge','upgraderequest','sitecredit_order','sitecredit_gateway','sitecredit_level','modulelist'
      ),
    // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onItemDeleteBefore',
      'resource' => 'Sitecredit_Plugin_Core',
    ),
        array(
      'event' => 'onActivityActionCreateAfter',
      'resource' => 'Sitecredit_Plugin_Core',
    ),
        array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Sitecredit_Plugin_Core',
    ),    
        array(
        'event' => 'onUserCreateAfter',
        'resource' => 'Sitecredit_Plugin_Core',
    ),
      array(
        'event' => 'onUserLoginAfter',
        'resource' => 'Sitecredit_Plugin_Core',
    ),  
    array(
      'event' => 'onRenderLayoutDefault',
      'resource' => 'Sitecredit_Plugin_Core'
    ),  
        
  ),
    // Routes --------------------------------------------------------------------
  'routes' => array(
    // Public

    'credit_general' => array(
      'route' => $routeStartP.'/:action/*',
      'defaults' => array(
        'module' => 'sitecredit',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'action' => '(index|transaction|earncredit|success|signup|print-invoice|view-detail|view-invoice)',
      ),
    ),
        'credit_payment' => array(
      'route' => $routeStartP.'/payment/:action/*',
      'defaults' => array(
        'module' => 'sitecredit',
        'controller' => 'payment',
        'action' => 'process',
      ),
      'reqs' => array(
        'action' => '(process)',
      ),
    ),
  ),
); ?>