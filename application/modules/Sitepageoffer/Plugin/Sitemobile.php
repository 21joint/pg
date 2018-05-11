<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepage
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Plugin_Sitemobile {
  
  protected $_pagesTable;
  protected $_contentTable;
  
  public function onIntegrated() {
    
    $this->_pagesTable =  Engine_Api::_()->getApi('modules', 'sitemobile')->_pagesTable;
    $this->_contentTable =  Engine_Api::_()->getApi('modules', 'sitemobile')->_contentTable;
   //Page offers
    $this->addSitepageOffersProfileContent();
    $this->addSitepageOffersViewPage();
    $this->addSitepageOffersBrowsePage();
    $this->addSitepageOffersCreatePage();
    include APPLICATION_PATH . "/application/modules/Sitepageoffer/controllers/license/mobileLayoutCreation.php";
  }
  
  //Offers view page

  public function addSitepageOffersProfileContent() {


    // install content areas

    $db = Engine_Db_Table::getDefaultAdapter();
    $select = new Zend_Db_Select($db);

    // profile page
    $page_id =  Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('sitepage_index_view');

    // sitemobile.blog-profile-blogs
    // Check if it's already been placed
    $select = new Zend_Db_Select($db);
    $select
            ->from($this->_contentTable)
            ->where('page_id = ?', $page_id)
            ->where('type = ?', 'widget')
            ->where('name = ?', 'sitepageoffer.sitemobile-profile-sitepageoffers')
    ;
    $info = $select->query()->fetch();

    if (empty($info)) {

      // container_id (will always be there)
      $select = new Zend_Db_Select($db);
      $select
              ->from($this->_contentTable)
              ->where('page_id = ?', $page_id)
              ->where('type = ?', 'container')
              ->limit(1);
      $container_id = $select->query()->fetchObject()->content_id;

      // middle_id (will always be there)
      $select = new Zend_Db_Select($db);
      $select
              ->from($this->_contentTable)
              ->where('parent_content_id = ?', $container_id)
              ->where('type = ?', 'container')
              ->where('name = ?', 'middle')
              ->limit(1);
      $middle_id = $select->query()->fetchObject()->content_id;

      // tab_id (tab container) may not always be there
      $select
              ->reset('where')
              ->where('type = ?', 'widget')
              ->where('name = ?', 'sitemobile.container-tabs-columns')
              ->where('page_id = ?', $page_id)
              ->limit(1);
      $tab_id = $select->query()->fetchObject();
      if ($tab_id && @$tab_id->content_id) {
        $tab_id = $tab_id->content_id;
      } else {
        $tab_id = null;
      }

      // tab on profile
      $db->insert($this->_contentTable, array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitepageoffer.sitemobile-profile-sitepageoffers',
          'parent_content_id' => ($tab_id ? $tab_id : $middle_id),
          'order' => 1400,
          'params' => '{"title":"Offers","titleCount":true}',
      ));
    }
  }

  public function addSitepageOffersViewPage() {
    $db = Engine_Db_Table::getDefaultAdapter();


    $page_id =  Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('sitepageoffer_index_view');
    // insert if it doesn't exist yet
    if (!$page_id) {
      // Insert page
      $db->insert($this->_pagesTable, array(
          'name' => 'sitepageoffer_index_view',
          'displayname' => 'Directory / Pages - Offer View Page',
          'title' => 'Offer View Page',
          'description' => 'This is offer view page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      // Insert main
      $db->insert($this->_contentTable, array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
      ));
      $main_id = $db->lastInsertId();

      // Insert middle
      $db->insert($this->_contentTable, array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_id,
          'order' => 2,
      ));
      $middle_id = $db->lastInsertId();

      // Insert content
      $db->insert($this->_contentTable, array(
          'type' => 'widget',
          'name' => 'sitepageoffer.offer-content',
          'page_id' => $page_id,
          'parent_content_id' => $middle_id,
          'order' => 2,
      ));
      $db->insert($this->_contentTable, array(
          'type' => 'widget',
          'name' => 'sitemobile.comments',
          'page_id' => $page_id,
          'parent_content_id' => $middle_id,
          'order' => 3,
      ));
    }
  }

  //Offers view page
  public function addSitepageOffersBrowsePage() {
    $db = Engine_Db_Table::getDefaultAdapter();

    $page_id = Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('sitepageoffer_index_browse');
    // insert if it doesn't exist yet
    if (!$page_id) {
      // Insert page
      $db->insert($this->_pagesTable, array(
          'name' => 'sitepageoffer_index_browse',
          'displayname' => 'Directory / Pages - Browse Offers',
          'title' => 'Browse Offers',
          'description' => 'This is browse offer page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      // Insert main
      $db->insert($this->_contentTable, array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
      ));
      $main_id = $db->lastInsertId();

      // Insert middle
      $db->insert($this->_contentTable, array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_id,
          'order' => 2,
      ));
      $middle_id = $db->lastInsertId();

      $db->insert($this->_contentTable, array(
          'type' => 'widget',
          'name' => 'sitemobile.sitemobile-navigation',
          'page_id' => $page_id,
          'parent_content_id' => $middle_id,
          'order' => 1,
      ));
//      
      // Insert Advance search
      $db->insert($this->_contentTable, array(
          'type' => 'widget',
          'name' => 'sitemobile.sitemobile-advancedsearch',
          'page_id' => $page_id,
          'parent_content_id' => $middle_id,
          'params' => '{"search":"2","title":"","nomobile":"0","name":"sitemobile.sitemobile-advancedsearch"}',
          'order' => 2,
          'module' => 'sitemobile'
      ));
      // Insert content
      $db->insert($this->_contentTable, array(
          'type' => 'widget',
          'name' => 'sitepageoffer.sitepage-offer',
          'page_id' => $page_id,
          'parent_content_id' => $middle_id,
          'params' => '{"itemCount":"10"}',
          'order' => 3,
      ));
    }
  }

  public function addSitepageOffersCreatePage() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $page_id = Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('sitepageoffer_index_create');
    if (!$page_id) {

      // Insert page
      $db->insert($this->_pagesTable, array(
          'name' => 'sitepageoffer_index_create',
          'displayname' => 'Directory / Pages - Create Offer',
          'title' => 'Create new Offer',
          'description' => 'This is offer create page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      // Insert main
      $db->insert($this->_contentTable, array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => 1,
      ));
      $main_id = $db->lastInsertId();

      // Insert main-middle
      $db->insert($this->_contentTable, array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_id,
      ));
      $main_middle_id = $db->lastInsertId();

      // Insert content
      $db->insert($this->_contentTable, array(
          'type' => 'widget',
          'name' => 'core.content',
          'page_id' => $page_id,
          'parent_content_id' => $main_middle_id,
          'order' => 2,
      ));
    }
  }

}