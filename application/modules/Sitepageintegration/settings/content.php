<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;


$sitebusinessEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusiness');
$sitereviewEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereview');
$sitereviewlistingtypeEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewlistingtype');
$listEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('list');
$title = '';
$description = '';
if (!empty($sitebusinessEnabled) && (!empty($sitereviewEnabled) || !empty($listEnabled))) {
    $title = "Listing / Business Profile Linked Pages (selected content)";
    $description = "This widget displays listings / businesses added from a selected content module, which you can choose from the settings of this widget, to the Page currently being viewed. You can place this widget multiple times on Page Profile page with different content modules chosen for each placement. It is recommended to place this widget in the Tabbed Blocks area of the Page Profile page.";
} elseif (!empty($sitereviewEnabled) || !empty($listEnabled)) {
    $title = "Listing Profile Linked Pages (selected content)";
    $description = "This widget displays listings added from a selected content module, which you can choose from the settings of this widget, to the Page currently being viewed. You can place this widget multiple times on Page Profile page with different content modules chosen for each placement. It is recommended to place this widget in the Tabbed Blocks area of the Page Profile page.";
} elseif ($sitebusinessEnabled) {
    $title = "Business Profile Linked Pages (selected content)";
    $description = "This widget displays businesses added from a selected content module, which you can choose from the settings of this widget, to the Page currently being viewed. You can place this widget multiple times on Page Profile page with different content modules chosen for each placement. It is recommended to place this widget in the Tabbed Blocks area of the Page Profile page.";
}

if(!empty($description))
$contentWidgetNames[] = array(
    'title' => 'Page Profile Listings (selected content)',
    'description' => "$description",
    'category' => 'Page Profile',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sitepageintegration.profile-items',
    'adminForm' => 'Sitepageintegration_Form_Admin_Integration',
    'defaultParams' => array(
        'title' => 'Page Profile Integration',
    ),
);
if(!empty($title))
$contentWidgetNames[] = array(
    'title' => "$title",
    'description' => 'This widget displays all the Pages to which the selected content module is added, as chosen by you from the settings of this widget. It is recommended to place this widget in the Tabbed Blocks area of the selected content module’s Profile page.',
    'category' => 'Page Profile',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sitepageintegration.mixprofile-items',
    'adminForm' => 'Sitepageintegration_Form_Admin_ProfileIntegration',
    'defaultParams' => array(
        'title' => 'Related Pages',
    ),
);
return $contentWidgetNames;
