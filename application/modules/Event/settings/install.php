<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: install.php 9903 2013-02-14 02:38:27Z shaun $
 * @author     Steve
 */

/**
 * @category   Application_Extensions
 * @package    Event
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Event_Installer extends Engine_Package_Installer_Module
{
  protected $_dropColumnsOnPreInstall = array(
    '4.9.0' => array(
      'engine4_event_events' => array('like_count', 'comment_count'),
      'engine4_event_albums' => array('like_count'),
      'engine4_event_photos' => array('like_count')
    )
  );

  public function onInstall()
  {
    $this->_addMobileEventProfileContent();
    $this->_addContentEventProfile();
    $this->_addContentMemberHome();
    $this->_addContentMemberProfile();

    $this->_addEventBrowsePage();
    $this->_addEventCreatePage();
    $this->_addEventManagePage();

    parent::onInstall();
  }

  protected function _addEventManagePage()
  {
    $db = $this->getDb();

    // profile page
    $pageId = $db->select()
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'event_index_manage')
      ->limit(1)
      ->query()
      ->fetchColumn();

    // insert if it doesn't exist yet
    if( !$pageId ) {
      // Insert page
      $db->insert('engine4_core_pages', array(
        'name' => 'event_index_manage',
        'displayname' => 'Event Manage Page',
        'title' => 'My Events',
        'description' => 'This page lists a user\'s events.',
        'custom' => 0,
      ));
      $pageId = $db->lastInsertId();

      // Insert top
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'top',
        'page_id' => $pageId,
        'order' => 1,
      ));
      $topId = $db->lastInsertId();

      // Insert main
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'main',
        'page_id' => $pageId,
        'order' => 2,
      ));
      $mainId = $db->lastInsertId();

      // Insert top-middle
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $pageId,
        'parent_content_id' => $topId,
      ));
      $topMiddleId = $db->lastInsertId();

      // Insert main-middle
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $pageId,
        'parent_content_id' => $mainId,
        'order' => 2,
      ));
      $mainMiddleId = $db->lastInsertId();

      // Insert main-right
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'right',
        'page_id' => $pageId,
        'parent_content_id' => $mainId,
        'order' => 1,
      ));
      $mainRightId = $db->lastInsertId();


      // Insert menu
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'event.browse-menu',
        'page_id' => $pageId,
        'parent_content_id' => $topMiddleId,
        'order' => 1,
      ));

      // Insert content
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'core.content',
        'page_id' => $pageId,
        'parent_content_id' => $mainMiddleId,
        'order' => 1,
      ));

      // Insert search
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'event.browse-search',
        'page_id' => $pageId,
        'parent_content_id' => $mainRightId,
        'order' => 1,
      ));

      // Insert gutter menu
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'event.browse-menu-quick',
        'page_id' => $pageId,
        'parent_content_id' => $mainRightId,
        'order' => 2,
      ));
    }
  }

  protected function _addEventCreatePage()
  {
    $db = $this->getDb();

    // profile page
    $pageId = $db->select()
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'event_index_create')
      ->limit(1)
      ->query()
      ->fetchColumn();

    // insert if it doesn't exist yet
    if( !$pageId ) {
      // Insert page
      $db->insert('engine4_core_pages', array(
        'name' => 'event_index_create',
        'displayname' => 'Event Create Page',
        'title' => 'Event Create',
        'description' => 'This page allows users to create events.',
        'custom' => 0,
      ));
      $pageId = $db->lastInsertId();

      // Insert top
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'top',
        'page_id' => $pageId,
        'order' => 1,
      ));
      $topId = $db->lastInsertId();

      // Insert main
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'main',
        'page_id' => $pageId,
        'order' => 2,
      ));
      $mainId = $db->lastInsertId();

      // Insert top-middle
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $pageId,
        'parent_content_id' => $topId,
      ));
      $topMiddleId = $db->lastInsertId();

      // Insert main-middle
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $pageId,
        'parent_content_id' => $mainId,
        'order' => 2,
      ));
      $mainMiddleId = $db->lastInsertId();

      // Insert menu
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'event.browse-menu',
        'page_id' => $pageId,
        'parent_content_id' => $topMiddleId,
        'order' => 1,
      ));

      // Insert content
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'core.content',
        'page_id' => $pageId,
        'parent_content_id' => $mainMiddleId,
        'order' => 1,
      ));

    }

  }

  protected function _addEventBrowsePage()
  {
    $db = $this->getDb();

    // profile page
    $pageId = $db->select()
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'event_index_browse')
      ->limit(1)
      ->query()
      ->fetchColumn();

    // insert if it doesn't exist yet
    if( !$pageId ) {
      // Insert page
      $db->insert('engine4_core_pages', array(
        'name' => 'event_index_browse',
        'displayname' => 'Event Browse Page',
        'title' => 'Event Browse',
        'description' => 'This page lists events.',
        'custom' => 0,
      ));
      $pageId = $db->lastInsertId();

      // Insert top
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'top',
        'page_id' => $pageId,
        'order' => 1,
      ));
      $topId = $db->lastInsertId();

      // Insert main
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'main',
        'page_id' => $pageId,
        'order' => 2,
      ));
      $mainId = $db->lastInsertId();

      // Insert top-middle
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $pageId,
        'parent_content_id' => $topId,
      ));
      $topMiddleId = $db->lastInsertId();

      // Insert main-middle
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $pageId,
        'parent_content_id' => $mainId,
        'order' => 2,
      ));
      $mainMiddleId = $db->lastInsertId();

      // Insert main-right
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'right',
        'page_id' => $pageId,
        'parent_content_id' => $mainId,
        'order' => 1,
      ));
      $mainRightId = $db->lastInsertId();

      // Insert banner
     $db->insert('engine4_core_banners', array(
        'name' => 'event',
        'module' => 'event',
        'title' => 'Your Source for Events',
        'body' => 'Don’t miss the action! Create, join and explore Events.',
        'photo_id' => 0,
        'params' => '{"label":"Create New Event","route":"event_general","routeParams":{"action":"create"}}',
        'custom' => 0
      ));
      $bannerId = $db->lastInsertId();

      if( $bannerId ) {
        $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'core.banner',
          'page_id' => $pageId,
          'parent_content_id' => $topMiddleId,
          'params' => '{"title":"","name":"core.banner","banner_id":"'. $bannerId .'","nomobile":"0"}',
          'order' => 1,
        ));
      }

      // Insert menu
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'event.browse-menu',
        'page_id' => $pageId,
        'parent_content_id' => $topMiddleId,
        'order' => 2,
      ));

      // Insert content
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'core.content',
        'page_id' => $pageId,
        'parent_content_id' => $mainMiddleId,
        'order' => 1,
      ));

      // Insert search
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'event.browse-search',
        'page_id' => $pageId,
        'parent_content_id' => $mainRightId,
        'order' => 1,
      ));

      // Insert gutter menu
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'event.browse-menu-quick',
        'page_id' => $pageId,
        'parent_content_id' => $mainRightId,
        'order' => 2,
      ));
      // Insert list categories
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'event.list-categories',
        'page_id' => $pageId,
        'parent_content_id' => $mainRightId,
        'order' => 3,
      ));
    }
  }

  protected function _addMobileEventProfileContent()
  {
    //
    // Mobile Event Profile
    //
    // page


    // Check if it's already been placed
    $db = $this->getDb();
    $select = new Zend_Db_Select($db);
    $select
      ->from('engine4_core_pages')
      ->where('name = ?', 'mobi_event_profile')
      ->limit(1);
      ;
    $info = $select->query()->fetch();

    if( empty($info) ) {
      $db->insert('engine4_core_pages', array(
        'name' => 'mobi_event_profile',
        'displayname' => 'Mobile Event Profile',
        'title' => 'Mobile Event Profile',
        'description' => 'This is the mobile verison of an event profile.',
        'custom' => 0
      ));
      $pageId = $db->lastInsertId('engine4_core_pages');

      // containers
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'container',
        'name' => 'main',
        'parent_content_id' => null,
        'order' => 1,
        'params' => '',
      ));
      $containerId = $db->lastInsertId('engine4_core_content');

      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'container',
        'name' => 'middle',
        'parent_content_id' => $containerId,
        'order' => 2,
        'params' => '',
      ));
      $middleId = $db->lastInsertId('engine4_core_content');

      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-status',
        'parent_content_id' => $middleId,
        'order' => 3,
        'params' => '',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-photo',
        'parent_content_id' => $middleId,
        'order' => 4,
        'params' => '',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-info',
        'parent_content_id' => $middleId,
        'order' => 5,
        'params' => '',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-rsvp',
        'parent_content_id' => $middleId,
        'order' => 6,
        'params' => '',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'core.container-tabs',
        'parent_content_id' => $middleId,
        'order' => 7,
        'params' => '{"max":6}',
      ));
      $tabId = $db->lastInsertId('engine4_core_content');

      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'activity.feed',
        'parent_content_id' => $tabId,
        'order' => 8,
        'params' => '{"title":"What\'s New"}',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-members',
        'parent_content_id' => $tabId,
        'order' => 9,
        'params' => '{"title":"Guests","titleCount":true}',
      ));
    }
  }

  protected function _addContentMemberHome()
  {
    $db = $this->getDb();
    $select = new Zend_Db_Select($db);

    // Get page id
    $pageId = $select
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'user_index_home')
      ->limit(1)
      ->query()
      ->fetchColumn(0)
      ;

    // event.home-upcoming

    // Check if it's already been placed
    $select = new Zend_Db_Select($db);
    $hasWidget = $select
      ->from('engine4_core_content', new Zend_Db_Expr('TRUE'))
      ->where('page_id = ?', $pageId)
      ->where('type = ?', 'widget')
      ->where('name = ?', 'event.home-upcoming')
      ->query()
      ->fetchColumn()
      ;

    // Add it
    if( !$hasWidget ) {

      // container_id (will always be there)
      $select = new Zend_Db_Select($db);
      $containerId = $select
        ->from('engine4_core_content', 'content_id')
        ->where('page_id = ?', $pageId)
        ->where('type = ?', 'container')
        ->limit(1)
        ->query()
        ->fetchColumn()
        ;

      // middle_id (will always be there)
      $select = new Zend_Db_Select($db);
      $rightId = $select
        ->from('engine4_core_content', 'content_id')
        ->where('parent_content_id = ?', $containerId)
        ->where('type = ?', 'container')
        ->where('name = ?', 'right')
        ->limit(1)
        ->query()
        ->fetchColumn()
        ;

      // insert
      if( $rightId ) {
        $db->insert('engine4_core_content', array(
          'page_id' => $pageId,
          'type'    => 'widget',
          'name'    => 'event.home-upcoming',
          'parent_content_id' => $rightId,
          'order'   => 1,
          'params'  => '{"title":"Upcoming Events","titleCount":true}',
        ));
      }
    }
  }

  protected function _addContentMemberProfile()
  {
    $db = $this->getDb();
    $select = new Zend_Db_Select($db);

    // Get page id
    $pageId = $select
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'user_profile_index')
      ->limit(1)
      ->query()
      ->fetchColumn(0)
      ;

    // event.profile-events

    // Check if it's already been placed
    $select = new Zend_Db_Select($db);
    $hasProfileEvents = $select
      ->from('engine4_core_content', new Zend_Db_Expr('TRUE'))
      ->where('page_id = ?', $pageId)
      ->where('type = ?', 'widget')
      ->where('name = ?', 'event.profile-events')
      ->query()
      ->fetchColumn()
      ;

    // Add it
    if( !$hasProfileEvents ) {

      // container_id (will always be there)
      $select = new Zend_Db_Select($db);
      $containerId = $select
        ->from('engine4_core_content', 'content_id')
        ->where('page_id = ?', $pageId)
        ->where('type = ?', 'container')
        ->limit(1)
        ->query()
        ->fetchColumn()
        ;

      // middle_id (will always be there)
      $select = new Zend_Db_Select($db);
      $middleId = $select
        ->from('engine4_core_content', 'content_id')
        ->where('parent_content_id = ?', $containerId)
        ->where('type = ?', 'container')
        ->where('name = ?', 'middle')
        ->limit(1)
        ->query()
        ->fetchColumn()
        ;

      // tab_id (tab container) may not always be there
      $select = new Zend_Db_Select($db);
      $select
        ->from('engine4_core_content', 'content_id')
        ->where('type = ?', 'widget')
        ->where('name = ?', 'core.container-tabs')
        ->where('page_id = ?', $pageId)
        ->limit(1);
      $tabId = $select->query()->fetchObject();
      if( $tabId && @$tabId->content_id ) {
          $tabId = $tabId->content_id;
      } else {
        $tabId = $middleId;
      }

      // insert
      if( $tabId ) {
        $db->insert('engine4_core_content', array(
          'page_id' => $pageId,
          'type'    => 'widget',
          'name'    => 'event.profile-events',
          'parent_content_id' => $tabId,
          'order'   => 8,
          'params'  => '{"title":"Events","titleCount":true}',
        ));
      }
    }
  }

  protected function _addContentEventProfile()
  {
    $db = $this->getDb();
    $select = new Zend_Db_Select($db);

    // Check if it's already been placed
    $select = new Zend_Db_Select($db);
    $hasWidget = $select
      ->from('engine4_core_pages', new Zend_Db_Expr('TRUE'))
      ->where('name = ?', 'event_profile_index')
      ->limit(1)
      ->query()
      ->fetchColumn()
      ;

    // Add it
    if( empty($hasWidget) ) {

      $db->insert('engine4_core_pages', array(
        'name' => 'event_profile_index',
        'displayname' => 'Event Profile',
        'title' => 'Event Profile',
        'description' => 'This is the profile for an event.',
        'custom' => 0,
        'provides' => 'subject=event',
      ));
      $pageId = $db->lastInsertId('engine4_core_pages');

      // containers
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'container',
        'name' => 'main',
        'parent_content_id' => null,
        'order' => 1,
        'params' => '',
      ));
      $containerId = $db->lastInsertId('engine4_core_content');

      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'container',
        'name' => 'middle',
        'parent_content_id' => $containerId,
        'order' => 3,
        'params' => '',
      ));
      $middleId = $db->lastInsertId('engine4_core_content');

      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'container',
        'name' => 'left',
        'parent_content_id' => $containerId,
        'order' => 1,
        'params' => '',
      ));
      $leftId = $db->lastInsertId('engine4_core_content');

      // middle column
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'core.container-tabs',
        'parent_content_id' => $middleId,
        'order' => 2,
        'params' => '{"max":"6"}',
      ));
      $tabId = $db->lastInsertId('engine4_core_content');

      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-status',
        'parent_content_id' => $middleId,
        'order' => 1,
        'params' => '',
      ));

      // left column
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-photo',
        'parent_content_id' => $leftId,
        'order' => 1,
        'params' => '',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-options',
        'parent_content_id' => $leftId,
        'order' => 2,
        'params' => '',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-info',
        'parent_content_id' => $leftId,
        'order' => 3,
        'params' => '',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-rsvp',
        'parent_content_id' => $leftId,
        'order' => 4,
        'params' => '',
      ));

      // tabs
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'activity.feed',
        'parent_content_id' => $tabId,
        'order' => 1,
        'params' => '{"title":"Updates"}',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-members',
        'parent_content_id' => $tabId,
        'order' => 2,
        'params' => '{"title":"Guests","titleCount":true}',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-photos',
        'parent_content_id' => $tabId,
        'order' => 3,
        'params' => '{"title":"Photos","titleCount":true}',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'event.profile-discussions',
        'parent_content_id' => $tabId,
        'order' => 4,
        'params' => '{"title":"Discussions","titleCount":true}',
      ));
      $db->insert('engine4_core_content', array(
        'page_id' => $pageId,
        'type' => 'widget',
        'name' => 'core.profile-links',
        'parent_content_id' => $tabId,
        'order' => 5,
        'params' => '{"title":"Links","titleCount":true}',
      ));
    }
  }
}
