<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    array(
        'title' => 'Navigation Tabs',
        'description' => 'This widget displays the navigation tabs for "Feedbacks Plugin" having links of Browse Feedbacks, My Feedbacks and Create New Feedback.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.navigation-feedbacks',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => 'Create New Feedback',
        'description' => 'Displays the link to Create New Feedback .',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.new-feedback',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        )
    ),
    array(
        'title' => 'Browse Feedbacks',
        'description' => 'Displays a list of all the feedbacks on site. This widget should be placed on the Feedbacks - Browse Feedbacks page.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.browse-feedbacks',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'show_content',
                    array(
                        'label' => 'What do you want for view more content?',
                        'description' => '',
                        'multiOptions' => array(
                            '1' => 'Pagination',
                            '2' => 'Show View More Link at Bottom',
                            '3' => 'Auto Load Feedbacks on Scrolling Down'),
                        'value' => 2,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'Search Feedbacks Form',
        'description' => 'Displays the form for searching Feedbacks on the basis of various fields and filters. Settings for this form can be configured from the Search Form Settings section.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.search-feedback',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => 'Recent / Popular Feedbacks',
        'description' => 'Displays Feedbacks based on the Popularity / Sorting Criteria and other settings that you choose for this widget. You can place this widget multiple times on a page with different popularity criterion chosen for each placement.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.recent-feedbacks',
        'defaultParams' => array(
            'title' => 'Recent Feedbacks',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity / Sorting Criteria',
                        'multiOptions' => array('views' => 'Most Viewed', 'total_votes' => 'Most Voted', 'comment_count' => 'Most Commented', 'feedback_id' => 'Recently Created'),
                        'value' => 'feedback_id',
                    )
                ),
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Number of Feedbacks to show',
                        'value' => 3,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                ),
                array(
                    'Text',
                    'truncationLimit',
                    array(
                        'label' => 'Title Truncation Limit',
                        'value' => 19,
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
        'title' => 'Feedback View Page: Feedback Options',
        'description' => 'Displays the various action link options to users viewing a Feedback. This widget should be placed on the Feedbacks - Feedback View page in the left column, below the feedback owner photo.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.options-feedback',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
            ),
        ),
    ),
    array(
        'title' => 'Feedback View / User\'s Feedbacks Page: Feedback Owner Photo',
        'description' => 'Displays the feedback owner’s photo with owner’s name. This widget must be placed on the Feedbacks - Feedback View page or Feedbacks - User\'s Feedbacks page in the left / right column.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.owner-photo-feedback',
        'defaultParams' => array(
            'titleCount' => true,
        ),
    ),
    array(
        'title' => 'Feedback View Page: Similar Feedbacks',
        'description' => 'This widget displays the feedbacks having the same category of the feedback being currently viewed, based on the Popularity / Sorting Criteria and other settings that you choose for this widget. This widget must be placed on the Feedbacks - Feedback View page in the left / right column.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.similar-feedbacks',
        'defaultParams' => array(
            'title' => 'FEEDBACK OF SAME CATEGORY',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity / Sorting Criteria',
                        'multiOptions' => array('views' => 'Most Viewed', 'total_votes' => 'Most Voted', 'comment_count' => 'Most Commented', 'feedback_id' => 'Recently Created'),
                        'value' => 'feedback_id',
                    )
                ),
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Number of Feedbacks to show',
                        'value' => 3,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                ),
                array(
                    'Text',
                    'truncationLimit',
                    array(
                        'label' => 'Title Truncation Limit',
                        'value' => 19,
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
        'title' => 'Feedback View / User\'s Feedbacks Page: Search Text Box',
        'description' => 'Displays search text box for searching the feedbacks created by the same owner of the feedback being currently viewed. This widget must be placed on Feedbacks - Feedback View page or Feedbacks - User\'s Feedbacks page in right/left column.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.search-box-feedbacks',
        'defaultParams' => array(
            'title' => 'Search Feedbacks',
            'titleCount' => true,
        ),
    ),
    array(
        'title' => 'Feedback View Page: Popular Tags',
        'description' => 'This widget displays the feedback tags added by the same owner of the feedback being currently viewed. This widget must be placed on Feedbacks - Feedback View page in right/left column.',
        'category' => 'Feedback',
        'type' => 'widget',
        'name' => 'feedback.owner-tags-feedbacks',
        'defaultParams' => array(
            'title' => "%s's Tags",
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'title',
                    array(
                        'label' => 'Title',
                        'description' => "Enter below the format in which you want to display the title of the widget. (Note: To display feedback owner’s name on feedback view page, enter title as: %s's Tags.)",
                        'value' => "%s's Tags",
                    )
                ),
            ),
        ),
    ),
);
