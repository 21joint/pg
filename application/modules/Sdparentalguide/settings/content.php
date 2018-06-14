<?php

$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
$showOptions = array(
    'Radio',
    'topmember',
    array(
        'label' => 'Popularity criteria on the basis of which members will be displayed.',
        'multiOptions' => array(
            'activities' => 'Maximum Performed Activities',
            'earned' => 'Maximum Earned Credits',
            'current' => 'Maximum Current Credits',
        ),
        'value' => 'activities',
    )
);
$count = array(
    'text', 'count',
    array(
    'label' => 'Number of top active members to be shown (Note: Maximum 10 will be shown).',
        'value' => '',
    ));


//GET LISTING TYPE TABLE
$listingTypeTable = Engine_Api::_()->getDbTable('listingtypes', 'sitereview');

//GET LISTING TYPE COUNT
$listingTypeCount = Engine_Api::_()->sdparentalguide()->getListingTypesArray();

if ($listingTypeCount > 1) {

  $listingTypes1 = $listingTypes2 = $listingTypes = array();

  $listingTypes1 = Engine_Api::_()->sdparentalguide()->getListingTypesArray();
  $listingTypes1['9999999'] = $view->translate("Based on Preferences");

  $listingTypes2['-1'] = $view->translate('All Types');
  $listingTypes2 = $listingTypes2 + $listingTypes1;
  
  $listingTypeElement1 = array(
      'Select',
      'listingtype_id',
      array(
          'label' => $view->translate('Listing Type'),
          'multiOptions' => $listingTypes1,
      )
  );

  $listingTypeElement2 = array(
      'Select',
      'listingtype_id',
      array(
          'label' => $view->translate('Listing Type'),
          'multiOptions' => $listingTypes2,
      )
  );

  $listingTypeCategoryElement = array(
      'multiselect',
      'listingtype_id',
      array(
          'label' => $view->translate('Listing Type'),
          'multiOptions' => $listingTypes2,
          'value' => 0,
      )
  );
} else {
  $listingTypeElement1 = array(
      'Hidden',
      'listingtype_id',
      array(
          'label' => $view->translate('Listing Type'),
          'value' => 1,
          'order' => 1041
      )
  );

  $listingTypeElement2 = array(
      'Hidden',
      'listingtype_id',
      array(
          'label' => $view->translate('Listing Type'),
          'value' => 1,
          'order' => 1042
      )
  );

  $listingTypeCategoryElement = array(
      'Hidden',
      'listingtype_id',
      array(
          'label' => $view->translate('Listing Type'),
          'value' => 1,
          'order' => 1043
      )
  );
}

$categoryElement = array(
    'Select',
    'category_id',
    array(
        'RegisterInArrayValidator' => false,
        'decorators' => array(array('ViewScript', array(
                    'viewScript' => 'application/modules/Sdparentalguide/views/scripts/_category.tpl',
                    'class' => 'form element')))
        ));



$hiddenCatElement = array(
    'Text',
    'hidden_category_id',
    array(
        ));

$hiddenSubCatElement = array(
    'Text',
    'hidden_subcategory_id',
    array(
        ));

$hiddenSubSubCatElement = array(
    'Text',
    'hidden_subsubcategory_id',
    array(
        ));

$ratingTypeElement = array(
    'Select',
    'ratingType',
    array(
        'label' => $view->translate('Rating Type'),
        'multiOptions' => array('rating_avg' => $view->translate('Average Ratings'), 'rating_editor' => $view->translate('Only Editor Ratings'), 'rating_users' => $view->translate('Only User Ratings'), 'rating_both' => $view->translate('Both User and Editor Ratings')),
    )
);

$featuredSponsoredElement = array(
    'Select',
    'fea_spo',
    array(
        'label' => $view->translate('Show Listings'),
        'multiOptions' => array(
            '' => '',
            'newlabel' => $view->translate('New Only'),
            'featured' => $view->translate('Featured Only'),
            'sponsored' => $view->translate('Sponsored Only'),
            'fea_spo' => $view->translate('Either Featured or Sponsored'),
            'createdbyfriends' => $view->translate('Created By Friends'),
            'thatIcreated' => $view->translate('That I Created'),
            'thatIliked' => $view->translate('That I Liked'),
        ),
        'value' => '',
    )
);

