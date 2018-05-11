<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$isActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.isActivate', 0);
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
        'title' => 'Page Profile Popular Reviews',
        'description' => 'Displays list of Page\'s popular reviews. Setting for this widget is available in widget settings tab of Page - Reviews admin.',
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagereview.popular-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Most Popular Reviews',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
                        'value' => 3,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'Page Profile Most Commented Reviews',
        'description' => 'Displays list of Page\'s most commented reviews. Setting for this widget is available in widget settings tab of Page - Reviews admin.',
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagereview.comment-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Most Commented Reviews',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
                        'value' => 3,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'Page Profile Most Liked Reviews',
        'description' => 'Displays list of Page\'s most liked reviews. Setting for this widget is available in widget settings tab of Page - Reviews admin.',
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagereview.like-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Most Liked Reviews',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
                        'value' => 3,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'Page Profile Reviews',
        'description' => 'This widget forms the Reviews tab on the Page Profile and displays the reviews of the Page. It should be placed in the Tabbed Blocks area of the Page Profile.',
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagereview.profile-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Reviews',
        ),
    ),
    array(
        'title' => 'Page Profile Review Rating',
        'description' => 'Displays a Page\'s review ratings on it\'s profile.',
        'category' => 'Page Profile',
        'type' => 'widget',
        'name' => 'sitepagereview.ratings-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Ratings',
        ),
    ),
    array(
        'title' => 'Top Rated Pages',
        'description' => 'Displays the top rated Pages of the site.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.topratedpages-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Top Rated Pages',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of pages to show)',
                        'value' => 3,
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
        'title' => 'Recent Reviews',
        'description' => 'Displays the most recent reviews of the site.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.recent-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Recent Reviews',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
                        'value' => 3,
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
        'title' => 'Top Reviewers',
        'description' => 'This widget shows the top reviewers for the Pages on your site based on the number of reviews posted by them.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.reviewer-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Top Reviewers',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviewers to show)',
                        'value' => 3,
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
        'title' => 'Featured Reviews',
        'description' => 'Displays Featured Reviews as chosen by you from the Manage Ratings & Reviews section in the admin panel of this extension.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.featured-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Featured Reviews',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
                        'value' => 3,
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
        'title' => 'Review of the Day',
        'description' => 'Displays the Review of the Day for Pages as selected by the Admin from the widget settings section of Reviews and Ratings Extension.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.review-of-the-day',
        'defaultParams' => array(
            'title' => 'Review of the Day'
        ),
    ),
    array(
        'title' => 'AJAX Tabbed Reviews widget',
        'description' => 'This tabbed AJAX widget concisely shows important information about reviews in 3 tabs: Recent, Popular, Top Reviewers.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.review-tabs',
        'defaultParams' => array(
            'title' => 'People\'s Reviews',
            'visibility' => array("recent", "popular", "reviewer")
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(No. of Elements)',
                        'value' => 3,
                    )
                ),
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity Criteria',
                        'multiOptions' => array(
                            'view_count' => 'Views',
                            'like_count' => 'Likes',
                            'comment_count' => 'Comments'
                        ),
                        'value' => 'view_count',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'visibility',
                    array(
                        'label' => 'Tabs?',
                        'multiOptions' => array(
                            'recent' => 'Recent',
                            'popular' => 'Popular',
                            'reviewer' => 'Top Reviewers',
                        ),
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
        'title' => 'Popular Reviews',
        'description' => 'Displays popular reviews for Pages on your site. From the edit popup of this widget, you can set the number of reviews to show in this widget and the criteria for popularity.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.site-popular-reviews',
        'defaultParams' => array(
            'title' => 'Popular Reviews',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
                        'value' => 3,
                    )
                ),
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity Criteria',
                        'multiOptions' => array(
                            'view_count' => 'Views',
                            'like_count' => 'Likes',
                            'comment_count' => 'Comments'
                        ),
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
        'title' => 'Page Review View',
        'description' => "This widget should be placed on the Page Review View Page.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.review-content',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => 'Page Reviews',
        'description' => 'Displays the list of Reviews from Pages created on your community. This widget should be placed in the widgetized Page Reviews page. Results from the Search Page Reviews form are also shown here.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.sitepage-review',
        'defaultParams' => array(
            'title' => 'Reviews',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
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
        'title' => 'Search Page Reviews form',
        'description' => 'Displays the form for searching Page Reviews on the basis of various filters. You can edit the fields to be available in this form.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.search-sitepagereview',
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
                        'label' => 'Choose the fields that you want to be available in the Search Page Reviews form widget.',
                        'multiOptions' => array("1" => "Show", "2" => "Browse By", "3" => "Page Title", "4" => "Review Title", "5" => "Page Category"),
                    ),
                ),
            ),
        )
    ),
    array(
        'title' => 'Most Commented Reviews',
        'description' => "Displays the Most Commented Page Reviews. You can choose the number of entries to be shown.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.homecomment-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Most Commented Reviews',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
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
        'title' => 'Most Liked Reviews',
        'description' => "Displays the Most Liked Page Reviews. You can choose the number of entries to be shown.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.homelike-sitepagereviews',
        'defaultParams' => array(
            'title' => 'Most Liked Reviews',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count',
                        'description' => '(number of reviews to show)',
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
        'title' => 'Review Details',
        'description' => "Displays overall as well as parametric reviews in detailed manner.",
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.sitepage-review-detail',
        'defaultParams' => array(
            'title' => 'Review Details',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => 'Page’s Featured Reviews Slideshow',
        'description' => 'Displays featured reviews in an attractive slideshow. You can set the count of the number of reviews to show in this widget. If the total number of reviews featured are more than that count, then the reviews to be displayed will be sequentially picked up.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.featured-reviews-slideshow',
        'isPaginated' => true,
        'defaultParams' => array(
            'title' => 'Featured Reviews',
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
        'title' => 'Page’s Featured Reviews Carousel',
        'description' => 'This widget contains an attractive AJAX based carousel, showcasing the featured reviews on the site. Multiple settings of this widget makes it highly configurable.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.featured-reviews-carousel',
        'defaultParams' => array(
            'title' => 'Featured Reviews',
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
                        'label' => 'Reviews in a Row',
                        'description' => '(number of reviews to show in one row. Note: This field is applicable only when you have selected ‘Horizontal’ in ‘Carousel Type’ field.)',
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
        'title' => 'Browse Reviews Link',
        'description' => 'Displays the link to view Page’s Reviews Browse page',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.sitepagereviewlist-link',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => 'Top Creators : Page Reviews',
        'description' => 'Displays the Pages which have the most number of Page Reviews added in them. Motivates Page Admins to add more content on your website.',
        'category' => 'Pages',
        'type' => 'widget',
        'name' => 'sitepagereview.topcreators-sitepagereview',
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
            'subject' => 'sitepagereview',
        ),
    ),
        )
?>
