<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    content.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    array(
        'title' => 'Top Trends',
        'description' => ' This widget displays the most used hashtags within a particular duration, entered in widget settings. You can configure various settings for this widget from the Edit settings section of this widget.',
        'category' => 'Hashtag',
        'type' => 'widget',
        'name' => 'sitehashtag.hashtags',
        'requirements' => array(
            'no-subject',
        ),
        'defaultParams' => array(
            'title' => 'Top Trends',
        ),
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'duration',
                    array(
                        'label' => 'Top hashtags, most frequently used in last days:',
                        'allowEmpty' => false,
                        'value' => 30,
                    ),
                    'validators' => array(
                        array('Int', true),
                        array('GreaterThan', true, array(0)),
                    ),
                ),
                array(
                    'Text',
                    'tag_count',
                    array(
                        'label' => 'Count',
                        'allowEmpty' => false,
                        'value' => 10,
                    ),
                    'validators' => array(
                        array('Int', true),
                        array('GreaterThan', true, array(0)),
                    ),
                ),
            ),
        ),
    ),
    array(
        'title' => 'Search Hashtags Form',
        'description' => 'This widget searches hashtags over various popularity criterias. This widget can be placed anywhere on the site.',
        'category' => 'Hashtag',
        'type' => 'widget',
        'name' => 'sitehashtag.search-form',
        'requirements' => array(
            'no-subject',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'search_criteria',
                    array(
                        'label' => 'Search Criteria',
                        'description' => '',
                        'multiOptions' => array(
                            'modified_date' => 'Recently Created',
                            'tag_count' => 'Most Used',
                            'text' => 'Alphabetical Order',
                        ),
                        'value' => 'tag_count',
                    ),
                ),
                array(
                    'Text',
                    'textWidth',
                    array(
                        'label' => 'Width for Search Box',
                        'allowEmpty' => false,
                        'value' => 800,
                    ),
                    'validators' => array(
                        array('Int', true),
                        array('GreaterThan', true, array(0)),
                    ),
                ),
                array(
                    'Text',
                    'limit',
                    array(
                        'label' => 'No. of hashtags to be shown in autosuggest.',
                        'allowEmpty' => false,
                        'value' => 20,
                    ),
                    'validators' => array(
                        array('Int', true),
                        array('GreaterThan', true, array(0)),
                    ),
                ),
            ),
        ),
    ),
);
