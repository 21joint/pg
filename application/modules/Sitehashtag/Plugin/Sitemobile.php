<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_Plugin_Sitemobile extends Zend_Controller_Plugin_Abstract {
  protected $_pagesTable;

  protected $_contentTable;

  public function onIntegrated($pageTable, $contentTable) {
    $this->_pagesTable = $pageTable;
    $this->_contentTable = $contentTable;


    $this->addSearchedResultsPage();
  }

  public function addSearchedResultsPage() {
    $db = Engine_Db_Table::getDefaultAdapter();

    $page_id = Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('sitehashtag_index_index');
    // insert if it doesn't exist yet
    if ($page_id) {
      return;
    }
    // Insert page
    $db->insert($this->_pagesTable, array(
      'name' => 'sitehashtag_index_index',
      'displayname' => 'Hashtag - Searched Results Page',
      'title' => 'Hashtag - Searched Results Page',
      'description' => 'This page displays activity feeds searched using hashtags.',
      'custom' => 0,
    ));
    $page_id = $db->lastInsertId();

    // Insert main
    $db->insert($this->_contentTable, array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
    ));
    $main_id = $db->lastInsertId();

    // Insert main-middle
    $db->insert($this->_contentTable, array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 2,
    ));
    $main_middle_id = $db->lastInsertId();

    //Insert search
    $db->insert($this->_contentTable, array(
        'type' => 'widget',
        'name' => 'sitemobile.sitemobile-advancedsearch',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'params' => '{"search":"1","module_search":"0","title":"","name":"sitemobile.sitemobile-advancedsearch"}',
        'order' => 3,
        'module' => 'sitemobile',
    ));
    $db->insert($this->_contentTable, array(
      'type' => 'widget',
      'name' => 'sitemobile.sitemobile-advfeed',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 4,
      'module' => 'advancedactivity',
      'params' => '{"title":""}'
    ));
  }

}
