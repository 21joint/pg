<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereview
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereviewlistingtype_Api_CoreSM extends Core_Api_Abstract {

  protected $_viewType = 'listview';
  protected $_layoutViews = '["listview", "gridview"]';
  protected $_layoutOrder = '1';
  protected $_layoutBrowseViews = '["1", "2"]';
  public function defaultTemplate($listingType, $template_type, $pageTable, $contentTable) {

        if (empty($listingType) || empty($template_type))
            return;

        $methodNameHome = $template_type . 'HomeTemplate';
        if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages' || in_array($methodNameHome, array('food', 'article', 'tourism', 'entertainment', 'fashion'))) {
          $this->_viewType = 'gridview';
          $this->_layoutViews = ($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages') ? '["gridview"]' : '["listview", "gridview"]';
        }
        
        $this->$methodNameHome($listingType, $pageTable, $contentTable);
        
        $methodNameBrowse = $template_type . 'BrowseTemplate';
        if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages' || in_array($methodNameBrowse, array('food', 'article', 'tourism', 'entertainment', 'fashion'))) {
          $this->_layoutOrder = '2';
          $this->_layoutBrowseViews = ($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages') ? '["2"]' : '["1", "2"]';
        }    
        $this->$methodNameBrowse($listingType, $pageTable, $contentTable);

        $methodNameProfile = $template_type . 'ProfileTemplate';
        $this->$methodNameProfile($listingType, $pageTable, $contentTable);
        $this->mostratedPageCreate($listingType, $pageTable, $contentTable);
    }

    public function foodHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);

        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();
            $columnHeight = 218;
            $columnWidth = 200;
            $db->query('
  
 INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"title":"","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 10, \'{"title":"Most Reviewed","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"review_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Featured","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Sponsored","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Recent","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL)
');
            //PLACE CATEGORY WIDGET FOR MOBILE APP AND TABLET APP.
     $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));
        }
    }

    public function articleHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);

        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) { 

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            
            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();
            $columnHeight = 255;
            $columnWidth = 200;
            $db->query('
  
 INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"title":"","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Featured","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"bottomLine":"2","bottomLineGrid":"2","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Sponsored","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"bottomLine":"2","bottomLineGrid":"2","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Top Rated","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"bottomLine":"2","bottomLineGrid":"2","columnHeight":"' . $columnHeight . '","popularity":"rating_avg","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Recent","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL)
');
           
  $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));          
     
    }
    }

    public function tourismHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 270;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 10, \'{"title":"Sponsored","titleCount":true,"statistics":["commentCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Featured","titleCount":true,"statistics":["commentCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","commentCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Most Reviewed","titleCount":true,"statistics":["commentCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location","endDate"],"columnHeight":"' . $columnHeight . '","popularity":"review_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Recent","titleCount":true,"statistics":["commentCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location"],"columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL)

');
      //PLACE CATEGORY WIDGET FOR MOBILE APP AND TABLET APP.
      $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));
        }
    }

    public function blogHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 236;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
    INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 10, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","commentCount"],"viewType":"'. $this->_viewType . '","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"bottomLine":"2","bottomLineGrid":"2","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}
\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Featured","titleCount":true,"statistics":["viewCount","commentCount"],"viewType":"'. $this->_viewType . '","columnWidth":"' . $columnWidth . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Sponsored","titleCount":true,"statistics":["viewCount","commentCount"],"viewType":"'. $this->_viewType . '","columnWidth":"' . $columnWidth . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Recent","titleCount":true,"statistics":["viewCount","commentCount"],"viewType":"'. $this->_viewType . '","columnWidth":"' . $columnWidth . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}\', NULL)
');
            //PLACE CATEGORY WIDGET FOR MOBILE APP AND TABLET APP.
      $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));
        }
    }

    public function fashionHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 236;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 10, \'{"title":"Featured","titleCount":true,"statistics":["viewCount","likeCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWith":"200"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Sponsored","titleCount":true,"statistics":["viewCount","likeCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWith":"200"}
