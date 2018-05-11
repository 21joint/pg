<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
$category_sitefaqs_multioptions = array(
    'view_count' => $view->translate('Views'),
    'like_count' => $view->translate('Likes'),
    'comment_count' => $view->translate('Comments'),
);

$popularity_options = array(
    'view_count' => $view->translate('Most Viewed'),
    'like_count' => $view->translate('Most Liked'),
    'comment_count' => $view->translate('Most Commented'),
    'rating' => $view->translate('Most Rated'),
    'creation_date' => $view->translate('Most Recent'),
    'modified_date' => $view->translate('Recently Updated'),
    'helpful' => $view->translate('Most Helpful'),
    'weight' => $view->translate('Most Weighted'),
    'RAND()' => $view->translate('Random'),
);

$categories = Engine_Api::_()->getDbTable('categories', 'sitefaq')->getCategories(null);
$categories_prepared = array();
if (count($categories) != 0) {
    $categories_prepared[0] = "";
    foreach ($categories as $category) {
        $categories_prepared[$category->category_id] = $category->category_name;
    }
}

return array(
    array(
        'title' => $view->translate('Browse FAQs'),
        'description' => $view->translate('Displays a list of all the FAQs on site. This widget should be placed on the FAQ Browse Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.browse-sitefaqs',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
            'orderby' => 'faq_id'
        ),
        'adminForm' => array(
            'elements' => array(               
                array(
                    'Radio',
                    'statisticsComment',
                    array(
                        'label' => $view->translate('Show Like & Comment Statistics.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'statisticsView',
                    array(
                        'label' => $view->translate('Show Views Statistics.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => $view->translate('Count'),
                        'description' => $view->translate('(number of FAQs to show)'),
                        'value' => 20,
                    )
                ),
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Categories Hierarchy for FAQs'),
        'description' => $view->translate('Displays the Categories, Sub-categories and 3<sup>rd</sup> Level-categories of FAQs in an expandable form. Clicking on them will redirect the viewer to the list of FAQs created in that category. Multiple settings are available to customize this widget. This widget should be placed on the FAQ Home Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sitefaq.categories-faqs-sitefaqs',
        'defaultParams' => array(
            'title' => $view->translate(''),
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'faq_limit',
                    array(
                        'label' => $view->translate('Enter number of FAQs to be shown for each sub-category. This setting will only work if you choose to show FAQs from the setting above.'),
                        'value' => 5,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                ),
                array(
                    'Text',
                    'truncation',
                    array(
                        'label' => $view->translate('Title Truncation Limit'),
                        'value' => 50,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                ),
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Message for No FAQs'),
        'description' => $view->translate('Displays a message to users when there are no FAQs. This widget should be placed in the top of the middle column of FAQ Home page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.zero-sitefaqs',
    ),
    array(
        'title' => $view->translate('FAQ Rating'),
        'description' => $view->translate('Displays the overall rating given to an FAQ by other users. If enabled from Admin Panel, users can also rate the FAQ using this widget. This widget should be placed on FAQ View page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.ratings-sitefaqs',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('FAQ Featured Label'),
        'description' => $view->translate("Displays the 'FEATURED' Label for the Featured FAQs marked by you from the 'Manage FAQs' section in the Admin Panel. This widget should be placed on the FAQ View Page."),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.featured-view-sitefaqs',
        'adminForm' => 'Sitefaq_Form_Admin_Widget_Rainbow',
        'requirements' => array(
            'subject' => 'sitefaq_faq',
        ),
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('FAQ View'),
        'description' => $view->translate('Displays the main FAQ. This widget should be placed on the FAQ View Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.sitefaq-view-sitefaqs',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'requirements' => array(
            'subject' => 'sitefaq_faq',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'statisticsHelpful',
                    array(
                        'label' => $view->translate('Show Helpful Statistics.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ), //SETTINGS ADDED FOR INFORMATION WIDGET.              
                array(
                    'Radio',
                    'update',
                    array(
                        'label' => 'Show last updated date.',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'created',
                    array(
                        'label' => 'Show created date.',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'Radio',
                    'tags',
                    array(
                        'label' => 'Show topics covered (tags).',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Popular / Recent / Random FAQs'),
        'description' => $view->translate('Displays FAQs based on the Popularity Criteria and other settings that you choose for this widget. You can place this widget multiple times on a page with different popularity criterion chosen for each placement.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sitefaq.faqs-sitefaqs',
        'defaultParams' => array(
            'title' => $view->translate('FAQs'),
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => $view->translate('Count'),
                        'description' => $view->translate('(number of FAQs to show)'),
                        'value' => 3,
                    )
                ),
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => $view->translate('Popularity Criteria'),
                        'multiOptions' => $popularity_options,
                        'value' => 'view_count',
                    )
                ),
                array(
                    'Select',
                    'interval',
                    array(
                        'label' => $view->translate('Popularity Duration (This duration will be applicable to these Popularity Criteria:  Most Liked, Most Commented and Most Recent.)'),
                        'multiOptions' => array('week' => '1 Week', 'month' => '1 Month', 'overall' => 'Overall'),
                        'value' => 'overall',
                    )
                ),
                array(
                    'Select',
                    'category_id',
                    array(
                        'label' => $view->translate('Category'),
                        'multiOptions' => $categories_prepared,
                    )
                ),
                array(
                    'Radio',
                    'featured',
                    array(
                        'label' => $view->translate('Show only Featured FAQs.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'Radio',
                    'statisticsRating',
                    array(
                        'label' => $view->translate('Show Rating.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'statisticsHelpful',
                    array(
                        'label' => $view->translate('Show Helpful Statistics.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'statisticsComment',
                    array(
                        'label' => $view->translate('Show Like & Comment Statistics.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'statisticsView',
                    array(
                        'label' => $view->translate('Show Views Statistics.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'privacy',
                    array(
                        'label' => $view->translate('Do you want to display private FAQs also?'),
                        'multiOptions' => array(
                            1 => "Yes",
                            0 => "No"
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Radio',
                    'viewAll',
                    array(
                        'label' => $view->translate("Show 'View All' link"),
                        'multiOptions' => array(
                            1 => "Yes",
                            0 => "No"
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Text',
                    'truncation',
                    array(
                        'label' => $view->translate('Title Truncation Limit'),
                        'value' => 26,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                ),
            ),
        ),
    ),
);
