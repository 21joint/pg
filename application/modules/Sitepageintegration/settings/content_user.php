<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content_user.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
// $mixSettingsResults = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration'
//         )->getIntegrationItems();
// $mixSettingsItems = $mixSettingsResults;
// 
// $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
// $subject = Engine_Api::_()->core()->getSubject('sitepage_page');
// foreach ($mixSettingsItems as $modNameKey => $modNameValue) {
//   if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
//     foreach ($mixSettingsItems as $modNameKey => $modNameValue) {
//       if (!Engine_Api::_()->sitepage()->allowPackageContent($subject->package_id, "modules", $modNameKey)) {
//         unset($mixSettingsItems[$modNameKey]);
//       }
//     }
//   } else {
//     foreach ($mixSettingsItems as $modNameKey => $modNameValue) {
//       $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($subject, $modNameKey);
//       if (empty($isPageOwnerAllow)) {
//         unset($mixSettingsItems[$modNameKey]);
//       }
//     }
//   }
// }
// $contentWidgetName = array();
// if (!empty($mixSettingsItems)) {
//   $contentWidgetName[] = array(
//       'title' => $view->translate('Page Profile intregration (selected content)'),
//       'description' => $view->translate('Displays list of integrated module on Page Profile for the content selected. This widget should be placed in the Tab Container on Page Profile.'),
//       'category' => $view->translate('Page Profile'),
//       'type' => 'widget',
//       'autoEdit' => true,
//       'name' => 'sitepageintegration.profile-items',
//       'defaultParams' => array(
//           'title' => $view->translate('Page Profile integration'),
//       ),
//       'adminForm' => array(
//           'elements' => array(
//               array(
//                   'select',
//                   'resource_type',
//                   array(
//                       'label' => $view->translate('Select the content'),
//                       'multiOptions' => $mixSettingsItems,
//                   )
//               ),
//           ),
//       )
//   );
// }
// return $contentWidgetName;