\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","likeCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWith":"200"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Most Reviewed","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"review_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWith":"200"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Recent","titleCount":true,"statistics":["viewCount","likeCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWith":"200"}\', NULL)

');
            $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));
    
        }
    }

    public function electronicHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 234;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),

(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 10, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","columnWidth":"' . $columnWidth . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","endDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Featured","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","columnWidth":"' . $columnWidth . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","endDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Most Reviewed","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","columnWidth":"' . $columnWidth . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location","endDate"],"columnHeight":"' . $columnHeight . '","popularity":"review_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Recent","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","columnWidth":"' . $columnWidth . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","endDate"],"columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Sponsored","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","columnWidth":"' . $columnWidth . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","endDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview"}\', NULL)
');
            //PLACE CATEGORY WIDGET FOR MOBILE APP AND TABLET APP.
      $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));
        }
    }

    public function sportHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 250;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
  
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 10, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Featured","titleCount":true,"statistics":["viewCount","likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Sponsored","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Most Reviewed","titleCount":true,"statistics":["viewCount","likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"review_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Recent","titleCount":true,"statistics":["viewCount","likeCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview","columnWidth":"' . $columnWidth . '"}\', NULL)

');
            //PLACE CATEGORY WIDGET FOR MOBILE APP AND TABLET APP.
      $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));
        }
    }

    public function classifiedHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('

INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
  
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.categories-home", ' . $main_middle_id . ', 5, \'{"title":"","titleCount":true,"showAllCategories":"1","show2ndlevelCategory":"1","show3rdlevelCategory":"1","orderBy":"category_name","showCount":"0","name":"sitereview.categories-home"}\', NULL)

');
        }
    }

    public function propertyHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 358;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('

INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
  
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 10, \'{"title":"Featured","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location","endDate","postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Sponsored","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location","endDate","postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Expiring Soon","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location","endDate", "postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"end_date","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location","endDate","postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Most Reviewed","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location","endDate","postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"review_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}
\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 60, \'{"title":"Recent","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"'. $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price","location","endDate","postedDate"],"columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL)

');
   $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));
        }
    }

    public function entertainmentHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 236;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 10, \'{"title":"Most Reviewed","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"review_count","postedby":"0","itemCount":"10","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Featured","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Sponsored","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"0","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Recent","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":["price"],"columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"0","itemCount":"10","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL)
  
');
            $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));
        }
    }

    public function foodBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 270;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            
            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb . 
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","reviewCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"1","postedby":"0","orderby":"spfesp","itemCount":"9","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '", "showContent":"","bottomLineGrid":1}\', NULL)
   
');
        }
    }

    public function articleBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 270;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

             $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            
            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb .
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","likeCount","commentCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"2","postedby":"1","orderby":"spfesp","itemCount":"9","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '", "showContent":["postedDate"],"bottomLineGrid":2}\', NULL)
   
');
        }
    }

    public function tourismBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 344;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
            
            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            $db->query('
 INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),' . $browseBreadcrumb . 