$approvedListingElement = array(
    'Select',
    'approved_listing',
    array(
        'label' => $view->translate('Approved Listing - will render listings whether or not they have been approved.'),
        'multiOptions' => array(
            '' => '',
            'approved' => $view->translate('Only Show Approved'),
            'non_approved' => $view->translate('Only Show Non-Approved'),
            'all' => $view->translate('Show All'),
        ),
        'value' => '',
    ),
);

$approvedListingElement = array(
    'Select',
    'approved_listing',
    array(
        'label' => $view->translate('Approved Listing - will render listings whether or not they have been approved.'),
        'multiOptions' => array(
            '' => '',
            'approved' => $view->translate('Only Show Approved'),
            'non_approved' => $view->translate('Only Show Non-Approved'),
            'all' => $view->translate('Show All'),
        ),
        'value' => '',
    ),
);
$statisticsElement = array(
    'MultiCheckbox',
    'statistics',
    array(
        'label' => $view->translate('Choose the statistics that you want to be displayed for the Listings in this block.'),
        'multiOptions' => array("viewCount" => "Views", "likeCount" => "Likes", "commentCount" => "Comments"),
    //'value' =>array("viewCount","likeCount","commentCount","reviewCount"),
    ),
);

if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.proximity.search.kilometer', 0)) {
  $locationDescription = "Choose the kilometers within which listings will be displayed. (This setting will only work, if you have chosen 'Yes' in the above setting.)";
  $locationLableS = "Kilometer";
  $locationLable = "Kilometers";
} else {
  $locationDescription = "Choose the miles within which listings will be displayed. (This setting will only work, if you have chosen 'Yes' in the above setting.)";
  $locationLableS = "Mile";
  $locationLable = "Miles";
}

$detactLocationElement = array(
    'Select',
    'detactLocation',
    array(
        'label' => 'Do you want to display listings based on user’s current location?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => '0'
    )
);

$defaultLocationDistanceElement = array(
    'Select',
    'defaultLocationDistance',
    array(
        'label' => $locationDescription,
        'multiOptions' => array(
            '0' => '',
            '1' => '1 ' . $locationLableS,
            '2' => '2 ' . $locationLable,
            '5' => '5 ' . $locationLable,
            '10' => '10 ' . $locationLable,
            '20' => '20 ' . $locationLable,
            '50' => '50 ' . $locationLable,
            '100' => '100 ' . $locationLable,
            '250' => '250 ' . $locationLable,
            '500' => '500 ' . $locationLable,
            '750' => '750 ' . $locationLable,
            '1000' => '1000 ' . $locationLable,
        ),
        'value' => '1000'
    )
);

$popularity_options = array(
    'view_count' => $view->translate('Most Viewed'),
    'like_count' => $view->translate('Most Liked'),
    'comment_count' => $view->translate('Most Commented'),
    'review_count' => $view->translate('Most Reviewed'),
    'rating_avg' => $view->translate('Most Rated (Average Rating)'),
    'rating_editor' => $view->translate('Most Rated (Editor Rating)'),
    'rating_users' => $view->translate('Most Rated (User Ratings)'),
    'creation_date' => $view->translate('Most Recent'),
    'modified_date' => $view->translate('Recently Updated'),
    'custom_algo' => $view->translate('Custom Listing Algorithm'),
);


