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
			'title' => 'Most Popular Badges',
			'description' => 'Displays list of most popular badges for pages on the site.',
			'category' => 'Pages',
			'type' => 'widget',
			'name' => 'sitepagebadge.popular-sitepagebadge',
			'defaultParams' => array(
					'title' => 'Most Popular Badges',
					'titleCount' => true,
			),
				'adminForm' => array(
					'elements' => array(
							array(
									'Text',
									'itemCount',
									array(
											'label' => 'Count',
											'description' => '(number of badges to show)',
											'value' => 3,
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
											'label' => 'Category',
											'multiOptions' => $categories_prepared,
									)
						  ),
					),
			),
	),
	array(
			'title' => 'Page Profile Badge',
			'description' => 'Displays the badge, which has been assigned to a Page, on it\'s profile.',
			'category' => 'Page Profile',
			'type' => 'widget',
			'name' => 'sitepagebadge.badge-sitepagebadge',
			'defaultParams' => array(
					'title' => 'Badge',
					'titleCount' => true,
			),
	),

	array(
			'title' => 'Page Badges',
			'description' => 'Displays a list of all the pages badge on site. This widget should be placed on the  Pages badge page.',
			'category' => 'Pages',
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
											'label' => 'Count',
											'description' => '(number of badges to show)',
											'value' => 20,
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
											'label' => 'Category',
											'multiOptions' => $categories_prepared,
									)
						  ),
					),
			),
	),
)
?>