'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ',6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["commentCount","reviewCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"2","postedby":"0","orderby":"spfesp","itemCount":"9","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '","showContent":["price","location"],"bottomLineGrid":2}\', NULL)
');
        }
    }

    public function blogBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 270;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            
            $db->query('
     
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb . 
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","commentCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"1","postedby":"1","orderby":"spfesp","itemCount":"10","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '", "showContent":["postedDate"],"bottomLineGrid":"1"}\', NULL)
');
        }
    }

    public function fashionBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 270;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            $db->query('
  
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb . 
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","likeCount","reviewCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"1","postedby":"0","orderby":"spfesp","itemCount":"10","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '","bottomLineGrid":"1"}\', NULL)
');
        }
    }

    public function electronicBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 290;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            $db->query('
        
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb . 
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","likeCount","reviewCount"],"columnWidth":"' . $columnWidth . '","truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"1","postedby":"0","orderby":"spfesp","itemCount":"9","truncation":"100","name":"sitereview.browse-listings-sitereview","bottomLineGrid":"1"}\', NULL)
');
        }
    }

    public function sportBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 270;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            $db->query('

INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb . 
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","likeCount","reviewCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"1","postedby":"1","orderby":"spfesp","itemCount":"10","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '","bottomLineGrid":"1","showContent":["postedDate"]}\', NULL)
');
        }
    }

    public function classifiedBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 356;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';

            $db->query('

INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb . 
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","likeCount","commentCount","reviewCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","postedby":"1","orderby":"spfesp","itemCount":"10","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '", "showContent":["price","location","endDate","postedDate"],"bottomLineGrid":2,"bottomLine":2}\', NULL)
');
        }
    }

    public function propertyBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 358;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            $db->query('
INSERT INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb . 
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","likeCount","commentCount","reviewCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"1","postedby":"1","orderby":"spfesp","itemCount":"10","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '","bottomLineGrid":"1","showContent":["postedDate","location","endDate","price"]}\', NULL)         
');
        }
    }

    public function entertainmentBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 290;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            $db->query('
        
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb . 
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","likeCount","reviewCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"1","postedby":"0","orderby":"spfesp","itemCount":"9","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '","showContent":["price"],"bottomLineGrid":"1"}\', NULL)
');
        }
    }

    public function foodProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
            if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
              $db->query('
  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'["[]"]\', NULL)

');
            }
            $db->query('
  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","tags","location","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL)

');
            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 20,
                'params' => '{"max":"5"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES        
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 21, \'{"title":"Preparation","titleCount":"true","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 22, \'{"title":"Reviews","titleCount":"true","itemProsConsCount":"5","itemReviewsCount":"5","nomobile":"0","name":"sitereview.sitemobile-user-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 23, \'{"title":"Nutrition","titleCount":"true","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 24, \'{"title":"Buy Ingredients","titleCount":"true","layout_column":"0","limit":"4","nomobile":"1","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 25, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 26, \'{"title":"Photos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 27, \'{"title":"Buy Ingredients","titleCount":"true","layout_column":"1","limit":"2","nomobile":"1","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 28, \'{"title":"Best Alternative Recipes","titleCount":"true","statistics":["reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"30","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 29, \'{"title":"More from %s","titleCount":"true","statistics":["reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"30","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 30, \'{"title":"From same Category","titleCount":"true","statistics":["reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"gridview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"30","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 31, \'{"title":"Recently Viewed by You","titleCount":"true","statistics":["reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","show":"0","viewType":"gridview","columnHeight":"' . $columnHeight . '","truncation":"30","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),

(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
');
        }
    }

    public function articleProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
            if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
        $db->query('
  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'["[]"]\', NULL)

');
      }
            $db->query('
  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","tags","location","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL)

');
            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 20,
                'params' => '{"max":"5"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES        