return array(
  array(
    'title' => 'Custom Features',
    'description' => 'Display custom features blocks',
    'category' => 'Guidance Guide Custom Features',
    'type' => 'widget',
    'name' => 'sdparentalguide.custom-features',    
  ),
  array(
    'title' => 'Top Active Members',
    'description' => 'Displays a list of top active members on your site based on: maximum activities performed, total earned credits or current credit balance.',
    'category' => 'Guidance Guide Custom Features',
    'type' => 'widget',
    'name' => 'sdparentalguide.top-member',
    'defaultParams' => array(
        'title' => '',
        'titleCount' => true,
    ),
    'adminForm' => array(
        'elements' => array(
            $showOptions,$count
        ),
    ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),
  array(
    'title' => 'Listing Search Form',
    'description' => 'Display listing search form',
    'category' => 'Guidance Guide Custom Features',
    'type' => 'widget',
    'name' => 'sdparentalguide.listing-search',    
  ),
  array(
    'title' => 'Browse Listings',
    'description' => 'Display listings',
    'category' => 'Guidance Guide Custom Features',
    'type' => 'widget',
    'name' => 'sdparentalguide.browse-listings',
    'autoEdit' => true,
    'adminForm' => array(
        'elements' => array(
            array(
                'Text',
                'truncation',
                array(
                    'label' => $view->translate('Title Truncation Limit'),
                    'value' => 32,
                )
            ),
            array(
                'Text',
                'truncationDescription',
                array(
                    'label' => $view->translate("Enter the trucation limit for the Listing Description. (If you want to hide the description, then enter '0'.)"),
                    'value' => 1024,
                )
            ),
            array(
                'Text',
                'itemCount',
                array(
                    'label' => $view->translate('Count'),
                    'description' => $view->translate('(number of Listings to show)'),
                    'value' => 10,
                )
            ),
        )
    ),
  ),
  array(
    'title' => 'Assigned Badges',
    'description' => 'Display listing of assigned badges',
    'category' => 'Guidance Guide Custom Features',
    'type' => 'widget',
    'name' => 'sdparentalguide.assigned-badges',
    'defaultParams' => array(
        'title' => 'Badges',
        'titleCount' => true,
    ),
    'adminForm' => array(
        'elements' => array(
            array(
                'Text',
                'photoHeight',
                array(
                    'label' => 'Enter the height of each photo.',
                    'value' => 100,
                )
            ),
            array(
                'Text',
                'photoWidth',
                array(
                    'label' => 'Enter the width of each photo.',
                    'value' => 100,
                )
            ),
        )
    ),
  ),
  array(
    'title' => 'Assigned Badges Slider',
    'description' => 'Display listing of assigned badges in slider',
    'category' => 'Guidance Guide Custom Features',
    'type' => 'widget',
    'name' => 'sdparentalguide.assigned-badges-slider',
  ),
  array(
        'title' => $view->translate('Custom Listings Home: Pinboard View'),
        'description' => $view->translate('Displays listings in Pinboard View on the Listings Home page. Multiple settings are available to customize this widget.'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sdparentalguide.pinboard-listings-sitereview',
        'defaultParams' => array(
            'title' => $view->translate('Recent'),
            'statistics' => array("likeCount", "reviewCount"),
            'show_buttons' => array("wishlist", "compare", "comment", "like", 'share', 'facebook', 'twitter', 'pinit')
        ),
        'adminForm' => array(
            'elements' => array(
                $listingTypeCategoryElement,
                $ratingTypeElement,
                $featuredSponsoredElement,
                $detactLocationElement,
                $defaultLocationDistanceElement,
                $approvedListingElement,
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => $view->translate('Popularity Criteria'),
                        'multiOptions' => $popularity_options,
                        'value' => 'creation_date',
                    )
                ),
                array(
                    'Select',
                    'interval',
                    array(
                        'label' => $view->translate('Popularity Duration (This duration will be applicable to these Popularity Criteria:  Most Liked, Most Commented, Most Rated and Most Recent.)'),
                        'multiOptions' => array('week' => '1 Week', 'month' => '1 Month', 'overall' => 'Overall'),
                        'value' => 'overall',
                    )
                ),
                array(
                    'Select',
                    'listing_created',
                    array(
                        'label' => $view->translate('Listings Created'),
                        'multiOptions' => array('day' => 'In the last day', 'week' => 'In the last week', 'month' => 'In the last month', 'overall' => 'All time'),
                        'value' => 'overall',
                    )
                ),
                $categoryElement,
                $hiddenCatElement,
                $hiddenSubCatElement,
                $hiddenSubSubCatElement,
                $statisticsElement,
                array(
                    'Radio',
                    'postedby',
                    array(
                        'label' => $view->translate('Show posted by option. (Selecting "Yes" here will display the member\'s name who has created the listing.)'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'userComment',
                    array(
                        'label' => $view->translate('Do you want to show user comments and enable user to post comment or not?'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => 1,
                    ),
                ),
                array(
                    'Select',
                    'price',
                    array(
                        'label' => $view->translate('Show price option. (Selecting "Yes" here will display the price of the listing.)'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => '0',
                    )
                ),
                array(
                    'Select',
                    'location',
                    array(
                        'label' => $view->translate('Show location option. (Selecting "Yes" here will display the location of the listing.)'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => '0',
                    )
                ),
                array(
                    'Select',
                    'autoload',
                    array(
                        'label' => $view->translate('Do you want to enable auto-loading of old pinboard items when users scroll down to the bottom of this page?'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => '1'
                    )
                ),
                array(
                    'Select',
                    'defaultLoadingImage',
                    array(
                        'label' => $view->translate('Do you want to show a Loading image when this widget renders on a page?'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => 1
                    )
                ),
                array(
                    'Text',
                    'itemWidth',
                    array(
                        'label' => $view->translate('One Item Width'),
                        'description' => $view->translate('Enter the width for each pinboard item.'),
                        'value' => 237,
                    )
                ),
                array(
                    'Radio',
                    'withoutStretch',
                    array(
                        'label' => $view->translate('Do you want to display the images without stretching them to the width of each pinboard item?'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => '0',
                    )
                ),
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => $view->translate('Count'),
                        'description' => $view->translate('(number of Listings to show)'),
                        'value' => 12,
                    )
                ),
                array(
                    'Text',
                    'noOfTimes',
                    array(
                        'label' => $view->translate('Auto-Loading Count'),
                        'description' => $view->translate('Enter the number of times that auto-loading of old pinboard items should occur on scrolling down. (Select 0 if you do not want such a restriction and want auto-loading to occur always. Because of auto-loading on-scroll, users are not able to click on links in footer; this setting has been created to avoid this.)'),
                        'value' => 0,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_buttons',
                    array(
                        'label' => $view->translate('Choose the action links that you want to be available for the Listings displayed in this block. (This setting will only work, if you have chosen Pinboard View from the above setting.)'),
                        'multiOptions' => array("wishlist" => "Wishlist", "compare" => "Compare", "comment" => "Comment", "like" => "Like / Unlike", 'share' => 'Share', 'facebook' => 'Facebook', 'twitter' => 'Twitter', 'pinit' => 'Pin it', 'tellAFriend' => 'Tell a Friend', 'print' => 'Print')
                    //'value' =>array("viewCount","likeCount","commentCount","reviewCount"),
                    ),
                ),
                array(
                    'Text',
                    'truncationDescription',
                    array(
                        'label' => $view->translate("Enter the trucation limit for the Listing Description. (If you want to hide the description, then enter '0'.)"),
                        'value' => 100,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Custom AJAX Search for Listings'),
        'description' => $view->translate("This widget searches over Listing Titles via AJAX. The search interface is similar to Facebook search."),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sdparentalguide.searchbox-sitereview',
        'defaultParams' => array(
            'title' => $view->translate("Search"),
            'titleCount' => "",
        ),
        'adminForm' => array(
            'elements' => array(
                $listingTypeElement2,
            ),
        ),
    ),    
    array(
        'title' => $view->translate('Custom Listing Profile: Listing Cover Photo'),
        'description' => $view->translate('Displays the main cover photo of a listing. This widget must be placed on the Multiple Listing Types - Listing Profile page at the top of left column.'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.mainphoto-sitereview',
        'defaultParams' => array(
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'ownerName',
                    array(
                        'label' => $view->translate('Do you want to display listing owner’s name in this widget?'),
                        'multiOptions' => array(
                            1 => $view->translate('Yes'),
                            0 => $view->translate('No')
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_buttons',
                    array(
                        'label' => $view->translate('Choose the action links that you want to be available for the Listings displayed in this block. (This setting will only work, if you have chosen Pinboard View from the above setting.)'),
                        'multiOptions' => array(/*"wishlist" => "Wishlist", "compare" => "Compare", "comment" => "Comment", "like" => "Like / Unlike",*/ 'share' => 'Share', 'facebook' => 'Facebook', 'twitter' => 'Twitter', 'pinit' => 'Pin it', /*'tellAFriend' => 'Tell a Friend', 'print' => 'Print',*/ 'setting' => 'Settings')
                    //'value' =>array("viewCount","likeCount","commentCount","reviewCount"),
                    ),
                ),
            )
        )
    ),
    array(
        'title' => $view->translate('Custom Listing Profile: Listing Rating'),
        'description' => $view->translate('Allows to rate the product or rate the author review.'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.listing-rating',
    ),
    array(
        'title' => $view->translate('Footer Menu'),
        'description' => $view->translate('Displaying latest footer menu for ParentalGuide'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.footer',
    ),
    array(
        'title' => $view->translate('Header Menu'),
        'description' => $view->translate('Displaying header menu for ParentalGuide'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.footer',
    ),

    array(
        'title' => $view->translate('Profile Landing'),
        'description' => $view->translate('Displaying widget for Reviews, Contribution Points, Following and Latest Guides'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.profile-landing',
        'defaultParams' => array(
            'title' => 'Overview',
        ),
    ),

    array(
        'title' => $view->translate('AJAX Profile Password'),
        'description' => $view->translate('Edit Password in AJAX Mode'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-password',
        'defaultParams' => array(
            'title' => 'Password',
        ),
    ),

    array(
        'title' => $view->translate('AJAX Delete Profile'),
        'description' => $view->translate('Delete Profile in AJAX Mode'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-delete',
        'defaultParams' => array(
            'title' => 'Delete Account',
        ),
    ),

    array(
        'title' => $view->translate('AJAX Update Profile'),
        'description' => $view->translate('Update Profile in AJAX Mode'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-profile',
        'defaultParams' => array(
            'title' => 'Personal Info',
        ),
    ),

    array(
        'title' => $view->translate('AJAX Update Notifications'),
        'description' => $view->translate('Update Notifications in AJAX Mode'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-notifications',
        'defaultParams' => array(
            'title' => 'Notifications',
        ),
    ),

    array(
        'title' => $view->translate('AJAX My Struggles'),
        'description' => $view->translate('My Struggles in AJAX Mode'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-struggles',
        'defaultParams' => array(
            'title' => 'My Struggles',
            'titleCount' => true,
        ),
    ),

    array(
        'title' => $view->translate('AJAX My Theories'),
        'description' => $view->translate('My Theories in AJAX Mode'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-theories',
        'defaultParams' => array(
            'title' => 'My Theories',
            'titleCount' => true,
        ),
    ),

    array(
        'title' => $view->translate('AJAX My Badges'),
        'description' => $view->translate('AJAX My Badges'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-badges',
        'defaultParams' => array(
            'title' => 'My Badges',
            'titleCount' => true,
        ),
    ),

    array(
        'title' => $view->translate('AJAX Privacy'),
        'description' => $view->translate('AJAX Privacy'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-privacy',
        'defaultParams' => array(
            'title' => 'Privacy',
        ),
    ),

    array(
        'title' => $view->translate('AJAX My Info'),
        'description' => $view->translate('AJAX My Info'),
        'category' => 'Guidance Guide Custom Features',
        'type' => 'widget',
        'name' => 'sdparentalguide.ajax-info',
        'defaultParams' => array(
            'title' => 'My Info',
        ),
    ),


) ?>