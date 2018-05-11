<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$isActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.isActivate', 0);
if (empty($isActive)) {
  return;
}

$categories = Engine_Api::_()->getDbTable('categories', 'sitepage')->getCategories();
if (count($categories) != 0) {
  $categories_prepared[0] = "";
  foreach ($categories as $category) {
    $categories_prepared[$category->category_id] = $category->category_name;
  }
}

$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(

	array(
			'title' => $view->translate('Page Profile Badge'),
			'description' => $view->translate('Displays the badge, which has been assigned to a Page, on it\'s profile.'),
			'category' => $view->translate('Page Profile'),
			'type' => 'widget',
			'name' => 'sitepagebadge.sitemobile-badge-sitepagebadge',
			'defaultParams' => array(
					'title' => $view->translate('Badge'),
					'titleCount' => true,
			),
	),
	array(
			'title' => $view->translate('Page Badges'),
			'description' => $view->translate('Displays a list of all the pages badge on site. This widget should be placed on the Pages badge page.'),
			'category' => $view->translate('Pages'),
			'type' => 'widget',
			'name' => 'sitepagebadge.sitepage-badge',
			'defaultParams' => array(
					'title' => '',
					'titleCount' => true,
			),
			'adminForm' => array(
					'elements' => array(
							array(
									'Text',
									'itemCount',
									array(
											'label' => $view->translate('Count'),
											'description' => $view->translate('(number of badges to show)'),
											'value' => 10,
											'validators' => array(
												array('Int', true),
												array('GreaterThan', true, array(0)),
											),
									),
							),
              array(
									'Select',
									'category_id',
									array(
											'label' => $view->translate('Category'),
											'multiOptions' => $categories_prepared,
									)
						  ),
					),
			),
	),
)
?>