(' . $page_id . ', "widget", "sitereview.editor-reviews-sitereview", ' . $tab_id . ', 21, \'{"titleEditor":"Review","titleOverview":"Overview","titleDescription":"Description","titleCount":"","title":"","show_slideshow":"0","slideshow_height":"400","slideshow_width":"600","showCaption":"1","showButtonSlide":"1","mouseEnterEvent":"0","thumbPosition":"bottom","autoPlay":"0","slidesLimit":"20","captionTruncation":"200","showComments":"1","name":"siteevent.editor-reviews-siteevent"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 22, \'{"title":"User Reviews","titleCount":"true","itemProsConsCount":"5","itemReviewsCount":"5","nomobile":"0","name":"sitereview.sitemobile-user-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 23, \'{"title":"Specs","titleCount":"true","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 25, \'{"title":"Photos","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 26, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 28, \'{"title":"Best Alternative Articles","titleCount":"true","statistics":["viewCount","likeCount","commentCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"30","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 29, \'{"title":"More from %s","titleCount":"true","statistics":["viewCount","likeCount","commentCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"30","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 30, \'{"title":"From same Category","titleCount":"true","statistics":["viewCount","likeCount","commentCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"30","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 31, \'{"title":"Recently Viewed by You","titleCount":"true","statistics":["viewCount","likeCount","commentCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","show":"0","viewType":"listview","columnHeight":"' . $columnHeight . '","truncation":"30","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),

(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
');
        }
    }

    public function tourismProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
            if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
              $db->query('       
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'{"title":"","nomobile":"0","name":"sitereview.list-profile-breadcrumb"}\', NULL)
');
            }
            $db->query('
        
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","tags","location","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"1","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $main_middle_id . ', 6, \'{"itemCount":"5"}\', NULL)
');
            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 8,
                'params' => '{"max":"6"}',
            ));
            $tab_id = $db->lastInsertId();
            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.editor-reviews-sitereview", ' . $tab_id . ', 9, \'{"titleEditor":"Editor Review","titleOverview":"Overview","titleDescription":"Description","titleCount":"","title":"","show_slideshow":"1","slideshow_height":"450","slideshow_width":"800","showCaption":"1","captionTruncation":"200","showComments":"1","nomobile":"0","name":"sitereview.editor-reviews-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 10, \'{"title":"User Reviews","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 11, \'{"title":"Specs","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 12, \'{"title":"Overview","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 13, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 15, \'{"title":"Photos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 16, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 17, \'{"title":"Discussions","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 18, \'{"title":"Where to Book","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 19, \'{"title":"Best Alternatives","titleCount":"true","statistics":["commentCount","reviewCount"],"ratingType":"rating_avg","columnHeight":"' . $columnHeight . '","viewType":"gridview","itemCount":"3","truncation":"90","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 20, \'{"title":"%s\'\'s Listings","titleCount":"true","statistics":["commentCount","reviewCount"],"ratingType":"rating_avg","columnHeight":"' . $columnHeight . '","viewType":"gridview","count":"3","truncation":"90","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 21, \'{"title":"Related Listings","titleCount":"true","statistics":["commentCount","reviewCount"],"ratingType":"rating_avg","related":"tags","columnHeight":"' . $columnHeight . '","viewType":"gridview","itemCount":"3","truncation":"90","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 22, \'{"title":"Recently Viewed By You","titleCount":"true","statistics":["commentCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"gridview","columnHeight":"' . $columnHeight . '","truncation":"90","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),

(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
');
        }
    }

    public function blogProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $columnHeight = 260;
        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);

        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
            if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
              $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'[""]\', NULL)  
');
            }
            

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
  
            
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy", "photo"],"like_button":"0","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $main_middle_id . ', 10, \'{"title":"Body","titleCount":"true","showAfterEditorReview":"2","showComments":"0","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL)
  
');
            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 12,
                'params' => '{"max":"6"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitemobile.comments", ' . $tab_id . ', 13, \'{"title":"Comments","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 14, \'{"title":"Specs","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 15, \'{"title":"Photos","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 16, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 17, \'{"title":"References","titleCount":"true","layout_column":"0","limit":"20","nomobile":"0","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),

(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 18, \'{"title":"Map","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 19, \'{"title":"More from %s","titleCount":"true","statistics":["viewCount","commentCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"21","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 20, \'{"title":"Related Blogs","titleCount":"true","statistics":["viewCount","commentCount"],"viewType":"listview","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"' . $columnHeight . '","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 21, \'{"title":"Recently Viewed By You","titleCount":"true","statistics":["viewCount","commentCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnHeight":"' . $columnHeight . '","truncation":"90","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),


(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)

');
        }
    }

    public function fashionProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
            if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
        $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'{"title":"","nomobile":"1","name":"sitereview.list-profile-breadcrumb"}\', NULL)');
      }
            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","photosCarousel","tags","location","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $main_middle_id . ', 6, \'{"itemCount":"5"}\', NULL)');

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 21,
                'params' => '{"max":"6"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 23, \'{"title":"Overview","titleCount":"true","showComments":"1","showAfterEditorReview":"2","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 24, \'{"title":"User Reviews","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 25, \'{"title":"Details","titleCount":"true","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 26, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","limit":"","nomobile":"0","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 27, \'{"title":"Photos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 28, \'{"title":"Best Pick","titleCount":"true","statistics":["likeCount","viewCount"],"ratingType":"rating_avg","viewType":"0","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 29, \'{"title":"Related Items","titleCount":"true","statistics":["likeCount","viewCount"],"ratingType":"rating_avg","related":"categories","viewType":"gridview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 30, \'{"title":"%s\'\'s Items","titleCount":"true","statistics":["likeCount","viewCount"],"ratingType":"rating_avg","viewType":"gridview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"19","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),

