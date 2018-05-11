<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content_user.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$isActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.isActivate', 0);
if (empty($isActive)) {
  return;
}
$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(
    array(
        'title' => $view->translate('Page Profile Music'),
        'description' => $view->translate('Forms the Music tab of your Page and shows music of your Page. It should be placed in the Tabbed Blocks area of the Page Profile.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagemusic.profile-sitepagemusic',
        'defaultParams' => array(
            'title' => $view->translate('Music'),
        ),
    ),
    array(
        'title' => $view->translate('Page Profile Most Popular Playlists'),
        'description' => $view->translate("Displays your Page's most popular playlists."),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagemusic.popular-sitepagemusic',
        'defaultParams' => array(
            'title' => $view->translate('Most Popular Playlists'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('Page Profile Most Commented Playlists'),
        'description' => $view->translate("Displays your Page's most commented playlists."),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagemusic.comment-sitepagemusic',
        'defaultParams' => array(
            'title' => $view->translate('Most Commented Playlists'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('Page Profile Most Recent Playlists'),
        'description' => $view->translate("Displays your Page's most recent playlists."),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagemusic.recent-sitepagemusic',
        'defaultParams' => array(
            'title' => $view->translate('Most Recent Playlists'),
            'titleCount' => true,
        ),
    ),
    array(
        'title' => $view->translate('Page Profile Most Liked Playlists'),
        'description' => $view->translate("Displays your Page's most liked playlists."),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagemusic.like-sitepagemusic',
        'defaultParams' => array(
            'title' => $view->translate('Most Liked Playlists'),
            'titleCount' => true,
        ),
    ),
    array(
	    'title' => $view->translate('Page Profile Player'),
	    'description' => $view->translate("Displays a music player that plays the playlist that you select to be played on your Page Profile."),
	    'category' => 'Page Profile',
	    'type' => 'widget',
	    'name' => 'sitepagemusic.profile-player',
   ),  
)
?>