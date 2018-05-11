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
        'title' => $view->translate('Navigation Tabs'),
        'description' => $view->translate('Displays the Navigation tabs: FAQs having links of FAQ Home, Browse FAQs, etc. This widget should be placed at the top of FAQ Home Page, FAQ Browse Page and FAQ Manage Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.navigation-sitefaqs',
    ),
    array(
        'title' => $view->translate('Add FAQ'),
        'description' => $view->translate('Displays a `Add FAQ` link.( Create link will be visible if user level is allowed to add FAQs )'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.create-sitefaqs',
    ),
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
                    'linked',
                    array(
                        'label' => $view->translate("Do you want the Title of FAQ to be a link? (Note: If you select ‘Yes’ over here, then FAQ will not be expanded / hide when clicked and will redirect users to the FAQ View Page.)"),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'Radio',
                    'print',
                    array(
                        'label' => $view->translate('Show print option.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
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
                    'Text',
                    'itemCount',
                    array(
                        'label' => $view->translate('Count'),
                        'description' => $view->translate('(number of FAQs to show)'),
                        'value' => 20,
                    )
                ),
                array(
                    'Text',
                    'truncation',
                    array(
                        'label' => $view->translate('Enter the maximum limit (between 1 and 999) to be applied to the number of characters in the description of FAQs. (If you do not want to truncate the description, then enter 0 below.)'),
                        'value' => 0,
                        'validators' => array(
                            array('Int', true),
                        ),
                    )
                ),
                array(
                    'Radio',
                    'scrollButton',
                    array(
                        'label' => $view->translate('Scroll to Top Button'),
                        'description' => $view->translate("Do you want the 'Scroll to Top' button to be displayed for this block? (As a user scrolls down to see more FAQs from this widget, the 'Scroll to Top' button will be shown in the bottom-right side of the screen, enabling user to easily move to the top.)"),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'show_content',
                    array(
                        'label' => 'What do you want for view more content?',
                        'description' => '',
                        'multiOptions' => array(
                            '1' => 'Pagination',
                            '2' => 'Show View More Link at Bottom',
                            '3' => 'Auto Load Faqs on Scrolling Down'),
                        'value' => 2,
                    )
                ),
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                        'order' => 1001
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Manage FAQs'),
        'description' => $view->translate('Displays to users a list of all the FAQs created by them. This widget should be placed on the FAQ Manage Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.manage-sitefaqs',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
            'orderby' => 'faq_id'
        ),
        'adminForm' => array(
            'elements' => array(
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
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                        'order' => 1002
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Popular FAQ Tags'),
        'description' => $view->translate('Shows popular tags with frequency.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.tagcloud-sitefaqs',
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
                        'description' => $view->translate('(number of tags to show)'),
                        'value' => 100,
                    )
                ),
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                        'order' => 1003
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Mobile Browse FAQs'),
        'description' => $view->translate('Displays a list of all the FAQs on site. This widget should be placed on the Mobile FAQ Browse Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.mobi-browse-sitefaqs',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
            'orderby' => 'faq_id'
        ),
        'adminForm' => array(
            'elements' => array(
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
                    'Text',
                    'truncation',
                    array(
                        'label' => $view->translate('Enter the maximum limit (between 1 and 999) to be applied to the number of characters in the description of FAQs. (If you do not want to truncate the description, then enter 0 below.)'),
                        'value' => 0,
                        'validators' => array(
                            array('Int', true),
                        ),
                    )
                ),
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                        'order' => 1004
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Search FAQs form'),
        'description' => $view->translate('Displays the form for searching FAQs on the basis of various fields and filters. Settings for this form can be configured from the Search Form Settings section.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.search-sitefaqs',
    ),
    array(
        'title' => $view->translate('FAQ View Page Options'),
        'description' => $view->translate('Displays the various action links that can be performed on the FAQs from their View page (edit, delete, share, etc.). This widget should be placed on the FAQ View Page. You can manage the Action Links available in this widget from the Menu Editor section by choosing FAQ View Page Options Menu.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.options-sitefaqs',
        'requirements' => array(
            'subject' => 'sitefaq_faq',
        ),
    ),
    array(
        'title' => $view->translate('FAQ Rating'),
        'description' => $view->translate('Displays the overall rating given to an FAQ by other users. If enabled from Admin Panel, users can also rate the FAQ using this widget. This widget should be placed on FAQ View page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.ratings-sitefaqs',
        'defaultParams' => array(
            'title' => $view->translate('Ratings'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('FAQs: Auto-suggest Search'),
        'description' => $view->translate('Displays auto-suggest search box for FAQs. As user types, FAQs will be displayed in an auto-suggest box.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.search-box-sitefaqs',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'heading',
                    array(
                        'label' => 'Do you want to show Heading?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Text',
                    'blur_text',
                    array(
                        'description' => $view->translate('Enter the text to be shown in the search box.'),
                        'value' => $view->translate('Enter a keyword or question'),
                    )
                ),
                array(
                    'Radio',
                    'privacy',
                    array(
                        'label' => $view->translate('Do you want to display private FAQs also in the search results?'),
                        'multiOptions' => array(
                            1 => "Yes",
                            0 => "No"
                        ),
                        'value' => 1,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Mobile Search FAQs'),
        'description' => $view->translate('Displays search box for FAQs. This widget should be placed on Mobile FAQ Home Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.mobi-search-box-sitefaqs',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'heading',
                    array(
                        'label' => 'Do you want to show Heading?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 1,
                    )
                ),
            ),
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
                ),
                array(
                    'Radio',
                    'breadCrumb',
                    array(
                        'label' => $view->translate('Show Breadcrumb.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                        'order' => 1005
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Categories Hierarchy for FAQs (sidebar)'),
        'description' => $view->translate('Displays the Categories, Sub-categories and 3<sup>rd</sup> Level-categories of FAQs in an expandable form. Clicking on them will redirect the viewer to the list of FAQs created in that category.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.sidebar-categories-sitefaqs',
        'defaultParams' => array(
            'title' => $view->translate('Categories'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('Categories Hierarchy for FAQs (View Page: sidebar)'),
        'description' => $view->translate('Displays all the Categories, Sub-categories and 3rd Level-categories associated with the current FAQ being viewed in an expandable form. Clicking on them will redirect the viewer to the list of FAQs created in that category. This widget should be placed on the FAQ View Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.sidebar-categories-view-sitefaqs',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
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
        'title' => $view->translate('Owner’s Photo'),
        'description' => $view->translate('Displays the FAQ owner’s photo with owner’s name. This widget should be placed in the right column of FAQ View Page.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.sitefaq-owner-photo-sitefaqs',
        'requirements' => array(
            'subject' => 'sitefaq_faq',
        ),
    ),
    array(
        'title' => $view->translate('FAQ Information'),
        'description' => $view->translate('Displays the owner, category, tags, views and other information about an FAQ. This widget should be placed on the FAQ View page in the right column.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.information-sitefaqs',
        'defaultParams' => array(
            'title' => 'Information',
            'titleCount' => true,
        ),
        'requirements' => array(
            'subject' => 'sitefaq_faq',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'posted',
                    array(
                        'label' => 'Show Posted By.',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'Radio',
                    'owner_photo',
                    array(
                        'label' => 'Do you want to show FAQ owner’s photo in ‘Posted By’?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 0,
                    )
                ),
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
            ),
        ),
    ),
    array(
        'title' => $view->translate('FAQ Social Share Buttons'),
        'description' => $view->translate("Contains Social Sharing buttons and enables users to easily share FAQs on their favorite Social Networks. This widget should be placed on the FAQ View Page. You can customize the code for social sharing buttons from Global Settings of this plugin by adding your own code generated from: <a href='http://www.addthis.com' target='_blank'>http://www.addthis.com</a>"),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.socialshare-sitefaqs',
        'defaultParams' => array(
            'title' => $view->translate('Social Share'),
            'titleCount' => true,
        ),
        'requirements' => array(
            'subject' => 'sitefaq_faq',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                        'order' => 1006
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Related FAQs'),
        'description' => $view->translate('Displays a list of FAQs related to the FAQ currently being viewed. This widget should be placed on FAQ View Page. The related FAQs are shown based on the tags and top-level category of the FAQ being viewed.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'name' => 'sitefaq.related-faqs-view-sitefaqs',
        'defaultParams' => array(
            'title' => $view->translate('Related FAQs'),
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'related',
                    array(
                        'label' => $view->translate('Choose which all FAQs should be displayed here as FAQs related to the current FAQ.'),
                        'multiOptions' => array(
                            'tags' => "FAQs having same tag.(Note: 'Tags Field' should be enabled from Global Settings.)",
                            'categories' => 'FAQs associated with same `Categories`.'
                        ),
                        'value' => 'categories',
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
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                        'order' => 1007
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
                    'column',
                    array(
                        'label' => $view->translate('Choose column for this widget.'),
                        'multiOptions' => array(
                            1 => 'Right Coumn / Left Column',
                            0 => 'Middel Column'
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
                    'Radio',
                    'show',
                    array(
                        'label' => $view->translate('Choose what to show in this widget along with Categories and Sub-categories.'),
                        'multiOptions' => array(
                            3 => 'FAQs of subcategories (Collapsed View)',
                            2 => 'FAQs of subcategories (Expanded View)',
                            1 => '3rd Level Categories',
                            0 => 'Only Categories and Sub-categories'
                        ),
                        'value' => 2,
                    )
                ),
                array(
                    'Radio',
                    'show_count',
                    array(
                        'label' => $view->translate('Show FAQs Count along with Categories and Sub-categories.'),
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
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
                        'order' => 1008
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => $view->translate('Mobile Categories Hierarchy for FAQs'),
        'description' => $view->translate('Displays the Categories, Sub-categories and 3rd Level-categories of FAQs in a grid form. Clicking on them will redirect the viewer to the list of FAQs created in that category.'),
        'category' => $view->translate('FAQs'),
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sitefaq.mobi-home-sitefaqs',
        'defaultParams' => array(
            'title' => $view->translate(''),
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Hidden',
                    'nomobile',
                    array(
                        'label' => '',
                        'order' => 1009
                    )
                ),
            ),
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
);