(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)

');
        }
    }

    public function electronicProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $columnHeight = 260;
        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);

        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
            if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
              $db->query('
    INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'{"title":"","nomobile":"1","name":"sitereview.list-profile-breadcrumb"}\', NULL)');
            }
            $db->query('
    INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","photosCarousel","tags","location","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $main_middle_id . ', 6, \'{"itemCount":"5"}\', NULL)');


            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 9,
                'params' => '{"max":"6"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.editor-reviews-sitereview", ' . $tab_id . ', 10, \'{
        "titleEditor":"Editor Review", "titleOverview":"Overview", "titleDescription":"Slideshow", "titleCount":"", "title":"", "show_slideshow":"1", "slideshow_height":"450", "slideshow_width":"800", "showCaption":"1", "captionTruncation":"200", "showComments":"1", "nomobile":"0", "name":"sitereview.editor-reviews-sitereview"
      }\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 11, \'{"title":"User Reviews","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 12, \'{"title":"Specs","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 13, \'{"title":"Overview","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 14, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 15, \'{"title":"Photos","titleCount":"true"}\', NULL),
   
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 16, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 17, \'{"title":"Discussions","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 18, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","limit":"4","nomobile":"0","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 19, \'{"title":"Editor\'\'s Top Picks","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_editor","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"24","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 20, \'{"title":"Recently Viewed By You","titleCount":"true","statistics":["viewCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnHeight":"' . $columnHeight . '","truncation":"21","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),

(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
');
        }
    }

    public function sportProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
             if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
               $db->query('
    INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'{"title":"","nomobile":"0","name":"sitereview.list-profile-breadcrumb"}\', NULL)
  
');
             }
            $db->query('
    INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
  (' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","photosCarousel","tags","location","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL)
  
');

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 9,
                'params' => '{"max":"6"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.editor-reviews-sitereview", ' . $tab_id . ', 10, \'{
        "titleEditor":"Review", "titleOverview":"Overview", "titleDescription":"Description", "titleCount":"",  "title":"", "show_slideshow":"0", "showComments":"1", "showScrollTopButton":"1", "nomobile":"0", "name":"sitereview.editor-reviews-sitereview"
      }\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 11, \'{"title":"User Reviews","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 12, \'{"title":"Specs","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 13, \'{"title":"Overview","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 14, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 15, \'{"title":"Photos","titleCount":"true"}\', NULL),
 
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 16, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 17, \'{"title":"Discussions","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 18, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","loaded_by_ajax":"1"}\', NULL),
 
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 19, \'{"title":"Related Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"24","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 20, \'{"title":"Best Match","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"24","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 21, \'{"title":"Recently Viewed By You","titleCount":"true","statistics":["likeCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnHeight":"' . $columnHeight . '","truncation":"16","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 22, \'{"title":"%s \'\'s Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"24","nomobile":"1","name":"sitereview.userlisting-sitereview"}
\', NULL),

(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
');
        }
    }

    public function classifiedProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
 if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
   $db->query('
        INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'[""]\', NULL)
  
');
 }
            $db->query('
        INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
  
        
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $main_middle_id . ', 6, \'{"itemCount":"5"}\', NULL)
  
');

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 10,
                'params' => '{"max":"6"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 11, \'{"title":"Overview","titleCount":"true","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 12, \'{"title":"User Reviews","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 13, \'{"title":"Specifications","titleCount":"true","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 14, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","limit":"20","loaded_by_ajax":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 15, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 16, \'{"title":"Photos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 17, \'{"title":"Discussions","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 18, \'{"title":"Map","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 19, \'{"title":"Best Alternatives","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"0","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 20, \'{"title":"%s\'\'s Other Classifieds","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"21","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 21, \'{"title":"Related Classifieds","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 22, \'{"title":"Recently Viewed","titleCount":"true","statistics":["viewCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnHeight":"' . $columnHeight . '","truncation":"16","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),


(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
  
');
        }
    }

    public function propertyProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
             if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
               $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

  (' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'{"title":"","nomobile":"1","name":"sitereview.list-profile-breadcrumb"}\', NULL)');
             }
            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

 
  
 (' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","tags","location","description","compare","wishlist", "photo"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $main_middle_id . ', 6, \'{"itemCount":"5"}\', NULL)');

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 17,
                'params' => '{"max":"6"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 18, \'{"title":"User Reviews","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 19, \'{"title":"Overview","titleCount":"true","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 20, \'{"title":"Specs","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 21, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 22, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 23, \'{"title":"Photos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 24, \'{"title":"Where to Buy","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 25, \'{"title":"Discussions","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 26, \'{"title":"Best Alternatives","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"16","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  


