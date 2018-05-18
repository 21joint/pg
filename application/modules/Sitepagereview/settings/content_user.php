<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content_user.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(
    array(
        'title' => $view->translate('Page Profile Popular Reviews'),
        'description' => $view->translate('Displays your Page\'s popular reviews.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagereview.popular-sitepagereviews',
        'defaultParams' => array(
            'title' => $view->translate('Most Popular Reviews'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('Page Profile Most Commented Reviews'),
        'description' => $view->translate('Displays your Page\'s most commented reviews.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagereview.comment-sitepagereviews',
        'defaultParams' => array(
            'title' => $view->translate('Most Commented Reviews'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('Page Profile Most Liked Reviews'),
        'description' => $view->translate('Displays your Page\'s most liked reviews.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagereview.like-sitepagereviews',
        'defaultParams' => array(
            'title' => $view->translate('Most Liked Reviews'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('Page Profile Reviews'),
        'description' => $view->translate('Forms the Reviews tab of your Page and shows reviews of your Page. It should be placed in the Tabbed Blocks area of the Page Profile.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagereview.profile-sitepagereviews',
        'defaultParams' => array(
            'title' => $view->translate('Reviews'),
        ),
    ),
    array(
        'title' => $view->translate('Page Profile Review Rating'),
        'description' => $view->translate('Displays your Page\'s review ratings.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagereview.ratings-sitepagereviews',
        'defaultParams' => array(
            'title' => $view->translate('Ratings'),
        ),
    ),
)
?>