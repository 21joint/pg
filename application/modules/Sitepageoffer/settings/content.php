<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$isActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.isActivate', 0);
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

$popularity_options = array(
		'view_count' => 'Most Viewed',
		'like_count' => 'Most Liked',
		'comment_count' => 'Most Commented',
		'popular' => 'Most Claimed',
);

$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(
    array(
        'title' => 'Page Profile Offers',
        'description' => 'This widget forms the Offers tab on the Page Profile and displays the offers of the Page. It should be placed in the Tabbed Blocks area of the Page Profile.',
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepageoffer.profile-sitepageoffers',
        'defaultParams' => array(
            'title' => 'Offers',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => 'Latest Page Offers',
        'description' => 'Displays the latest Page Offers that have been created. You can choose the number of entries to be shown.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.sitepage-latestoffer',
        'defaultParams' => array(
            'title' => 'Latest Page Offers',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of offers to show)',
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
        'title' => 'Hot Page Offers',
        'description' => 'Displays Page Offers that have been marked as Hot. You can choose the number of entries to be shown',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.sitepage-hotoffer',
        'defaultParams' => array(
            'title' => 'Hot Page Offers',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of offers to show)',
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
        'title' => 'Page Offers',
        'description' => 'Displays the list of Offers from Pages created on your community. This widget should be placed in the widgetized Page Offers page. Results from the Search Page Offers form are also shown here.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.sitepage-offer',
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
                        'description' => '(number of offers to show)',
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
        'title' => 'Available Offers',
        'description' => 'Displays the Page Offers based on their ending dates, in 3 tabs: ‘This Week’, ‘This Month’ and ‘Overall’. Users can see more offers right within that widget. You can choose the number of entries to be shown.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.sitepage-dateoffer',
        'defaultParams' => array(
            'title' => 'Available Offers',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of offers to show)',
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
    ),

		array(
			'title' => 'Search Page Offers form',
			'description' => 'Displays the form for searching Page Offers on the basis of various filters. You can edit the fields to be available in this form.',
			'category' => 'Pages',
			'type' => 'widget',
			'name' => 'sitepageoffer.search-sitepageoffer',
			'defaultParams' => array(
					'title' => '',
          'search_column' => array("0" => "1", "1" => "2", "2" => "3", "3" => "4"),
					'titleCount' => true,

			),
			'adminForm' => array(
              'elements' => array(
							array(
									'MultiCheckbox',
									'search_column',
									array(
											'label' => 'Choose the fields that you want to be available in the Search Page Offers form widget.',
											'multiOptions' => array("1" => "Browse By", "2" => "Page Title", "3" => "Offer Title","4" => "Page Category","5" => "Location"),
									),
							),
					),
			)
    ),

     array(
        'title' => 'Sponsored Offers',
        'description' => 'Displays the Offers from Paid Pages. You can choose the number of entries to be shown.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.sitepage-sponsoredoffer',
        'defaultParams' => array(
            'title' => 'Sponsored Offers',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of offers to show)',
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
				'title' => 'Page Offer View',
				'description' => "This widget should be placed on the Page Offer View Page.",
				'category' => 'Pages',
				'type' => 'widget',
				'name' => 'sitepageoffer.offer-content',
				'defaultParams' => array(
						'title' => '',
						'titleCount' => true,
				),
        'adminForm' => array(
            'elements' => array(
                array(
									'MultiCheckbox',
									'showLinks',
									array(
											'label' => 'Show Action Links (Note: "Suggest to Friends" link having dependency on our "Suggestions" plugin.)',
											'multiOptions' => array(
                          'add' => "Add an Offer",
                          'edit' => "Edit Offer",
                          'delete' => "Delete Offer",
                          'featured' => "Make Featured",
                          'dayOffer' => "Make Offer of the Day",
                          'suggest' => "Suggest to Friends",
                          'print' => "Print Offer",
                          'share' => "Site Share",
                          'report' => "Report",
                      ),
									)
						    ),
                array(
                    'MultiCheckbox',
                    'showContent',
                    array(
                        'label' => 'Select the information options that you want to be available in this block.',
                        'multiOptions' => array("postedBy" => "Posted By", "viewCount" => "Views", "likeCount" => "Likes", "commentCount" => "Comments"),
                    ),
                ),           
                array(
                    'Radio',
                    'commentEnabled',
                    array(
                        'label' => $view->translate('Do you want to show user comments and enable user to post comment or not?'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => 1,
                    ),
                ),                
            ),
        ),        
		),

		array(
				'title' => 'Popular / Viewed / Liked Page Offers',
				'description' => 'Displays Page Offers based on the Popularity Criteria and other settings that you choose for this widget. You can place this widget multiple times on a page with different popularity criterion chosen for each placement.',
				'category' => 'Pages',
				'type' => 'widget',
				'autoEdit' => true,
				'name' => 'sitepageoffer.offers-sitepageoffers',
				'defaultParams' => array(
						'title' => 'Offers',
						'titleCount' => true,
				),
				'adminForm' => array(
						'elements' => array(
								array(
										'Text',
										'itemCount',
										array(
											'label' => 'Count',
											'description' => '(number of Offers to show)',
											'value' => 3,
										)
								),
								array(
										'Select',
										'popularity',
										array(
												'label' => 'Popularity Criteria',
												'multiOptions' => $popularity_options,
												'value' => 'view_count',
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
        'title' => 'Page’s Offer of the Day',
        'description' => 'Displays the Offer of the Day as selected by the Admin from the widget settings section of Directory / Pages - Offers Extension.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.offer-of-the-day',
        'defaultParams' => array(
            'title' => 'Offer of the Day'
        ),
    ),

    array(
        'title' => 'Browse Offers',
        'description' => 'Displays the link to view Page’s Offers Browse page.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.sitepageofferlist-link',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    ),

    array(
        'title' => 'Page’s Hot Offers Slideshow',
        'description' => 'Displays hot offers in an attractive slideshow. You can set the count of the number of offers to show in this widget. If the total number of offers selected as hot are more than that count, then the offers to be displayed will be sequentially picked up.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.hot-offers-slideshow',
        'isPaginated' => true,
        'defaultParams' => array(
            'title' => 'Hot Offers',
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
        'title' => 'Page’s Hot Offers Carousel',
        'description' => 'This widget contains an attractive AJAX based carousel, showcasing the hot offers on the site. Multiple settings of this widget makes it highly configurable.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.hot-offers-carousel',
        'defaultParams' => array(
            'title' => 'Hot Offers',
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
                        'label' => 'Offers in a Row',
                        'description' => '(number of offers to show in one row. Note: This field is applicable only when you have selected ‘Horizontal’ in ‘Carousel Type’ field.)',
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
            ),
        ),
    ),

    array(
        'title' => 'Page’s Ajax based Tabbed widget for Offers',
        'description' => 'Displays the Upcoming, Most Liked, Most Viewed, Most Commented, Hot and Featured Offers in separate AJAX based tabs. Settings for this widget are available in the Tabbed Offers Widget section of Directory / Pages - Offers Extension.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepageoffer.list-offers-tabs-view',
        'defaultParams' => array(
            'title' => 'Offers',
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
                  'Radio',
                  'showViewMore',
                  array(
                      'label' => 'Show "View More" link',
                      'multiOptions' => array(
                          '1' => 'Yes',
                          '0' => 'No',
                      ),
                      'value' => 1,
                  )
              ),
              array(
									'Select',
									'category_id',
									array(
											'label' => 'Category',
											'multiOptions' => $categories_prepared,
									)
							)
            ),
        ),
    ),

    array(
    'title' => 'Top Creators : Page Offers',
    'description' => 'Displays the Pages which have the most number of Page Offers added in them. Motivates Page Admins to add more content on your website.',
    'category' => 'Pages',
    'type' => 'widget',
    'name' => 'sitepageoffer.topcreators-sitepageoffer',
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
      'subject' => 'sitepageoffer',
    ),
  ),

	)

?>