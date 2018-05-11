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
        'name' => 'sitepagemusic.profile-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Music',
        ),
    ),
    array(
        'title' => 'Page Profile Most Popular Playlists',
        'description' => "Displays list of a Page's most played playlists. This widget should be placed on the Page Profile.",
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagemusic.popular-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Most Popular Playlists',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of playlist to show)',
                        'value' => 3,
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
        'title' => 'Page Profile Most Commented Playlists',
        'description' => "Displays list of Page's most commented playlists. This widget should be placed on the Page Profile.",
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagemusic.comment-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Most Commented Playlists',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of playlist to show)',
                        'value' => 3,
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
        'title' => 'Page Profile Most Recent Playlists',
        'description' => "Displays list of a Page's most recent playlists. This widget should be placed on the Page Profile.",
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagemusic.recent-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Most Recent Playlists',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of playlist to show)',
                        'value' => 3,
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
        'title' => 'Page Profile Most Liked Playlists',
        'description' => "Displays list of Page's most liked playlists. This widget should be placed on the Page Profile.",
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagemusic.like-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Most Liked Playlists',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of playlist to show)',
                        'value' => 3,
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
        'title' => 'Recent Page Playlists',
        'description' => 'Displays list of recent playlists of pages on the site.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.homerecent-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Recent Page Playlists'
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of playlists to show)',
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
        'title' => 'Popular Page Playlists',
        'description' => 'Displays the Most Popular Page Playlists. You can choose the number of entries to be shown.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.homepopular-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Popular Page Playlists'
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of playlists to show)',
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
        'title' => 'Page Profile Player',
        'description' => 'Displays a music player that plays the playlist the Page Admin has selected to play on their Page Profile.',
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagemusic.profile-player',
    ),

    array(
			'title' => 'Search Page Music form',
			'description' => 'Displays the form for searching Page Music on the basis of various filters. You can edit the fields to be available in this form.',
			'category' => 'Pages',
			'type' => 'widget',
			'name' => 'sitepagemusic.search-sitepagemusic',
			'defaultParams' => array(
					'title' => '',
          'search_column' => array("0" => "1", "1" => "2", "2" => "3", "3" => "4", "4" => '5'),
					'titleCount' => true,

			),
			'adminForm' => array(
              'elements' => array(
							array(
									'MultiCheckbox',
									'search_column',
									array(
											'label' => 'Choose the fields that you want to be available in the Search Page Music form widget.',
											'multiOptions' => array("1" => "Show","2" => "Browse By", "3" => "Page Title", "4" => "Music Title", "5" => "Page Category"),
									),
							),
					),
			)
    ),

    array(
        'title' => 'Page Music',
        'description' => 'Displays the list of Music from Pages created on your community. This widget should be placed in the widgetized Page Music page. Results from the Search Page Music form are also shown here.',
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
                        'value' => 20,
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
        'title' => 'Most Commented Page Playlists',
        'description' => "Displays the Most Commented Page Playlists. You can choose the number of entries to be shown.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.homecomment-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Most Commented Playlists',
            'titleCount' => true,array(
        'title' => 'Featured Playlists',
        'description' => "Displays Featured Page Playlists. You can mark Page Playlists as Featured from the “Manage Page Playlists” section in the Admin Panel of this extension. You can choose the number of entries to be shown.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageplaylist.homefeaturelist-sitepageplaylists',
        'defaultParams' => array(
            'title' => 'Featured Playlists',
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
   
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of playlists to show)',
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
        'title' => 'Sponsored Playlists',
        'description' => "Displays the Playlists from Paid Pages. You can choose the number of entries to be shown.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.sitepage-sponsoredmusic',
        'defaultParams' => array(
            'title' => 'Sponsored Playlists',
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
        'title' => 'Most Liked Page Playlists',
        'description' => "Displays the Most Liked Page Playlists. You can choose the number of entries to be shown.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.homelike-sitepagemusic',
        'defaultParams' => array(
            'title' => 'Most Liked Playlists',
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
  array(
        'title' => 'Featured Playlists',
        'description' => "Displays Featured Page Playlists. You can mark Page Playlists as Featured from the “Manage Page Playlists” section in the Admin Panel of this extension. You can choose the number of entries to be shown.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.homefeaturelist-sitepagemusics',
        'defaultParams' => array(
            'title' => 'Featured Playlists',
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
        'title' => 'Page’s Featured Playlists Slideshow',
        'description' => 'Displays featured playlists in an attractive slideshow. You can set the count of the number of playlists to show in this widget. If the total number of playlists featured are more than that count, then the playlists to be displayed will be sequentially picked up.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.featured-musics-slideshow',
        'isPaginated' => true,
        'defaultParams' => array(
            'title' => 'Featured Playlists',
            'itemCountPerPage' => 10,
        ),
        'adminForm' => array(
					'elements' => array(
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
        'title' => 'Page’s Featured Playlists Carousel',
        'description' => 'This widget contains an attractive AJAX based carousel, showcasing the featured playlists on the site. Multiple settings of this widget makes it highly configurable.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.featured-musics-carousel',
        'defaultParams' => array(
            'title' => 'Featured Playlists',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'vertical',
                    array(
                        'label' => 'Carousel Type',
                        'multiOptions' => array(
                            '0' => 'Horizontal',
                            '1' => 'Vertical',
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'Text',
                    'inOneRow',
                    array(
                        'label' => 'Playlists in a Row',
                        'description' => '(number of playlists to show in one row. Note: This field is applicable only when you have selected ‘Horizontal’ in ‘Carousel Type’ field.)',
                        'value' => 3,
                    )
                ),
                array(
                    'Text',
                    'noOfRow',
                    array(
                        'label' => 'Rows',
                        'description' => '(number of rows in one view)',
                        'value' => 2,
                    )
                ),
                array(
                    'Text',
                    'interval',
                    array(
                        'label' => 'Speed',
                        'description' => '(transition interval between two slides in millisecs)',
                        'value' => 250,
                    )
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
        'title' => 'Page’s Ajax based Tabbed widget for Playlists',
        'description' => 'Displays the Recent, Most Liked, Most Viewed, Most Commented, Featured and Random Playlists in separate AJAX based tabs. Settings for this widget are available in the Widget Settings section of Directory / Pages - Music Extension.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.list-musics-tabs-view',
        'defaultParams' => array(
            'title' => 'Playlists',
            'margin_photo'=>12,
            'showViewMore'=>1
        ),
         'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'margin_photo',
                    array(
                        'label' => 'Horizontal Margin between Elements',
                        'description' => '(Horizontal margin in px between consecutive elements in this widget. You might want to change this value if the content of this widget is not coming properly on your site because of the column width in your theme.)',
                        'value' => 12,
                    )
                ),
                array(
									'Select',
									'category_id',
									array(
											'label' => 'Category',
											'multiOptions' => $categories_prepared,
									)
							  ),
                 array(
                  'Radio',
                  'showViewMore',
                  array(
                      'label' => 'Show "View More" link',
                      'multiOptions' => array(
                          '1' => 'Yes',
                          '0' => 'No',
                      ),
                  )
              )
            ),
        ),
    ),

    array(
        'title' => 'Browse Playlists',
        'description' => 'Displays the link to view Page’s Playlists Browse page.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.sitepagemusiclist-link',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    ),

    array(
        'title' => 'Page’s Playlist of the Day',
        'description' => 'Displays the Playlist of the Day as selected by the Admin from the widget settings section of Directory / Pages - Musics Extension.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagemusic.music-of-the-day',
        'defaultParams' => array(
            'title' => 'Playlist of the Day'
        ),
    ),

    array(
    'title' => 'Top Creators : Page Music',
    'description' => 'Displays the Pages which have the most number of Page Music added in them. Motivates Page Admins to add more content on your website.',
    'category' => 'Pages',
    'type' => 'widget',
    'name' => 'sitepagemusic.topcreators-sitepagemusic',
   // 'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Top Creators',
    ),
     'adminForm' => array(
					'elements' => array(
						array(
								'Text',
								'itemCount',
								array(
										'label' => 'Count',
										'description' => '(number of elements to show)',
										'value' => 5,
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
    'requirements' => array(
      'subject' => 'sitepagemusic',
    ),
  ),
)
?>