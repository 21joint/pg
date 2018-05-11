<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Content.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$isActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.isActivate', 0);
if ( empty($isActive) ) {
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
        'title' => 'Page Profile Music',
        'description' => 'This widget forms the Music tab on the Page Profile and displays the music of the Page. It should be placed in the Tabbed Blocks area of the Page Profile.',
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagemusic.sitemobile-profile-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Music',
        ),
    ), 
    array(
        'title' => 'Page Music',
        'description' => 'Displays the list of Music from Pages created on your community. This widget should be placed in the widgetized Page Music Browse page. Results from the Search Page Music form are also shown here.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.sitepage-music',
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
                        'description' => '(number of playlists to show)',
                        'value' => 10,
												'validators' => array(
													array('Int', true),
													array('GreaterThan', true, array(0)),
												),
                    ),
                ),
            ),
        ),
    ),
    array(
			'title' => 'Page Music View',
			'description' => "This widget should be placed on the Page Music View Page.",
      'category' => 'Pages',
			'type' => 'widget',
			'name' => 'sitepagemusic.music-content',
			'defaultParams' => array(
					'title' => '',
					'titleCount' => true,
			),
	),
)
?>