(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 27, \'{"title":"%s\'\'s Properties","titleCount":"true","statistics":["commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"16","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  

(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 28, \'{"title":"Related Listings","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"tags","viewType":"gridview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"23","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),


(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 29, \'{"title":"Recently Viewed","titleCount":"true","statistics":["viewCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnHeight":"' . $columnHeight . '","truncation":"16","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),


(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
  
        
');
        }
    }

    public function entertainmentProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
             if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
               $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'[""]\', NULL)');
             }
            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","tags","location","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL)');

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 18,
                'params' => '{"max":"5"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 19, \'{"title":"Overview","titleCount":"true","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 20, \'{"title":"Reviews","titleCount":"true","itemProsConsCount":"3","itemReviewsCount":"3","nomobile":"0","name":"sitereview.sitemobile-user-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 21, \'{"title":"Discussions","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 22, \'{"title":"Details","titleCount":"true","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 23, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","limit":"20","loaded_by_ajax":"1"}\', NULL), 
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 24, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 25, \'{"title":"Photos","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 26, \'{"title":"Best Alternatives","titleCount":"true","statistics":["viewCount","likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"45","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  

(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 27, \'{"title":"%s\'\'s Listings","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"21","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 28, \'{"title":"Related Listings","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"gridview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 29, \'{"title":"Recently Viewed by You","titleCount":"true","statistics":["viewCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","show":"0","viewType":"gridview","columnHeight":"' . $columnHeight . '","truncation":"21","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),


(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
  
');
        }
    }

    //MOST RATED PAGE WORK
    public function mostratedPageCreate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 358;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_top-rated_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_top-rated_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse Top Rated ' . $titlePluUc,
                'title' => 'Browse  Top Rated ' . $titlePluUc,
                'description' => 'This is the top rated ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitereview.navigation-sitereview',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '',
            ));

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.sitemobile-advancedsearch',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"listingtype_id":"' . $listingTypeId . '"}',
            ));

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitereview.browse-breadcrumb-sitereview',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"nomobile":"1"}',
            ));
            
            $layoutViews=($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages') ? '"layouts_views":["2"]':'"layouts_views":["1","2"]';

            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitereview.rated-listings-sitereview',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"title":"","titleCount":true,'.$layoutViews.',"layouts_order":"2","statistics":["viewCount","likeCount","reviewCount","commentCount"],"columnWidth":"200","truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"1","bottomLine":"1","postedby":"1","orderby":"spfesp","itemCount":"9","truncation":"100","name":"sitereview.browse-listings-sitereview","bottomLineGrid":"2","showContent":["price","location","endDate","postedDate"],"viewType":"1"}',

            ));
        }
    }
    
    
   public function insertWidget($params = array()) {
     $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      //PLACE CATEGORY WIDGET FOR MOBILE APP AND TABLET APP.
      if (1 ||$params['contentTable'] == 'engine4_sitemobileapp_content' || $params['contentTable'] == 'engine4_sitemobileapp_tablet_content') {
        $db->query('  
 INSERT IGNORE INTO ' . $params['contentTable'] . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $params['page_id'] . ', "widget", "sitereview.categories-home", ' . $params['top_middle_id'] . ',5, \'{"title":"","titleCount":"true","listingtype_id":"' . $params['listingtype_id'] . '"}\', NULL)');
      }
   } 
   
    public function jobHomeTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);

        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) { 

            $containerCount = 0;
            $widgetCount = 7;

            //CREATE PAGE
            $db->insert($pageTable, array(
                'name' => "sitereview_index_home_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
                'title' => $titlePluUc . ' Home',
                'description' => 'This is the ' . $titleSinLc . ' home page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

            
            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => $widgetCount++,
                'params' => '{"max":"5","layoutContainer":"horizontal","title":"","name":"sitemobile.container-tabs-columns"}',
            ));
            $tab_id = $db->lastInsertId();
            $columnHeight = 255;
            $columnWidth = 200;
            $db->query('
  
 INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"title":"","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 20, \'{"title":"Featured","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"bottomLine":"2","bottomLineGrid":"2","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Sponsored","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"sponsored","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"bottomLine":"2","bottomLineGrid":"2","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 30, \'{"title":"Top Rated","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","layouts_views":' . $this->_layoutViews . ',"showContent":["postedDate"],"bottomLine":"2","bottomLineGrid":"2","columnHeight":"' . $columnHeight . '","popularity":"rating_avg","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 40, \'{"title":"Most Popular","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"view_count","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL),

(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $tab_id . ', 50, \'{"title":"Recent","titleCount":true,"statistics":["viewCount","likeCount","commentCount"],"viewType":"' . $this->_viewType . '","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","layouts_views":' . $this->_layoutViews . ',"showContent":"","columnHeight":"' . $columnHeight . '","popularity":"creation_date","postedby":"1","itemCount":"9","truncationList":"100","truncationGrid":"100","name":"sitereview.listings-sitereview", "columnWidth":"' . $columnWidth . '"}\', NULL)
');
           
  $this->insertWidget(array('page_id' => $page_id, 'top_middle_id' => $main_middle_id, 'listingtype_id' => $listingTypeId, 'contentTable' => $contentTable, 'order' => 5));          
     
    }
 }   
 
     public function jobBrowseTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 270;
        $columnWidth = 200;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!$page_id) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_index_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
                'title' => 'Browse ' . $titlePluUc,
                'description' => 'This is the ' . $titleSinLc . ' browse page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();

             $browseBreadcrumb = '(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $main_middle_id . ', 5, \'{"nomobile":"1"}\', NULL),';
            if($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')
            $browseBreadcrumb = '';
            
            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $main_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advancedsearch", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
' . $browseBreadcrumb .
'(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":true,"layouts_views":' . $this->_layoutBrowseViews . ',"layouts_order":"' . $this->_layoutOrder . '","statistics":["viewCount","likeCount","commentCount"],"truncationGrid":"100","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"' . $columnHeight . '","showExpiry":"0","bottomLine":"2","postedby":"1","orderby":"spfesp","itemCount":"9","truncation":"100","name":"sitereview.browse-listings-sitereview", "columnWidth":"' . $columnWidth . '", "showContent":["postedDate"],"bottomLineGrid":2}\', NULL)
   
');
        }
    }
    
     public function jobProfileTemplate($listingType, $pageTable, $contentTable) {

        //GET DATABASE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        //GET LISTING TYPE DETAILS
        $listingTypeId = $listingType->listingtype_id;
        $titleSinUc = ucfirst($listingType->title_singular);
        $titlePluUc = ucfirst($listingType->title_plural);
        $titleSinLc = strtolower($listingType->title_singular);
        $titlePluLc = strtolower($listingType->title_plural);
        $columnHeight = 260;
        $page_id = $db->select()
                ->from($pageTable, 'page_id')
                ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
                ->query()
                ->fetchColumn();

        if (empty($page_id)) {

            $containerCount = 0;
            $widgetCount = 0;

            $db->insert($pageTable, array(
                'name' => "sitereview_index_view_listtype_" . $listingTypeId,
                'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
                'title' => $titleSinUc . ' Profile',
                'description' => 'This is ' . $titleSinUc . ' profile page.',
                'custom' => 0
            ));
            $page_id = $db->lastInsertId($pageTable);

            //TOP CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $top_container_id = $db->lastInsertId();

            //MAIN CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => $containerCount++,
            ));
            $main_container_id = $db->lastInsertId();

            //INSERT TOP-MIDDLE
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $top_container_id,
                'order' => $containerCount++,
            ));
            $top_middle_id = $db->lastInsertId();

            //MAIN-MIDDLE CONTAINER
            $db->insert($contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_container_id,
                'order' => $containerCount++,
            ));
            $main_middle_id = $db->lastInsertId();
            if (!($pageTable == 'engine4_sitemobileapp_pages' || $pageTable == 'engine4_sitemobileapp_tablet_pages')) {
        $db->query('
  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES

(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 4, \'["[]"]\', NULL)

');
      }
            $db->query('
  INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 5, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","tags","location","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL)

');
            $db->insert($contentTable, array(
                'page_id' => $page_id,
                'type' => 'widget',
                'name' => 'sitemobile.container-tabs-columns',
                'parent_content_id' => $main_middle_id,
                'order' => 20,
                'params' => '{"max":"5"}',
            ));
            $tab_id = $db->lastInsertId();

            $db->query('
INSERT IGNORE INTO ' . $contentTable . ' (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES        
(' . $page_id . ', "widget", "sitereview.editor-reviews-sitereview", ' . $tab_id . ', 21, \'{"titleEditor":"Review","titleOverview":"Overview","titleDescription":"Description","titleCount":"","title":"","show_slideshow":"0","slideshow_height":"400","slideshow_width":"600","showCaption":"1","showButtonSlide":"1","mouseEnterEvent":"0","thumbPosition":"bottom","autoPlay":"0","slidesLimit":"20","captionTruncation":"200","showComments":"1","name":"siteevent.editor-reviews-siteevent"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sitemobile-user-sitereview", ' . $tab_id . ', 22, \'{"title":"User Reviews","titleCount":"true","itemProsConsCount":"5","itemReviewsCount":"5","nomobile":"0","name":"sitereview.sitemobile-user-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 23, \'{"title":"Specs","titleCount":"true","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 25, \'{"title":"Photos","titleCount":"true"}\', NULL),

(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 26, \'{"title":"Videos","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $tab_id . ', 28, \'{"title":"Best Alternative Jobs","titleCount":"true","statistics":["viewCount","likeCount","commentCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"30","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $tab_id . ', 29, \'{"title":"More from %s","titleCount":"true","statistics":["viewCount","likeCount","commentCount"],"ratingType":"rating_avg","viewType":"listview","columnHeight":"' . $columnHeight . '","count":"3","truncation":"30","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $tab_id . ', 30, \'{"title":"From same Category","titleCount":"true","statistics":["viewCount","likeCount","commentCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnHeight":"' . $columnHeight . '","itemCount":"3","truncation":"30","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $tab_id . ', 31, \'{"title":"Recently Viewed by You","titleCount":"true","statistics":["viewCount","likeCount","commentCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","show":"0","viewType":"listview","columnHeight":"' . $columnHeight . '","truncation":"30","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),

(' . $page_id . ', "widget", "seaocore.sitemobile-people-like", ' . $tab_id . ', 35, \'{"title":"Member Likes","titleCount":"true","itemCount":5}\', NULL),
(' . $page_id . ', "widget", "sitemobile.profile-links", ' . $tab_id . ', 38, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitemobile.sitemobile-advfeed", ' . $tab_id . ', 40, \'{"title":"Updates"}\', NULL)
');
        }
    }   
   
}