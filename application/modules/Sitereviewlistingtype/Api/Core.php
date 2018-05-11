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
class Sitereviewlistingtype_Api_Core extends Core_Api_Abstract {

  public function defaultTemplate($listingType, $template_type) {

    if (empty($listingType) || empty($template_type))
      return;

    $methodNameHome = $template_type . 'HomeTemplate';
    $this->$methodNameHome($listingType);

    $methodNameBrowse = $template_type . 'BrowseTemplate';
    $this->$methodNameBrowse($listingType);

    $methodNameProfile = $template_type . 'ProfileTemplate';
    $this->$methodNameProfile($listingType);
    $this->mostratedPageCreate($listingType);
  }
  
  public function jobHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
INSERT INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"advancedslideshow","nomobile":"1"}\', NULL),    

(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"advancedslideshow","nomobile":"1"}\', NULL),       
    
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $left_container_id . ', 9, \'{"title":"Job of the Day","ratingType":"rating_avg","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $left_container_id . ', 10, \'{"listingtype_id":' . $listingTypeId . ',"viewDisplayHR":"0","nomobile":"1","title":"Search Jobs By Category","name":"sitereview.listtypes-categories"}\', NULL),
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 11, \'{"title":"Top Rated Jobs","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":' . $listingTypeId . ',"ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"rating_avg","interval":"overall","category_id":"0","hidden_category_id":"","hidden_subcategory_id":"","hidden_subsubcategory_id":"","itemCount":"3","truncation":"16","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 12, \'{"title":"Most Liked Jobs","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":' . $listingTypeId . ',"ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"like_count","interval":"overall","category_id":"0","hidden_category_id":"","hidden_subcategory_id":"","hidden_subsubcategory_id":"","itemCount":"3","truncation":"16","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $left_container_id . ', 13, \'{"title":"Recently Viewed By Friends","titleCount":true,"statistics":["likeCount","reviewCount"],"listingtype_id":' . $listingTypeId . ',"ratingType":"rating_avg","fea_spo":"","show":"1","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"16","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 15, \'{"0":"","title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "sitereview.slideshow-sitereview", ' . $main_middle_id . ', 16, \'{"title":"Featured Jobs","titleCount":"true","statistics":["viewCount","likeCount","commentCount","reviewCount"],"listingtype_id":' . $listingTypeId . ',"fea_spo":"featured","nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.recently-popular-random-sitereview", ' . $main_middle_id . ', 17, \'{"title":"Recent Jobs","titleCount":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"layouts_views":["listZZZview","gridZZZview","mapZZZview"],"ajaxTabs":["recent","mostZZZreviewed","mostZZZpopular","featured","sponsored"],"showContent":["price","location"],"recent_order":"4","reviews_order":"5","popular_order":"2","featured_order":"1","sponosred_order":"3","columnWidth":"180","listingtype_id":' . $listingTypeId . ',"ratingType":"rating_avg","category_id":"0","hidden_category_id":"","hidden_subcategory_id":"","hidden_subsubcategory_id":"","detactLocation":"0","defaultLocationDistance":"1000","defaultOrder":"listZZZview","listViewType":"list","columnHeight":"328","expiring_order":"6","postedby":"1","limit":"8","truncationList":"600","truncationGrid":"90","nomobile":"0","name":"sitereview.recently-popular-random-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 19, \'{"title":"","titleCount":true,"listingtype_id":' . $listingTypeId . ',"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.mostrated-browse-sitereview", ' . $right_container_id . ', 20, \'{"title":"","titleCount":true,"listingtype_id":' . $listingTypeId . ',"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $right_container_id . ', 21, \'{"title":"","titleCount":true,"listingtype_id":' . $listingTypeId . '}\', NULL),
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 22, \'{"title":"Most Recommended Jobs","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":' . $listingTypeId . ',"ratingType":"rating_avg","fea_spo":"fea_spo","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"comment_count","interval":"overall","category_id":"0","hidden_category_id":"","hidden_subcategory_id":"","hidden_subsubcategory_id":"","itemCount":"3","truncation":"16","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 23, \'{"title":"Most Viewed Jobs","titleCount":true,"statistics":["viewCount","likeCount"],"viewType":"listview","columnWidth":"180","listingtype_id":' . $listingTypeId . ',"ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"16","nomobile":"0","name":"sitereview.listings-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.popularlocation-sitereview", ' . $right_container_id . ', 24, \'{"title":"Now Find Job Near Your Locations","titleCount":true,"listingtype_id":' . $listingTypeId . ',"itemCount":"10","nomobile":"1","name":"sitereview.popularlocation-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 25, \'{"title":"Popular Tags (%s)","titleCount":true,"listingtype_id":' . $listingTypeId . ',"nomobile":"1"}\', NULL);
');
      
      $db->insert('engine4_core_content', array('page_id' => $page_id, 'type' => 'widget', 'name' => 'sitereview.sponsored-sitereview','parent_content_id' => $top_middle_id, 'order' => 5, 'params' => '{"title":"Recent Jobs","titleCount":true,"showOptions":["category","rating","review","compare","wishlist"],"titleLink":"<a href=\"\/jobs\">Read More \u00bb<\/a>","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","category_id":"0","hidden_category_id":"","hidden_subcategory_id":"","hidden_subsubcategory_id":"","detactLocation":"0","defaultLocationDistance":"1000","viewType":"0","blockHeight":"238","blockWidth":"185","itemCount":"5","showPagination":"1","popularity":"creation_date","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","interval":"300","truncation":"50","nomobile":"1","name":"sitereview.sponsored-sitereview"}'));
    }
  }
  
  public function jobBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();


      $db->query('

INSERT INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":true,"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $top_middle_id . ', 5, \'{"title":"","titleCount":true,"listingtype_id":' . $listingTypeId . ',"viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 8, \'{"title":"","titleCount":true,"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 9, \'{"title":"","titleCount":true,"layouts_views":["1","2","3"],"layouts_order":"1","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"199","truncationGrid":"90","listingtype_id":' . $listingTypeId . ',"ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"308","showExpiry":"0","viewType":"0","bottomLine":"0","postedby":"0","orderby":"fespfe","itemCount":"12","truncation":"25","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.categories-sidebar-sitereview", ' . $right_container_id . ', 11, \'{"title":"Categories","titleCount":"true","listingtype_id":' . $listingTypeId . ',"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 13, \'{"title":"","titleCount":true,"listingtype_id":' . $listingTypeId . ',"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 14, \'{"title":"Popular Tags (%s)","titleCount":true,"listingtype_id":' . $listingTypeId . ',"nomobile":"1"}\', NULL);

');
    }
  }  
  
public function jobProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
INSERT INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'{"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'[""]\', NULL),

(' . $page_id . ', "widget", "sitereview.mainphoto-sitereview", ' . $left_container_id . ', 8, \'{"titleCount":true,"ownerName":"0","title":"","nomobile":"0","name":"sitereview.mainphoto-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.applynow-button", ' . $left_container_id . ', 9, \'{"title":"Apply Now","show_option":["2","3","4","5"],"nomobile":"0","name":"sitereview.applynow-button"}\', NULL),
(' . $page_id . ', "widget", "sitereview.user-ratings", ' . $left_container_id . ', 10, \'{"title":"User Ratings","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "sitereview.location-sidebar-sitereview", ' . $left_container_id . ', 11, \'{"title":"Job Location","titleCount":true,"height":"200","nomobile":"0","name":"sitereview.location-sidebar-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $left_container_id . ', 12, \'{"title":"Refrences","titleCount":"true","nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.write-sitereview", ' . $left_container_id . ', 13, \'{"title":"","titleCount":true,"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $left_container_id . ', 14, \'{"title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 15, \'{"title":"Most Viewed Jobs","titleCount":true,"statistics":["viewCount","likeCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"2","truncation":"16","nomobile":"0","name":"sitereview.listings-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $left_container_id . ', 16, \'{"title":"Related Listings","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnWidth":"180","columnHeight":"328","itemCount":"2","truncation":"24","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $left_container_id . ', 17, \'{"title":"%s`s Listings","titleCount":true,"statistics":["viewCount","likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnWidth":"180","columnHeight":"328","count":"2","truncation":"24","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),

(' . $page_id . ', "widget", "seaocore.people-like", ' . $left_container_id . ', 18, \'{"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "seaocore.social-share-buttons", ' . $left_container_id . ', 19, \'{"show_buttons":["facebook","twitter","linkedin","plusgoogle","share"],"title":"","nomobile":"0","name":"seaocore.social-share-buttons"}\', NULL),
(' . $page_id . ', "widget", "sitereview.title-sitereview", ' . $main_middle_id . ', 21, \'{"title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "seaocore.like-button", ' . $main_middle_id . ', 22, \'{"title":""}\', NULL),
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $main_middle_id . ', 23, \'[""]\', NULL),
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $main_middle_id . ', 24, \'{"title":"Looking For","titleCount":true,"loaded_by_ajax":"0","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $main_middle_id . ', 25, \'{"title":"Overview","titleCount":true,"loaded_by_ajax":"0","showAfterEditorReview":"2","showComments":"0","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $main_middle_id . ', 26, \'{"title":"Discussions","titleCount":true,"loaded_by_ajax":"1","nomobile":"0","name":"sitereview.discussion-sitereview"}\', NULL),
(' . $page_id . ', "widget", "core.profile-links", ' . $main_middle_id . ', 27, \'{"title":"Links","titleCount":"true"}\', NULL);
  
');
      
      $db->insert('engine4_core_content', array('page_id' => $page_id, 'type' => 'widget', 'name' => 'core.html-block','parent_content_id' => $top_middle_id, 'order' => 5, 'params' => '{"title":"","data":"<style type=\"text\/css\">\r\n.generic_layout_container > h3 {\r\n    background-color: #5BC0AC;\r\n    border-radius: 0;\r\n    color: #FFFFFF;\r\n    margin-bottom: 6px;\r\n}\r\n.layout_seaocore_like_button {\r\n float: none; \r\n}\r\n.layout_sitereview_title_sitereview {\r\nmargin-right: 10px;\r\n}\r\n<\/style>","nomobile":"0","name":"core.html-block"}'));      
      
    }
  }  

  public function articleHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
  
 INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"advancedslideshow","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 7, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.pinboard-listings-sitereview", ' . $main_middle_id . ', 8, \'{"title":"","statistics":"","show_buttons":["wishlist","like","share","pinit"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","detactLocation":"0","defaultLocationDistance":"1000","popularity":"creation_date","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","postedby":"1","userComment":"1","price":"0","location":"0","autoload":"1","defaultLoadingImage":"1","itemWidth":"215","withoutStretch":"0","itemCount":"10","noOfTimes":"2","truncationDescription":"60","nomobile":"0","name":"sitereview.pinboard-listings-sitereview"}\', NULL),

(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 10, \'{"title":"","titleCount":true,"listingtype_id":"' . $listingTypeId . '","nomobile":"0","name":"sitereview.newlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-sidebar-sitereview", ' . $right_container_id . ', 11, \'{"title":"Browse Categories","titleCount":true,"listingtype_id":"' . $listingTypeId . '","nomobile":"0","name":"sitereview.categories-sidebar-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 14, \'{"title":"Featured Articles","titleCount":true,"statistics":"","viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"30","nomobile":"0","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 16, \'{"title":"Recent Articles","titleCount":true,"statistics":"","viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"fea_spo","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"30","nomobile":"0","name":"sitereview.listings-sitereview"}\', NULL) 

');
    }
  }
  
  public function foodHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
  
 INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $top_middle_id . ', 4, \'["[]"]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 7, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.pinboard-listings-sitereview", ' . $main_middle_id . ', 8, \'{"title":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"show_buttons":["wishlist","compare","comment","like","share","facebook","twitter","pinit","tellAFriend","print"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","popularity":"like_count","interval":"overall","postedby":"1","autoload":"1","itemWidth":"431","withoutStretch":"0","itemCount":"16","noOfTimes":"0","truncationDescription":"225","nomobile":"0","name":"sitereview.pinboard-listings-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"facebooksefeed","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.top-posters", ' . $right_container_id . ', 10, \'{"title":"Cook of the Week","listingtype_id":"' . $listingTypeId . '","itemCount":"1","nomobile":"1","name":"sitereview.top-posters"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $right_container_id . ', 11, \'{"title":"Food Item of the Day","ratingType":"rating_avg","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-sponsored", ' . $right_container_id . ', 13, \'{"title":"Popular Collections","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"0","showIcon":"1","nomobile":"1","name":"sitereview.categories-sponsored"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 14, \'{"title":"Latest Food Items","titleCount":"true","statistics":["reviewCount"],"viewType":"gridview","columnWidth":"185","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"newlabel","columnHeight":"300","popularity":"rating_users","interval":"overall","itemCount":"3","truncation":"20","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $right_container_id . ', 15, \'{"title":"Featured Food Items","titleCount":"true","showOptions":["rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"featured","viewType":"1","blockHeight":"200","blockWidth":"182","itemCount":"2","popularity":"rating_users","featuredIcon":"0","sponsoredIcon":"0","newIcon":"0","interval":"300","truncation":"21","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 16, \'{"title":"Top Rated Foods","titleCount":"true","statistics":["reviewCount"],"viewType":"gridview","columnWidth":"185","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"300","popularity":"rating_users","interval":"overall","itemCount":"3","truncation":"20","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL) 

');
    }
  }

  public function tourismHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"advancedslideshow","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $left_container_id . ', 7, \'{"listingtype_id":"' . $listingTypeId . '","viewDisplayHR":"0","title":"","nomobile":"1","name":"sitereview.listtypes-categories"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $left_container_id . ', 8, \'{"title":"' . $titleSinUc . ' of the Day","ratingType":"rating_both","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 9, \'{"title":"Editors Top Rated","titleCount":"true","statistics":["viewCount","commentCount","reviewCount"],"viewType":"gridview","columnWidth":"200","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_editor","fea_spo":"","columnHeight":"328","popularity":"rating_editor","interval":"overall","itemCount":"2","truncation":"90","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $left_container_id . ', 10, \'{"title":"Popular Places (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"25","nomobile":"1","name":"sitereview.tagcloud-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 11, \'{"title":"Most Liked Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"gridview","columnWidth":"200","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","popularity":"like_count","interval":"overall","itemCount":"2","truncation":"90","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 14, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-middle-sitereview", ' . $main_middle_id . ', 15, \'{"title":"Categories","titleCount":"true","listingtype_id":"' . $listingTypeId . '","showAllCategories":"1","show2ndlevelCategory":"1","show3rdlevelCategory":"0","showCount":"0","nomobile":"1","name":"sitereview.categories-middle-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.category-listings-sitereview", ' . $main_middle_id . ', 16, \'{"title":"Popular Tourism Listings","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"2","listingCount":"5","popularity":"view_count","interval":"overall","truncation":"25","nomobile":"1","name":"sitereview.category-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-popular-random-sitereview", ' . $main_middle_id . ', 17, \'{"title":"","titleCount":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"layouts_views":["listZZZview","gridZZZview","mapZZZview"],"ajaxTabs":["recent","mostZZZreviewed","mostZZZpopular","featured","sponsored"],"recent_order":"2","reviews_order":"1","popular_order":"3","featured_order":"4","sponosred_order":"5","columnWidth":"191","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","defaultOrder":"gridZZZview","listViewType":"list","columnHeight":"300","postedby":"1","limit":"12","truncationList":"600","truncationGrid":"60","nomobile":"0","name":"sitereview.recently-popular-random-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $right_container_id . ', 19, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 20, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.newlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 21, \'{"title":"Top Reviewed Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"gridview","columnWidth":"200","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"review_count","interval":"overall","itemCount":"2","truncation":"90","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 22, \'{"title":"Recently Viewed By Friends","titleCount":"true","statistics":["likeCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"1","viewType":"gridview","columnWidth":"200","truncation":"90","count":"2","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL);

');
    }
  }

  public function blogHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
    INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 6, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.pinboard-listings-sitereview", ' . $main_middle_id . ', 7, \'{"title":"","statistics":["likeCount","commentCount"],"show_buttons":["wishlist","compare","comment","like","share","facebook","twitter"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","popularity":"like_count","interval":"overall","postedby":"1","autoload":"1","itemWidth":"366","withoutStretch":"1","itemCount":"16","noOfTimes":"0","truncationDescription":"225","nomobile":"0","name":"sitereview.pinboard-listings-sitereview"}\', NULL);

');
    }
  }

  public function fashionHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $top_middle_id . ', 4, \'{"title":"","mouseOverText":"Scroll to Top","nomobile":"1","name":"seaocore.scroll-top"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 7, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $main_middle_id . ', 8, \'{"title":"Featured","titleCount":"true","showOptions":"","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","viewType":"0","blockHeight":"140","blockWidth":"150","itemCount":"6","popularity":"creation_date","featuredIcon":"0","sponsoredIcon":"0","newIcon":"0","interval":"300","truncation":"19","nomobile":"0","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.pinboard-listings-sitereview", ' . $main_middle_id . ', 9, \'{"title":"New Arrivals","statistics":["viewCount","likeCount","commentCount","reviewCount"],"show_buttons":["wishlist","compare","comment","like","share","facebook","pinit"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","popularity":"comment_count","interval":"overall","postedby":"1","autoload":"1","itemWidth":"275","withoutStretch":"1","itemCount":"26","noOfTimes":"0","truncationDescription":"0","nomobile":"0","name":"sitereview.pinboard-listings-sitereview"}\', NULL);
    
');
    }
  }

  public function electronicHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();


      $db->query('
  INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"sitefaq","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $top_middle_id . ', 4, \'{"title":"Electronics Catalog","titleCount":"true","showOptions":["category","rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","fea_spo":"","viewType":"0","blockHeight":"240","blockWidth":"150","itemCount":"6","popularity":"review_count","featuredIcon":"1","sponsoredIcon":"1","newIcon":"0","interval":"300","truncation":"50","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 7, \'{"title":"","titleCount":"true","0":""}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-popular-random-sitereview", ' . $main_middle_id . ', 8, \'{"title":"","titleCount":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"layouts_views":["listZZZview","gridZZZview","mapZZZview"],"ajaxTabs":["recent","mostZZZreviewed","mostZZZpopular","featured","sponsored"],"recent_order":"2","reviews_order":"1","popular_order":"3","featured_order":"4","sponosred_order":"5","columnWidth":"197","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","defaultOrder":"listZZZview","listViewType":"tabular","columnHeight":"328","postedby":"1","limit":"10","truncationList":"600","truncationGrid":"90","nomobile":"0","name":"sitereview.recently-popular-random-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.slideshow-sitereview", ' . $main_middle_id . ', 9, \'{"title":"Featured Electronics Listings","titleCount":"true","statistics":["viewCount","likeCount","commentCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","fea_spo":"featured","popularity":"creation_date","interval":"overall","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","truncation":"500","count":"10","nomobile":"1","name":"sitereview.slideshow-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-middle-sitereview", ' . $main_middle_id . ', 10, \'{"title":"Categories","titleCount":"true","listingtype_id":"' . $listingTypeId . '","showAllCategories":"1","show2ndlevelCategory":"1","show3rdlevelCategory":"0","showCount":"0","nomobile":"1","name":"sitereview.categories-middle-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.category-listings-sitereview", ' . $main_middle_id . ', 11, \'{"title":"Popular Electronics Listings","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"0","listingCount":"2","popularity":"view_count","interval":"overall","truncation":"25","nomobile":"1","name":"sitereview.category-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 13, \'{"title":"","pluginName":"communityad","nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 13, \'{"title":"","titleCount":"true","pluginName":"facebooksefeed","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $right_container_id . ', 14, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 15, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 16, \'{"title":"Most Rated Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"rating_avg","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 17, \'{"title":"Most Liked Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"like_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 18, \'{"title":"Most Popular Listings","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"rating_users","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 19, \'{"title":"Most Reviewed Listing","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"review_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 20, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"20","nomobile":"1","name":"sitereview.tagcloud-sitereview"}\', NULL);

');
    }
  }

  public function sportHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
  
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $left_container_id . ', 6, \'{"listingtype_id":"' . $listingTypeId . '","viewDisplayHR":"0","title":"","nomobile":"1","name":"sitereview.listtypes-categories"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $left_container_id . ', 7, \'{"title":"' . $titleSinUc . ' of the Day","ratingType":"rating_avg","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 8, \'{"title":"Most Reviewed Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"review_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 9, \'{"title":"Most Liked Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"like_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 10, \'{"title":"Most Commented Listings","titleCount":"true","statistics":["commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $left_container_id . ', 11, \'{"title":"Recently Viewed By Friends","titleCount":"true","statistics":["likeCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"1","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"21","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 13, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $main_middle_id . ', 14, \'{"title":"Footwear","titleCount":"true","showOptions":["rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","viewType":"0","blockHeight":"200","blockWidth":"150","itemCount":"3","popularity":"creation_date","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","interval":"300","truncation":"50","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $main_middle_id . ', 15, \'{"title":"Fitness Accessories","titleCount":"true","showOptions":["rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","viewType":"0","blockHeight":"200","blockWidth":"150","itemCount":"3","popularity":"creation_date","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","interval":"300","truncation":"50","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $main_middle_id . ', 16, \'{"title":"Sports Apparel","titleCount":"true","showOptions":["rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","viewType":"0","blockHeight":"200","blockWidth":"150","itemCount":"3","popularity":"creation_date","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","interval":"300","truncation":"50","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-popular-random-sitereview", ' . $main_middle_id . ', 17, \'{"title":"","titleCount":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"layouts_views":["listZZZview","gridZZZview","mapZZZview"],"ajaxTabs":["recent","mostZZZreviewed","mostZZZpopular","featured","sponsored"],"recent_order":"3","reviews_order":"2","popular_order":"1","featured_order":"4","sponosred_order":"5","columnWidth":"192","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","defaultOrder":"gridZZZview","listViewType":"list","columnHeight":"300","postedby":"1","limit":"9","truncationList":"600","truncationGrid":"90","nomobile":"0","name":"sitereview.recently-popular-random-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 19, \'{"title":"","pluginName":"advancedslideshow","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.popular-reviews-sitereview", ' . $right_container_id . ', 21, \'{"title":"Most Liked Reviews","statistics":["likeCount","commentCount","helpfulCount"],"listingtype_id":"' . $listingTypeId . '","type":"user","status":"0","popularity":"like_count","interval":"overall","groupby":"1","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.popular-reviews-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.review-ads", ' . $right_container_id . ', 22, \'{"title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 23, \'{"title":"Recently Viewed Listings","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"listingtype_id":"-1","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"21","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL);

');
    }
  }

  public function classifiedHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('

INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $top_middle_id . ', 5, \'{"title":"","titleCount":"true","showOptions":["rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","viewType":"0","blockHeight":"200","blockWidth":"150","itemCount":"6","popularity":"rating_users","featuredIcon":"0","sponsoredIcon":"0","newIcon":"1","interval":"300","truncation":"19","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 8, \'{"title":"Featured Classifieds","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"gridview","columnWidth":"202","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"featured","columnHeight":"328","popularity":"rating_avg","interval":"overall","itemCount":"2","truncation":"30","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 9, \'{"title":"Most Liked Classifieds","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"gridview","columnWidth":"202","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"like_count","interval":"overall","itemCount":"2","truncation":"16","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 10, \'{"title":"Most Commented Classifieds","titleCount":"true","statistics":["commentCount","reviewCount"],"viewType":"gridview","columnWidth":"202","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"2","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 12, \'{"title":"","titleCount":"true","0":""}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-popular-random-sitereview", ' . $main_middle_id . ', 13, \'{"title":"Classifieds by Location","titleCount":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"layouts_views":["mapZZZview"],"ajaxTabs":"","recent_order":"1","reviews_order":"2","popular_order":"3","featured_order":"4","sponosred_order":"5","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","defaultOrder":"mapZZZview","listViewType":"list","columnHeight":"328","postedby":"1","limit":"50","truncationList":"600","truncationGrid":"90","nomobile":"0","name":"sitereview.recently-popular-random-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $main_middle_id . ', 14, \'{"title":"","pluginName":"communityad","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.category-listings-sitereview", ' . $main_middle_id . ', 15, \'{"title":"Most Liked Classifieds by Category","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"0","listingCount":"5","popularity":"like_count","interval":"overall","truncation":"25","nomobile":"1","name":"sitereview.category-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-popular-random-sitereview", ' . $main_middle_id . ', 16, \'{"title":"","titleCount":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"layouts_views":["listZZZview","gridZZZview"],"ajaxTabs":["recent","mostZZZreviewed","mostZZZpopular","featured","sponsored"],"recent_order":"2","reviews_order":"1","popular_order":"3","featured_order":"4","sponosred_order":"5","columnWidth":"192","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","defaultOrder":"gridZZZview","listViewType":"list","columnHeight":"328","postedby":"1","limit":"6","truncationList":"600","truncationGrid":"90","nomobile":"0","name":"sitereview.recently-popular-random-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 18, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $right_container_id . ', 19, \'{"title":"' . $titleSinUc . ' of the Day","ratingType":"rating_users","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 20, \'{"title":"Latest Posted Calssifieds","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"gridview","columnWidth":"202","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"2","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 21, \'{"title":"Recently Viewed By Friends","titleCount":"true","statistics":["likeCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"1","viewType":"gridview","columnWidth":"202","columnHeight":"328","truncation":"19","count":"2","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 22, \'{"title":"","titleCount":"true","pluginName":"facebookse","nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 23, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.popularlocation-sitereview", ' . $right_container_id . ', 24, \'{"title":"Popular Locations","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL);

');
    }
  }

  public function propertyHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('

INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $left_container_id . ', 7, \'{"title":"Featured & Sponsored","titleCount":"true","showOptions":["category","rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","viewType":"1","blockHeight":"220","blockWidth":"180","itemCount":"2","popularity":"creation_date","featuredIcon":"0","sponsoredIcon":"0","newIcon":"1","interval":"300","truncation":"30","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.popularlocation-sitereview", ' . $left_container_id . ', 8, \'{"title":"Popular Locations","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"10","nomobile":"1","name":"sitereview.popularlocation-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $left_container_id . ', 9, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.newlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 10, \'{"title":"Most Recent","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"gridview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"creation_date","interval":"overall","itemCount":"2","truncation":"30","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 11, \'{"title":"Top Rated ","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 12, \'{"title":"Most Liked","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"like_count","interval":"overall","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 13, \'{"title":"Most Reviewed","titleCount":"true","statistics":["commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.layout-width", ' . $left_container_id . ', 14, \'{"title":"","layoutWidth":"195","layoutWidthType":"px","nomobile":"0","name":"seaocore.layout-width"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 16, \'{"title":"","titleCount":"true","0":""}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-popular-random-sitereview", ' . $main_middle_id . ', 17, \'{"title":"","titleCount":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"layouts_views":["mapZZZview"],"ajaxTabs":["recent","mostZZZreviewed","mostZZZpopular","featured","sponsored"],"recent_order":"3","reviews_order":"2","popular_order":"1","featured_order":"4","sponosred_order":"5","columnWidth":"193","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","defaultOrder":"mapZZZview","listViewType":"list","columnHeight":"328","postedby":"1","limit":"100","truncationList":"600","truncationGrid":"90","nomobile":"0","name":"sitereview.recently-popular-random-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.pinboard-listings-sitereview", ' . $main_middle_id . ', 18, \'{"title":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"show_buttons":["wishlist","compare","comment","like","share","pinit"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","popularity":"creation_date","interval":"overall","postedby":"1","autoload":"1","itemWidth":"340","withoutStretch":"1","itemCount":"12","noOfTimes":"0","truncationDescription":"0","nomobile":"0","name":"sitereview.pinboard-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $right_container_id . ', 20, \'{"title":"' . $titleSinUc . ' of the Day","ratingType":"rating_users","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $right_container_id . ', 21, \'{"title":"Featured","titleCount":"true","showOptions":["category","rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"featured","viewType":"1","blockHeight":"240","blockWidth":"180","itemCount":"2","popularity":"creation_date","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","interval":"300","truncation":"30","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 22, \'{"title":"","pluginName":"communityad","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.top-posters", ' . $right_container_id . ', 23, \'{"title":"Top Contributers","listingtype_id":"' . $listingTypeId . '","itemCount":"3","nomobile":"1","name":"sitereview.top-posters"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 24, \'{"title":"Most Viewed","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 25, \'{"title":"Most Commented","titleCount":"true","statistics":["commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 26, \'{"title":"Recently Viewed By Friends","titleCount":"true","statistics":["likeCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"1","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"19","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 27, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"25","nomobile":"1","name":"sitereview.tagcloud-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.layout-width", ' . $right_container_id . ', 28, \'{"title":"","layoutWidth":"195","layoutWidthType":"px","nomobile":"0","name":"seaocore.layout-width"}\', NULL);

');
    }
  }

  public function entertainmentHomeTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_home_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_home_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titlePluUc . ' Home',
          'title' => $titlePluUc . ' Home',
          'description' => 'This is the ' . $titleSinLc . ' home page.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
  INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"sitefaq","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.zerolisting-sitereview", ' . $main_middle_id . ', 6, \'{"title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.pinboard-listings-sitereview", ' . $main_middle_id . ', 7, \'{"title":"","statistics":["viewCount","likeCount","commentCount","reviewCount"],"show_buttons":["wishlist","compare","comment","like","share","facebook","twitter","pinit","tellAFriend","print"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","popularity":"view_count","interval":"overall","postedby":"1","autoload":"1","itemWidth":"275","withoutStretch":"1","itemCount":"12","noOfTimes":"0","truncationDescription":"0","nomobile":"0","name":"sitereview.pinboard-listings-sitereview"}\', NULL);
  
');
    }
  }
  
  public function articleBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      //CREATE PAGE
      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
  
 INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":true,"nomobile":"1"}\', NULL),
    
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $top_middle_id . ', 5, \'{"title":"","titleCount":true,"listingtype_id":"'.$listingTypeId.'","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 8, \'{"title":"","titleCount":true,"layouts_views":["1"],"layouts_order":"1","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"206","truncationGrid":"200","listingtype_id":"'.$listingTypeId.'","ratingType":"rating_both","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","showExpiry":"0","viewType":"1","bottomLine":"1","postedby":"1","orderby":"creation_date","itemCount":"7","truncation":"100","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 14, \'{"title":"Most Commented Articles","titleCount":true,"statistics":["commentCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"'.$listingTypeId.'","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"16","nomobile":"0","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 16, \'{"title":"Most Discussed Articles","titleCount":true,"statistics":"","viewType":"listview","columnWidth":"180","listingtype_id":"'.$listingTypeId.'","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"16","nomobile":"0","name":"sitereview.listings-sitereview"}\', NULL), 
    
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 16, \'{"title":"Most Popular Articles","titleCount":true,"statistics":["viewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"'.$listingTypeId.'","ratingType":"rating_avg","fea_spo":"featured","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"16","nomobile":"0","name":"sitereview.listings-sitereview"}\', NULL),
    
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 16, \'{"title":"Popular Tags (%s)","titleCount":true,"listingtype_id":"'.$listingTypeId.'","itemCount":"30","nomobile":"1","name":"sitereview.tagcloud-sitereview"}\', NULL)

');
    }
  }  

  public function foodBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();


      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $left_container_id . ', 7, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $left_container_id . ', 8, \'{"listingtype_id":"' . $listingTypeId . '","viewDisplayHR":"0","title":"Search Food Items In","nomobile":"0","name":"sitereview.listtypes-categories"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $left_container_id . ', 9, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 10, \'{"title":"Most Commented Food Items","titleCount":"true","statistics":["commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"2","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 11, \'{"title":"Most Viewed Food Items","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"2","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $left_container_id . ', 12, \'{"title":"Recently Viewed by You","titleCount":"true","statistics":["likeCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","show":"0","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"21","count":"2","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.layout-width", ' . $left_container_id . ', 13, \'{"title":"","layoutWidth":"200","layoutWidthType":"px","nomobile":"0","name":"seaocore.layout-width"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 15, \'{"title":"","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 16, \'{"title":"","titleCount":"true","layouts_views":["1","2"],"layouts_order":"2","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"198","truncationGrid":"90","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","columnHeight":"300","viewType":"0","bottomLine":"1","postedby":"1","orderby":"fespfe","itemCount":"12","truncation":"40","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $right_container_id . ', 18, \'{"title":"Sponsored Today","titleCount":"true","showOptions":["rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"sponsored","viewType":"1","blockHeight":"190","blockWidth":"200","itemCount":"2","popularity":"review_count","featuredIcon":"0","sponsoredIcon":"0","newIcon":"0","interval":"300","truncation":"25","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 19, \'{"title":"Most Recent Food Items","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"creation_date","interval":"overall","itemCount":"2","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 20, \'{"title":"Most Liked Courses","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 21, \'{"title":"Highest Rated Desserts","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"rating_users","interval":"overall","itemCount":"2","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 22, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL);
        
');
    }
  }

  public function tourismBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
 INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $left_container_id . ', 6, \'{"title":"Most Recent Listings","titleCount":"true","showOptions":["category","rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","viewType":"1","blockHeight":"240","blockWidth":"200","itemCount":"2","popularity":"creation_date","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","interval":"300","truncation":"50","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $left_container_id . ', 7, \'{"title":"Most Rated Listings","titleCount":"true","showOptions":["category","rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","viewType":"1","blockHeight":"240","blockWidth":"200","itemCount":"2","popularity":"rating_avg","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","interval":"300","truncation":"50","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 9, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.categories-banner-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 10, \'{"title":"","titleCount":"true","layouts_views":["1","2","3"],"layouts_order":"2","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"190","truncationGrid":"90","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","columnHeight":"310","viewType":"1","bottomLine":"1","postedby":"1","orderby":"creation_date","itemCount":"12","truncation":"25","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-sidebar-sitereview", ' . $right_container_id . ', 12, \'{"title":"Categories","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.categories-sidebar-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $right_container_id . ', 13, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 14, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.newlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 15, \'{"title":"Editors Choice","titleCount":"true","statistics":["viewCount","likeCount","commentCount","reviewCount"],"viewType":"gridview","columnWidth":"200","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_editor","fea_spo":"","columnHeight":"328","popularity":"rating_editor","interval":"overall","itemCount":"2","truncation":"90","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL);
      
');
    }
  }
  
  public function mostratedPageCreate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_top-rated_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_top-rated_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse Top Rated ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitereview.navigation-sitereview',
          'parent_content_id' => $top_middle_id,
          'order' => $widgetCount++,
          'params' => '',
      ));

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitereview.browse-breadcrumb-sitereview',
          'parent_content_id' => $top_middle_id,
          'order' => $widgetCount++,
          'params' => '{"nomobile":"1"}',
      ));

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitereview.categories-sidebar-sitereview',
          'parent_content_id' => $right_container_id,
          'order' => $widgetCount++,
          'params' => '{"title":"Categories","listingtype_id":"' . $listingTypeId . '","titleCount":"true","nomobile":"1"}',
      ));

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitereview.search-sitereview',
          'parent_content_id' => $right_container_id,
          'order' => $widgetCount++,
          'params' => '{"listingtype_id":"' . $listingTypeId . '"}',
      ));

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitereview.newlisting-sitereview',
          'parent_content_id' => $right_container_id,
          'order' => $widgetCount++,
          'params' => '{"listingtype_id":"' . $listingTypeId . '","nomobile":"1"}',
      ));

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitereview.tagcloud-sitereview',
          'parent_content_id' => $right_container_id,
          'order' => $widgetCount++,
          'params' => '{"title": "Popular Tags (%s)","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}',
      ));

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitereview.categories-banner-sitereview',
          'parent_content_id' => $main_middle_id,
          'order' => $widgetCount++,
          'params' => '{"nomobile":"1"}',
      ));

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'sitereview.rated-listings-sitereview',
          'parent_content_id' => $main_middle_id,
          'order' => $widgetCount++,
          'params' => '{"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","statistics":["viewCount","likeCount","commentCount","reviewCount"],"layouts_views":["1","2","3"]}',
      ));
    }
  }

  public function blogBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
     
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.browse-breadcrumb-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.top-posters", ' . $left_container_id . ', 7, \'{"title":"Top posters","listingtype_id":"' . $listingTypeId . '","itemCount":"3","nomobile":"1","name":"sitereview.top-posters"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 8, \'{"title":"Latest Blogs","titleCount":"true","statistics":["viewCount","commentCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"newlabel","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 9, \'{"title":"Most Commented Blogs","titleCount":"true","statistics":["viewCount","commentCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 10, \'{"title":"Most Viewed Blogs","titleCount":"true","statistics":["viewCount","commentCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 12, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.categories-banner-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 13, \'{"title":"","titleCount":"true","layouts_views":["1","2","3"],"layouts_order":"2","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"193","truncationGrid":"45","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","columnHeight":"270","viewType":"0","bottomLine":"0","postedby":"1","orderby":"newlabel","itemCount":"15","truncation":"40","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-sidebar-sitereview", ' . $right_container_id . ', 15, \'{"title":"Categories","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.categories-sidebar-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $right_container_id . ', 16, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 17, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.newlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 18, \'{"title":"Most Liked Blogs","titleCount":"true","statistics":["viewCount","likeCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"like_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.popularlocation-sitereview", ' . $right_container_id . ', 19, \'{"title":"Popular Locations","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"4","nomobile":"1","name":"sitereview.popularlocation-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 20, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"25","nomobile":"1","name":"sitereview.tagcloud-sitereview"}\', NULL);

');
    }
  }

  public function fashionBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
  
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.browse-breadcrumb-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $main_middle_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"sitefaq","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $top_middle_id . ', 5, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"suggestion","nomobile":"1"}\', NULL), 
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 5, \'{"title":"","titleCount":"true","pluginName":"facebookse","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $left_container_id . ', 8, \'{"listingtype_id":"' . $listingTypeId . '","viewDisplayHR":"0","title":"","nomobile":"1","name":"sitereview.listtypes-categories"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $left_container_id . ', 9, \'{"title":"' . $titleSinUc . ' of the Day","ratingType":"rating_avg","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $left_container_id . ', 10, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.newlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 11, \'{"title":"Featured & Sponsored","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"fea_spo","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 12, \'{"title":"Top Rated Items","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"rating_avg","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 13, \'{"title":"Most Liked Items","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"like_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $left_container_id . ', 14, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"25","nomobile":"1","name":"sitereview.tagcloud-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 16, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.categories-banner-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 17, \'{"title":"","titleCount":"true","layouts_views":["1","2","3"],"layouts_order":"2","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"198","truncationGrid":"30","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","columnHeight":"320","viewType":"1","bottomLine":"0","postedby":"1","orderby":"newlabel","itemCount":"20","truncation":"25","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL);

');
    }
  }

  public function electronicBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
        
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.browse-breadcrumb-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $top_middle_id . ', 5, \'{"title":"","titleCount":"true","pluginName":"sitepageintegration","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $main_middle_id . ', 7, \'{"title":"New Electronics Listings","titleCount":"true","showOptions":["rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","fea_spo":"newlabel","viewType":"0","blockHeight":"240","blockWidth":"150","itemCount":"4","popularity":"creation_date","featuredIcon":"1","sponsoredIcon":"1","newIcon":"1","interval":"300","truncation":"50","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 8, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.categories-banner-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 9, \'{"title":"","titleCount":"true","layouts_views":["1","2","3"],"layouts_order":"1","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"180","truncationGrid":"90","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","columnHeight":"328","viewType":"1","bottomLine":"1","postedby":"1","orderby":"fespfe","itemCount":"10","truncation":"45","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $right_container_id . ', 11, \'{"title":"' . $titleSinUc . ' of the Day","ratingType":"rating_both","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $right_container_id . ', 12, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","viewType":"vertical","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 13, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.newlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 14, \'{"title":"Editors Choice","titleCount":"true","statistics":["reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_editor","fea_spo":"","columnHeight":"328","popularity":"rating_editor","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 15, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"10","nomobile":"1","name":"sitereview.tagcloud-sitereview"}\', NULL);

');
    }
  }

  public function sportBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();


      $db->query('

INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.browse-breadcrumb-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $top_middle_id . ', 5, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $top_middle_id . ', 6, \'{"title":"","titleCount":"true","pluginName":"sitepageintegration","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 8, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.categories-banner-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 9, \'{"title":"","titleCount":"true","layouts_views":["1","2","3"],"layouts_order":"2","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"196","truncationGrid":"90","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","columnHeight":"310","viewType":"1","bottomLine":"1","postedby":"1","orderby":"spfesp","itemCount":"16","truncation":"25","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 11, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.newlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-sidebar-sitereview", ' . $right_container_id . ', 12, \'{"title":"Categories","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1","name":"sitereview.categories-sidebar-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 13, \'{"title":"Most Viewed Listings","titleCount":"true","statistics":["viewCount","likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.popular-reviews-sitereview", ' . $right_container_id . ', 14, \'{"title":"Most Popular Reviews","statistics":["viewCount","likeCount","helpfulCount"],"listingtype_id":"' . $listingTypeId . '","type":"user","status":"0","popularity":"like_count","interval":"overall","groupby":"1","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.popular-reviews-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 15, \'{"title":"Most Reviewed Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"review_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 16, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","itemCount":"12","nomobile":"1","name":"sitereview.tagcloud-sitereview"}\', NULL);

');
    }
  }

  public function classifiedBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();


      $db->query('

INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"title":"","titleCount":"true","0":""}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $top_middle_id . ', 5, \'["[]"]\', NULL),
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $top_middle_id . ', 6, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 9, \'{"title":"","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 10, \'{"title":"","titleCount":"true","layouts_views":["1","2","3"],"layouts_order":"1","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"180","truncationGrid":"90","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","columnHeight":"328","viewType":"1","bottomLine":"1","postedby":"1","orderby":"newlabel","itemCount":"15","truncation":"45","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 4, \'{"title":"","titleCount":"true","pluginName":"facebookse","nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 5, \'{"title":"","titleCount":"true","pluginName":"suggestion","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.categories-sidebar-sitereview", ' . $right_container_id . ', 12, \'{"title":"Categories","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 13, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 14, \'{"title":"Most Commented Classifieds","titleCount":"true","statistics":["commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 15, \'{"title":"Most Viewed Classifieds","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 16, \'{"title":"Most Liked Calssifieds","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 17, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL);

');
    }
  }

  public function propertyBrowseTemplate($listingType) {

//GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
INSERT INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":true}\', NULL),
              
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":true,"listingtype_id":"' . $listingTypeId . '","viewType":"horizontal","nomobile":"0","name":"sitereview.search-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 5, \'{"title":"","titleCount":true,"nomobile":"1","name":"sitereview.browse-breadcrumb-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.popularlocation-sitereview", ' . $left_container_id . ', 8, \'{"title":"Popular Locations","titleCount":true,"listingtype_id":"' . $listingTypeId . '","itemCount":"10","nomobile":"1","name":"sitereview.popularlocation-sitereview"}\', NULL),
        
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 9, \'{"title":"","pluginName":"communityad","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 10, \'{"title":"Top Rated ","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"rating_users","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 11, \'{"title":"Most Reviewed","titleCount":true,"statistics":["commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
        
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 12, \'{"title":"Most Recent","titleCount":true,"statistics":["reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"creation_date","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 13, \'{"title":"Most Liked","titleCount":true,"statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 15, \'{"title":"","titleCount":true,"nomobile":"1","name":"sitereview.categories-banner-sitereview"}\', NULL),
        
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 16, \'{"title":"","titleCount":true,"layouts_views":["1","2","3"],"layouts_order":"2","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"193","truncationGrid":"30","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","columnHeight":"328","viewType":"1","bottomLine":"0","postedby":"1","orderby":"creation_date","itemCount":"10","truncation":"25","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.item-sitereview", ' . $right_container_id . ', 18, \'{"title":"Property of the day","ratingType":"rating_avg","nomobile":"1","name":"sitereview.item-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 19, \'{"title":"","titleCount":true,"listingtype_id":"' . $listingTypeId . '","nomobile":"0","name":"sitereview.newlisting-sitereview"}\', NULL),
        
(' . $page_id . ', "widget", "sitereview.sponsored-sitereview", ' . $right_container_id . ', 20, \'{"title":"Properties At Glance","titleCount":true,"showOptions":["category","rating","review","compare","wishlist"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","viewType":"1","blockHeight":"220","blockWidth":"190","itemCount":"2","popularity":"creation_date","featuredIcon":"0","sponsoredIcon":"0","newIcon":"1","interval":"300","truncation":"30","nomobile":"1","name":"sitereview.sponsored-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 21, \'{"title":"Most Viewed","titleCount":true,"statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 22, \'{"title":"Most Commented","titleCount":true,"statistics":["commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"like_count","interval":"overall","itemCount":"3","truncation":"19","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
        
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 23, \'{"title":"Recently Viewed By Friends","titleCount":true,"statistics":["likeCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","show":"1","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"19","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 24, \'{"title":"Popular Tags (%s)","titleCount":true,"listingtype_id":"' . $listingTypeId . '","itemCount":"25","nomobile":"0","name":"sitereview.tagcloud-sitereview"}\', NULL);        

');
    }
  }

  public function entertainmentBrowseTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_index_listtype_" . $listingTypeId)
            ->limit(1)
            ->query()
            ->fetchColumn();

    if (!$page_id) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_index_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - Browse ' . $titlePluUc,
          'title' => '',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
        
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.navigation-sitereview", ' . $top_middle_id . ', 3, \'{"0":"","title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-breadcrumb-sitereview", ' . $top_middle_id . ', 4, \'{"title":"","titleCount":"true","nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $top_middle_id . ', 5, \'{"title":"","titleCount":"true","pluginName":"sitepageintegration","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.categories-banner-sitereview", ' . $main_middle_id . ', 7, \'{"title":"","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.browse-listings-sitereview", ' . $main_middle_id . ', 8, \'{"title":"","titleCount":"true","layouts_views":["1","2","3"],"layouts_order":"2","statistics":["viewCount","likeCount","commentCount","reviewCount"],"columnWidth":"197","truncationGrid":"90","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_both","columnHeight":"320","viewType":"1","bottomLine":"1","postedby":"1","orderby":"newlabel","itemCount":"10","truncation":"25","nomobile":"0","name":"sitereview.browse-listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.slideshow-sitereview", ' . $main_middle_id . ', 9, \'{"title":"Top Rated Featured Listings","titleCount":"true","statistics":["viewCount","likeCount","commentCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"featured","popularity":"rating_users","interval":"overall","featuredIcon":"0","sponsoredIcon":"0","newIcon":"0","truncation":"45","count":"10","nomobile":"1","name":"sitereview.slideshow-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.categories-sidebar-sitereview", ' . $right_container_id . ', 11, \'{"title":"Categories","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.search-sitereview", ' . $right_container_id . ', 12, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 13, \'{"title":"","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 14, \'{"title":"Most Commented","titleCount":"true","statistics":["commentCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"comment_count","interval":"overall","itemCount":"2","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 15, \'{"title":"Most Viewed","titleCount":"true","statistics":["viewCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"328","popularity":"view_count","interval":"overall","itemCount":"2","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 16, \'{"title":"Popular Tags (%s)","titleCount":"true","listingtype_id":"' . $listingTypeId . '","nomobile":"1"}\', NULL);

');
    }
  }
  
  public function articleProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
  
(' . $page_id . ', "widget", "sitereview.listing-owner-photo", ' . $left_container_id . ', 3, \'["[]"]\', NULL),
(' . $page_id . ', "widget", "sitereview.write-sitereview", ' . $left_container_id . ', 4, \'{"title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "sitereview.user-ratings", ' . $left_container_id . ', 5, \'{"title":"User Ratings","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "seaocore.social-share-buttons", ' . $left_container_id . ', 6, \'{"show_buttons":["facebook","twitter","linkedin","plusgoogle","share"],"title":"","nomobile":"0","name":"seaocore.social-share-buttons"}\', NULL),
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $left_container_id . ', 7, \'{"title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $left_container_id . ', 8, \'{"title":"Related Listings","titleCount":true,"statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnWidth":"180","columnHeight":"328","itemCount":"2","truncation":"24","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
(' . $page_id . ', "widget", "seaocore.people-like", ' . $left_container_id . ', 9, \'{"nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 10, \'{"title":"","titleCount":"true","pluginName":"sitetagcheckin","nomobile":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $main_middle_id . ', 13, \'[""]\', NULL),
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $main_middle_id . ', 14, \'[""]\', NULL),
(' . $page_id . ', "widget", "sitereview.title-sitereview", ' . $main_middle_id . ', 15, \'{"title":"","titleCount":true}\', NULL),
(' . $page_id . ', "widget", "seaocore.like-button", ' . $main_middle_id . ', 16, \'{"title":""}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $main_middle_id . ', 17, \'{"title":"","titleCount":"true","pluginName":"facebookse","nomobile":"1"}\', NULL)');

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 18,
          'params' => '{"max":"6"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');      
      
      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 19, \'{"title":"Article","titleCount":true,"loaded_by_ajax":"1","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 20, \'{"title":"Discussions","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 21, \'{"title":"Photos","titleCount":true,"loaded_by_ajax":"1","itemCount":"18","nomobile":"0","name":"sitereview.photos-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 22, \'{"title":"Videos","titleCount":true,"loaded_by_ajax":"1","count":"2","truncation":"35","nomobile":"0","name":"sitereview.video-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 23, \'{"title":"References","titleCount":true,"layout_column":"0","limit":"3","nomobile":"0","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.slideshow-list-photo", ' . $right_container_id . ', 25, \'{"title":"Facts and Figures","titleCount":true,"slideshow_height":"200","slideshow_width":"200","showCaption":"1","showButtonSlide":"0","mouseEnterEvent":"0","thumbPosition":"bottom","autoPlay":"1","slidesLimit":"20","captionTruncation":"200","nomobile":"0","name":"sitereview.slideshow-list-photo"}\', NULL),
(' . $page_id . ', "widget", "sitereview.newlisting-sitereview", ' . $right_container_id . ', 26, \'{"title":"","titleCount":true,"listingtype_id":"'.$listingTypeId.'","nomobile":"0","name":"sitereview.newlisting-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.wishlist-add-link", ' . $right_container_id . ', 27, \'{"title":""}\', NULL),
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $right_container_id . ', 28, \'{"title":"Views","titleCount":true,"show_specificationlink":"1","show_specificationtext":"Views","itemCount":"5","nomobile":"0","name":"sitereview.quick-specification-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.tagcloud-sitereview", ' . $right_container_id . ', 29, \'{"title":"Major Concepts","titleCount":true,"listingtype_id":"'.$listingTypeId.'","itemCount":"25","nomobile":"0","name":"sitereview.tagcloud-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 30, \'{"title":"Recently Viewed By Your Friends","titleCount":true,"statistics":["viewCount"],"listingtype_id":"'.$listingTypeId.'","ratingType":"rating_avg","fea_spo":"fea_spo","show":"1","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"90","count":"2","nomobile":"0","name":"sitereview.recently-viewed-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $right_container_id . ', 31, \'{"title":"%s\'\'s Listings","titleCount":true,"statistics":"","ratingType":"rating_avg","viewType":"listview","columnWidth":"180","columnHeight":"228","count":"2","truncation":"90","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
(' . $page_id . ', "widget", "sitereview.most-discussed-listings", ' . $right_container_id . ', 32, \'{"title":"Most Discussed Articles","titleCount":true,"viewType":"listview","columnWidth":"180","listingtype_id":"'.$listingTypeId.'","ratingType":"rating_avg","fea_spo":"fea_spo","detactLocation":"0","defaultLocationDistance":"1000","columnHeight":"328","category_id":"0","hidden_category_id":"0","hidden_subcategory_id":"0","hidden_subsubcategory_id":"0","itemCount":"2","truncation":"16","nomobile":"0","name":"sitereview.most-discussed-listings"}\', NULL);');
    }
  }  

  public function foodProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
  INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'{"listingtype_id":"-1","viewDisplayHR":"1","title":"","nomobile":"0","name":"sitereview.listtypes-categories"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'["[]"]\', NULL),
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $left_container_id . ', 7, \'{"title":"Nutritional Facts","titleCount":"true","show_specificationlink":"1","show_specificationtext":"All Facts","itemCount":"6","nomobile":"1","name":"sitereview.quick-specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $left_container_id . ', 8, \'{"title":"Options","titleCount":"true","nomobile":"0","name":"sitereview.options-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.write-sitereview", ' . $left_container_id . ', 9, \'{"title":"","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $left_container_id . ', 10, \'{"title":"Best Alternative Recipes","titleCount":"true","statistics":["reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnWidth":"200","columnHeight":"300","itemCount":"2","truncation":"30","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $left_container_id . ', 11, \'{"title":"Recently Viewed by You","titleCount":"true","statistics":["reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","show":"0","viewType":"gridview","columnWidth":"200","columnHeight":"300","truncation":"30","count":"2","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.share", ' . $left_container_id . ', 12, \'{"title":"Social Share","titleCount":"true","options":["socialShare"],"nomobile":"1","name":"sitereview.share"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 14, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $main_middle_id . ', 16, \'[""]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 17, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","tags","location","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-carousel", ' . $main_middle_id . ', 18, \'{"title":"","itemCount":"10","nomobile":"0","name":"sitereview.photos-carousel"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overall-ratings", ' . $main_middle_id . ', 19, \'{"title":"","titleCount":"true","show_rating":"both","ratingParameter":"1","nomobile":"0","name":"sitereview.overall-ratings"}\', NULL)
  
');
      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 20,
          'params' => '{"max":"5"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES        
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 21, \'{"title":"Preparation","titleCount":"true","loaded_by_ajax":"1","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.user-sitereview", ' . $tab_id . ', 22, \'{"title":"Reviews","titleCount":"true","loaded_by_ajax":"1","itemProsConsCount":"5","itemReviewsCount":"5","nomobile":"0","name":"sitereview.user-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.slideshow-list-photo", ' . $tab_id . ', 23, \'{"title":"Slideshow","titleCount":"true","slideshow_height":"500","slideshow_width":"600","showCaption":"1","captionTruncation":"200","nomobile":"0","name":"sitereview.slideshow-list-photo"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 24, \'{"title":"Nutrition","titleCount":"true","loaded_by_ajax":"1","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 25, \'{"title":"Buy Ingredients","titleCount":"true","layout_column":"0","limit":"4","nomobile":"1","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 26, \'{"title":"Ads","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 26, \'{"title":"Ads","titleCount":"true","pluginName":"sitevideoview","nomobile":"1"}\', NULL),  
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 27, \'{"title":"Videos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 28, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $left_container_id . ', 30, \'{"title":"Buy Ingredients","titleCount":"true","layout_column":"1","limit":"2","nomobile":"1","name":"sitereview.price-info-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.people-like", ' . $left_container_id . ', 31, \'{"nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 32, \'{"title":"","pluginName":"communityad","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $left_container_id . ', 33, \'{"title":"More from %s","titleCount":"true","statistics":["reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnWidth":"210","columnHeight":"300","count":"2","truncation":"30","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $left_container_id . ', 34, \'{"title":"From same Category","titleCount":"true","statistics":["reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"gridview","columnWidth":"210","columnHeight":"280","itemCount":"2","truncation":"30","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL);

');
    }
  }

  public function tourismProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
        
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'[""]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'{"title":"","nomobile":"0","name":"sitereview.list-profile-breadcrumb"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 7, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","tags","location","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"1","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL)
  
');
      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 8,
          'params' => '{"max":"6"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');
      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.editor-reviews-sitereview", ' . $tab_id . ', 9, \'{"titleEditor":"Editor Review","titleOverview":"Overview","titleDescription":"Description","titleCount":"","loaded_by_ajax":"1","title":"","show_slideshow":"1","slideshow_height":"450","slideshow_width":"800","showCaption":"1","captionTruncation":"200","showComments":"1","nomobile":"0","name":"sitereview.editor-reviews-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.user-sitereview", ' . $tab_id . ', 10, \'{"title":"User Reviews","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 11, \'{"title":"Specs","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 12, \'{"title":"Overview","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 13, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 14, \'{"title":"Ads","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 15, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 16, \'{"title":"Ads","titleCount":"true","pluginName":"sitevideoview","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 16, \'{"title":"Videos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 17, \'{"title":"Discussions","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 18, \'{"title":"Where to Book","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "core.profile-links", ' . $tab_id . ', 19, \'{"title":"Links","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 20, \'{"title":"Ads","nomobile":"1","pluginName":"advancedactivity"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overall-ratings", ' . $right_container_id . ', 22, \'{"title":"","titleCount":"true","show_rating":"both","ratingParameter":"0","nomobile":"0","name":"sitereview.overall-ratings"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $right_container_id . ', 23, \'{"title":"Quick Specifications","titleCount":"true","show_specificationlink":"1","itemCount":"5","nomobile":"1","name":"sitereview.quick-specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $right_container_id . ', 24, \'{"title":"Where to Book","titleCount":"true","layout_column":"1","limit":"4","nomobile":"1","name":"sitereview.price-info-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.write-sitereview", ' . $right_container_id . ', 25, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.write-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.about-editor-sitereview", ' . $right_container_id . ', 26, \'{"title":"About Editor","titleCount":"","nomobile":"1","name":"sitereview.about-editor-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $right_container_id . ', 27, \'{"title":"Best Alternatives","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnWidth":"220","itemCount":"2","truncation":"90","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $right_container_id . ', 28, \'{"title":"Related Listings","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"tags","viewType":"gridview","columnWidth":"220","itemCount":"2","truncation":"90","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $right_container_id . ', 29, \'{"title":"","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnWidth":"220","count":"2","truncation":"90","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 30, \'{"title":"Recently Viewed By You","titleCount":"true","statistics":["likeCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"gridview","columnWidth":"220","columnHeight":"328","truncation":"90","count":"4","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 32, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.share", ' . $right_container_id . ', 33, \'{"title":"Share and Report","titleCount":"true","options":["siteShare","friend","report","print","socialShare"],"nomobile":"1","name":"sitereview.share"}\', NULL);

');
    }
  }

  public function blogProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();


      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'[""]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'[""]\', NULL),
              
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $top_middle_id . ', 5, \'["[]"]\', NULL),
              
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 8, \'{"title":"","showContent":["title","postedDate","postedBy"],"like_button":"0","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $main_middle_id . ', 9, \'{"title":"","nomobile":"1","pluginName":"facebookse"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $main_middle_id . ', 10, \'{"title":"Body","titleCount":"true","loaded_by_ajax":"0","showAfterEditorReview":"2","showComments":"0","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.slideshow-list-photo", ' . $main_middle_id . ', 11, \'{"title":"","titleCount":"true","slideshow_height":"400","slideshow_width":"840","showCaption":"1","captionTruncation":"200","nomobile":"1","name":"sitereview.slideshow-list-photo"}\', NULL)
  
');
      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 12,
          'params' => '{"max":"6"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "seaocore.seaocores-nestedcomments", ' . $tab_id . ', 13, \'{"title":"Comments"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 14, \'{"title":"Specs","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 15, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 16, \'{"title":"Ads","titleCount":"true","pluginName":"sitevideoview","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 16, \'{"title":"Videos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 17, \'{"title":"References","titleCount":"true","layout_column":"0","limit":"20","nomobile":"0","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 18, \'{"title":"Ads","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 19, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listing-owner-photo", ' . $right_container_id . ', 21, \'{"title":"","nomobile":"1","name":"sitereview.listing-owner-photo"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.mainphoto-sitereview", ' . $right_container_id . ', 22, \'{"titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.information-sitereview", ' . $right_container_id . ', 23, \'{"title":"","titleCount":"true","showContent":["modifiedDate","viewCount","likeCount","commentCount","tags","location"],"nomobile":"1","name":"sitereview.information-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $right_container_id . ', 24, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.options-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 25, \'{"title":"","pluginName":"communityad","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $right_container_id . ', 26, \'{"title":"More from %s","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnWidth":"180","columnHeight":"328","count":"3","truncation":"21","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $right_container_id . ', 27, \'{"title":"More in Politics","titleCount":"true","statistics":["viewCount","commentCount"],"viewType":"listview","columnWidth":"210","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","columnHeight":"150","popularity":"view_count","interval":"overall","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $right_container_id . ', 28, \'{"title":"Related Blogs","titleCount":"true","statistics":["likeCount","commentCount"],"ratingType":"rating_avg","viewType":"0","columnWidth":"190","columnHeight":"328","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 30, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.archives-sitereview", ' . $right_container_id . ', 31, \'{"title":"Archives","titleCount":"true","nomobile":"1","name":"sitereview.archives-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.share", ' . $right_container_id . ', 32, \'{"title":"Social Share","titleCount":"true","options":["socialShare"],"nomobile":"1","name":"sitereview.share"}\', NULL)
');
    }
  }

  public function fashionProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'[""]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'{"title":"","nomobile":"1","name":"sitereview.list-profile-breadcrumb"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $top_middle_id . ', 5, \'["[]"]\', NULL),
(' . $page_id . ', "widget", "sitereview.overall-ratings", ' . $left_container_id . ', 8, \'{"title":"","titleCount":"true","show_rating":"avg","ratingParameter":"0","nomobile":"0","name":"sitereview.overall-ratings"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.write-sitereview", ' . $left_container_id . ', 9, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.write-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $left_container_id . ', 10, \'{"title":"Item Details","titleCount":"true","show_specificationlink":"1","itemCount":"3","nomobile":"1","name":"sitereview.quick-specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $left_container_id . ', 11, \'{"title":"Where to Buy","titleCount":"true","layout_column":"1","limit":"4","nomobile":"1","name":"sitereview.price-info-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $left_container_id . ', 12, \'{"title":"Options","titleCount":"true","nomobile":"1","name":"sitereview.options-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $left_container_id . ', 13, \'{"title":"Best Pick","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","viewType":"0","columnWidth":"180","columnHeight":"328","itemCount":"2","truncation":"19","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $left_container_id . ', 14, \'{"title":"Related Items","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnWidth":"180","columnHeight":"328","itemCount":"2","truncation":"19","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $left_container_id . ', 15, \'{"title":"%s\'\'s Items","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnWidth":"200","columnHeight":"328","count":"2","truncation":"19","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 17, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.share", ' . $left_container_id . ', 18, \'{"title":"Social Share","titleCount":"true","options":["socialShare"],"nomobile":"1","name":"sitereview.share"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 20, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","photosCarousel","tags","location","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL)');

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 21,
          'params' => '{"max":"6"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.slideshow-list-photo", ' . $tab_id . ', 22, \'{"title":"Photos Slideshow","titleCount":"true","slideshow_height":"600","slideshow_width":"855","showCaption":"1","captionTruncation":"200","nomobile":"0","name":"sitereview.slideshow-list-photo"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 23, \'{"title":"Overview","titleCount":"true","loaded_by_ajax":"1","showAfterEditorReview":"2","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.user-sitereview", ' . $tab_id . ', 24, \'{"title":"User Reviews","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 25, \'{"title":"Details","titleCount":"true","loaded_by_ajax":"1","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 26, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","limit":"","nomobile":"0","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 27, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 28, \'{"title":"","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.seaocores-nestedcomments", ' . $tab_id . ', 29, \'{"title":"Comments"}\', NULL);
  
');
    }
  }

  public function electronicProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
    INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'[""]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'{"title":"","nomobile":"1","name":"sitereview.list-profile-breadcrumb"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.title-sitereview", ' . $main_middle_id . ', 7, \'{"title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "seaocore.like-button", ' . $main_middle_id . ', 8, \'{"title":""}\', NULL)');

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 9,
          'params' => '{"max":"6"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.editor-reviews-sitereview", ' . $tab_id . ', 10, \'{
        "titleEditor":"Editor Review", "titleOverview":"Overview", "titleDescription":"Slideshow", "titleCount":"", "loaded_by_ajax":"1", "title":"", "show_slideshow":"1", "slideshow_height":"450", "slideshow_width":"800", "showCaption":"1", "captionTruncation":"200", "showComments":"1", "nomobile":"0", "name":"sitereview.editor-reviews-sitereview"
      }\', NULL),
  
(' . $page_id . ', "widget", "sitereview.user-sitereview", ' . $tab_id . ', 11, \'{"title":"User Reviews","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 12, \'{"title":"Specs","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 13, \'{"title":"Overview","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 14, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 15, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 16, \'{"title":"Ads","titleCount":"true","pluginName":"sitevideoview","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 16, \'{"title":"Videos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 17, \'{"title":"Discussions","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 18, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","limit":"4","nomobile":"0","name":"sitereview.price-info-sitereview","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 19, \'{"title":"Ads","nomobile":"1","pluginName":"advancedactivity"}\', NULL),
  
(' . $page_id . ', "widget", "core.profile-links", ' . $tab_id . ', 20, \'{"title":"Links","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 21, \'{"title":"Ads","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $main_middle_id . ', 22, \'{"title":"Editor\'\'s Top Picks","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_editor","viewType":"1","columnWidth":"180","itemCount":"3","truncation":"24","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.mainphoto-sitereview", ' . $right_container_id . ', 24, \'{
        "titleCount":"true"
      }\', NULL),
(' . $page_id . ', "widget", "sitereview.information-sitereview", ' . $right_container_id . ', 25, \'{"title":"","titleCount":"true","showContent":["ownerPhoto","ownerName","modifiedDate","tags","price","compare"],"nomobile":"0","name":"sitereview.information-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $right_container_id . ', 26, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.options-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.review-button", ' . $right_container_id . ', 27, \'{"title":""}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overall-ratings", ' . $right_container_id . ', 28, \'{"title":"","titleCount":"true","show_rating":"both","ratingParameter":"1","nomobile":"1","name":"sitereview.overall-ratings"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $right_container_id . ', 29, \'{"title":"Where to Buy","titleCount":"true","layout_column":"1","limit":"3","nomobile":"1","name":"sitereview.price-info-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $right_container_id . ', 30, \'{"title":"Quick Specifications","titleCount":"true","show_specificationlink":"1","itemCount":"5","nomobile":"1","name":"sitereview.quick-specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.about-editor-sitereview", ' . $right_container_id . ', 31, \'{"title":"About Editor","titleCount":"","nomobile":"1","name":"sitereview.about-editor-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.share", ' . $right_container_id . ', 32, \'{"title":"Share and Report","titleCount":"true","options":["siteShare","friend","report","print","socialShare"],"nomobile":"1","name":"sitereview.share"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 33, \'{"title":"Recently Viewed By You","titleCount":"true","statistics":["viewCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"21","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 35, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL);
    
');
    }
  }

  public function sportProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
    INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'[""]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'{"title":"","nomobile":"0","name":"sitereview.list-profile-breadcrumb"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $top_middle_id . ', 5, \'["[]"]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.slideshow-list-photo", ' . $main_middle_id . ', 8, \'{"title":"","titleCount":"true","slideshow_height":"450","slideshow_width":"750","showCaption":"1","captionTruncation":"200","nomobile":"1","name":"sitereview.slideshow-list-photo"}\', NULL)');

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 9,
          'params' => '{"max":"6"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.editor-reviews-sitereview", ' . $tab_id . ', 10, \'{
        "titleEditor":"Review", "titleOverview":"Overview", "titleDescription":"Description", "titleCount":"", "loaded_by_ajax":"1", "title":"", "show_slideshow":"0", "showComments":"1", "showScrollTopButton":"1", "nomobile":"0", "name":"sitereview.editor-reviews-sitereview"
      }\', NULL),
  
(' . $page_id . ', "widget", "sitereview.user-sitereview", ' . $tab_id . ', 11, \'{"title":"User Reviews","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 12, \'{"title":"Specs","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 13, \'{"title":"Overview","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 14, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 15, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 16, \'{"title":"Ads","titleCount":"true","pluginName":"sitevideoview","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 16, \'{"title":"Videos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 17, \'{"title":"Discussions","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 18, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "core.profile-links", ' . $tab_id . ', 19, \'{"title":"Links","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 20, \'{"title":"Ads","nomobile":"1","pluginName":"advancedactivity"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 21, \'{"title":"Ads","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $right_container_id . ', 23, \'{"title":"","showContent":["title","viewCount","likeCount","commentCount","tags","compare","wishlist","reviewCreate"],"like_button":"1","actionLinks":"1","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overall-ratings", ' . $right_container_id . ', 24, \'{"title":"Reviews","titleCount":"true","show_rating":"avg","ratingParameter":"1","nomobile":"1","name":"sitereview.overall-ratings"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.layout-width", ' . $right_container_id . ', 25, \'{"title":"","layoutWidth":"300","layoutWidthType":"px","nomobile":"0","name":"seaocore.layout-width"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $right_container_id . ', 26, \'{"title":"Related Listings","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnWidth":"180","columnHeight":"328","itemCount":"3","truncation":"24","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $right_container_id . ', 27, \'{"title":"Best Match","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","viewType":"0","columnWidth":"180","columnHeight":"328","itemCount":"3","truncation":"24","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 28, \'{"title":"Recently Viewed By You","titleCount":"true","statistics":["likeCount","reviewCount"],"listingtype_id":"-1","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"16","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 30, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $right_container_id . ', 31, \'{"title":"%s \'\'s Listings","titleCount":"true","statistics":["viewCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnWidth":"180","columnHeight":"328","count":"3","truncation":"24","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL);
    
');
    }
  }

  public function classifiedProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
        INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'{"nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'[""]\', NULL),
        
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $top_middle_id . ', 5, \'[""]\', NULL),
        
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 8, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","description","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.slideshow-list-photo", ' . $main_middle_id . ', 9, \'{"title":"","titleCount":"true","slideshow_height":"450","slideshow_width":"840","showCaption":"1","captionTruncation":"200","nomobile":"1","name":"sitereview.slideshow-list-photo"}\', NULL)');

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 10,
          'params' => '{"max":"6"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 11, \'{"title":"Overview","titleCount":"true","loaded_by_ajax":"1","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.user-sitereview", ' . $tab_id . ', 12, \'{"title":"User Reviews","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 13, \'{"title":"Specifications","titleCount":"true","loaded_by_ajax":"1","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 14, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","limit":"20","loaded_by_ajax":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 15, \'{"title":"Ads","titleCount":"true","pluginName":"sitevideoview","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 15, \'{"title":"Videos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 16, \'{"title":"Ads","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 17, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 18, \'{"title":"Discussions","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 19, \'{"title":"Map","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overall-ratings", ' . $right_container_id . ', 21, \'{"title":"","titleCount":"true","show_rating":"both"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.information-sitereview", ' . $right_container_id . ', 22, \'{"title":"Information","titleCount":"true","showContent":["ownerPhoto","ownerName","tags","location"],"nomobile":"0","name":"sitereview.information-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $right_container_id . ', 23, \'{"title":"","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $right_container_id . ', 24, \'{"title":"Quick Specifications","titleCount":"true","show_specificationlink":"1","show_specificationtext":"Full Specifications","itemCount":"6","nomobile":"1","name":"sitereview.quick-specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $right_container_id . ', 25, \'{"title":"Where to Buy","titleCount":"true","layout_column":"1","limit":"2","nomobile":"1","name":"sitereview.price-info-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $right_container_id . ', 26, \'{"title":"Best Alternatives","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"0","columnWidth":"180","columnHeight":"328","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $right_container_id . ', 27, \'{"title":"%s\'\'s Other Classifieds","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnWidth":"180","columnHeight":"328","count":"3","truncation":"21","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $right_container_id . ', 28, \'{"title":"Related Classifieds","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnWidth":"180","columnHeight":"328","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 30, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.people-like", ' . $right_container_id . ', 32, \'{"nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.share", ' . $right_container_id . ', 33, \'{"title":"Social Share","titleCount":"true","options":["socialShare"],"nomobile":"1","name":"sitereview.share"}\', NULL);

');
    }
  }

  public function propertyProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'{"listingtype_id":"-1","viewDisplayHR":"1","title":"","nomobile":"1","name":"sitereview.listtypes-categories"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'{"title":"","nomobile":"1","name":"sitereview.list-profile-breadcrumb"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.slideshow-list-photo", ' . $left_container_id . ', 7, \'{"title":"","titleCount":"true","slideshow_height":"300","slideshow_width":"230","showCaption":"0","captionTruncation":"200","nomobile":"0","name":"sitereview.slideshow-list-photo"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $left_container_id . ', 8, \'{"title":"Options","titleCount":"true","nomobile":"0","name":"sitereview.options-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.share", ' . $left_container_id . ', 9, \'{"title":"Share and Report","titleCount":"true","options":["siteShare","friend","report","print","socialShare"],"nomobile":"1","name":"sitereview.share"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $left_container_id . ', 10, \'{"title":"Related Listings","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"tags","viewType":"listview","columnWidth":"180","columnHeight":"328","itemCount":"3","truncation":"23","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.listings-sitereview", ' . $left_container_id . ', 11, \'{"title":"Most Liked Properties","titleCount":"true","statistics":["likeCount","reviewCount"],"viewType":"listview","columnWidth":"180","listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","columnHeight":"328","popularity":"like_count","interval":"overall","itemCount":"3","truncation":"23","nomobile":"1","name":"sitereview.listings-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 13, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.layout-width", ' . $left_container_id . ', 14, \'{"title":"","layoutWidth":"230","layoutWidthType":"px","nomobile":"0","name":"seaocore.layout-width"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 16, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","tags","location","description","compare","wishlist"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL)');

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 17,
          'params' => '{"max":"6"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.user-sitereview", ' . $tab_id . ', 18, \'{"title":"User Reviews","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 19, \'{"title":"Overview","titleCount":"true","loaded_by_ajax":"1","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 20, \'{"title":"Specs","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.location-sitereview", ' . $tab_id . ', 21, \'{"title":"Map","titleCount":"true"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 22, \'{"title":"Ads","titleCount":"true","pluginName":"sitevideoview","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 22, \'{"title":"Videos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 23, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 24, \'{"title":"Where to Buy","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 25, \'{"title":"Discussions","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 26, \'{"title":"Ads","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.layout-width", ' . $tab_id . ', 27, \'["[]"]\', NULL),
  
(' . $page_id . ', "widget", "sitereview.review-button", ' . $right_container_id . ', 29, \'{"title":""}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.write-sitereview", ' . $right_container_id . ', 30, \'{"title":"","titleCount":"true","nomobile":"1","name":"sitereview.write-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $right_container_id . ', 31, \'{"title":"Where to Buy","titleCount":"true","layout_column":"1","limit":"4","nomobile":"1","name":"sitereview.price-info-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $right_container_id . ', 32, \'{"title":"Quick Specifications","titleCount":"true","show_specificationlink":"1","itemCount":"5","nomobile":"1","name":"sitereview.quick-specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $right_container_id . ', 33, \'{"title":"Best Alternatives","titleCount":"true","statistics":["likeCount","reviewCount"],"ratingType":"rating_avg","viewType":"0","columnWidth":"180","columnHeight":"328","itemCount":"3","truncation":"16","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $right_container_id . ', 34, \'{"title":"%s\'\'s Properties","titleCount":"true","statistics":["commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnWidth":"180","columnHeight":"328","count":"3","truncation":"16","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.people-like", ' . $right_container_id . ', 35, \'{
        "itemCount":"3", "title":"", "nomobile":"1", "name":"seaocore.people-like"
      }\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $right_container_id . ', 36, \'{"title":"Recently Viewed","titleCount":"true","statistics":["viewCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_avg","fea_spo":"","show":"0","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"16","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.layout-width", ' . $right_container_id . ', 37, \'{"title":"","layoutWidth":"198","layoutWidthType":"px","nomobile":"0","name":"seaocore.layout-width"}\', NULL);
        
');
    }
  }

  public function entertainmentProfileTemplate($listingType) {

    //GET DATABASE
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //GET LISTING TYPE DETAILS
    $listingTypeId = $listingType->listingtype_id;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titlePluUc = ucfirst($listingType->title_plural);
    $titleSinLc = strtolower($listingType->title_singular);
    $titlePluLc = strtolower($listingType->title_plural);

    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', "sitereview_index_view_listtype_" . $listingTypeId)
            ->query()
            ->fetchColumn();

    if (empty($page_id)) {

      $containerCount = 0;
      $widgetCount = 0;

      $db->insert('engine4_core_pages', array(
          'name' => "sitereview_index_view_listtype_" . $listingTypeId,
          'displayname' => 'Multiple Listing Types - ' . $titleSinUc . ' Profile',
          'title' => $titleSinUc . ' Profile',
          'description' => 'This is ' . $titleSinUc . ' profile page.',
          'custom' => 0
      ));
      $page_id = $db->lastInsertId('engine4_core_pages');

      //TOP CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $top_container_id = $db->lastInsertId();

      //MAIN CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => $containerCount++,
      ));
      $main_container_id = $db->lastInsertId();

      //INSERT TOP-MIDDLE
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_container_id,
          'order' => $containerCount++,
      ));
      $top_middle_id = $db->lastInsertId();

      //LEFT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'left',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $left_container_id = $db->lastInsertId();

      //RIGHT CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $right_container_id = $db->lastInsertId();

      //MAIN-MIDDLE CONTAINER
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_container_id,
          'order' => $containerCount++,
      ));
      $main_middle_id = $db->lastInsertId();


      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(' . $page_id . ', "widget", "sitereview.listtypes-categories", ' . $top_middle_id . ', 3, \'{"nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.list-profile-breadcrumb", ' . $top_middle_id . ', 4, \'[""]\', NULL),
        
(' . $page_id . ', "widget", "sitereview.overall-ratings", ' . $left_container_id . ', 7, \'{"title":"","titleCount":"true","show_rating":"both"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.options-sitereview", ' . $left_container_id . ', 8, \'{"title":"","titleCount":"true"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $left_container_id . ', 9, \'{"title":"","pluginName":"communityad","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.related-listings-view-sitereview", ' . $left_container_id . ', 10, \'{"title":"Related Listings","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","related":"categories","viewType":"listview","columnWidth":"180","columnHeight":"328","itemCount":"3","truncation":"21","nomobile":"1","name":"sitereview.related-listings-view-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.share", ' . $left_container_id . ', 11, \'{"title":"Social Share","titleCount":"true","options":["socialShare"],"nomobile":"1","name":"sitereview.share"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.recently-viewed-sitereview", ' . $left_container_id . ', 12, \'{"title":"Recently Viewed by You","titleCount":"true","statistics":["viewCount","reviewCount"],"listingtype_id":"' . $listingTypeId . '","ratingType":"rating_users","fea_spo":"","show":"0","viewType":"listview","columnWidth":"180","columnHeight":"328","truncation":"21","count":"3","nomobile":"1","name":"sitereview.recently-viewed-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.scroll-top", ' . $main_middle_id . ', 14, \'[""]\', NULL),
(' . $page_id . ', "widget", "sitereview.list-information-profile", ' . $main_middle_id . ', 15, \'{"title":"","showContent":["title","postedDate","postedBy","viewCount","likeCount","commentCount","photo","tags","location","compare","wishlist","reviewCreate"],"like_button":"2","actionLinks":"0","nomobile":"0","name":"sitereview.list-information-profile"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.slideshow-list-photo", ' . $main_middle_id . ', 16, \'{"title":"","titleCount":"true","slideshow_height":"400","slideshow_width":"600","showCaption":"1","captionTruncation":"200","nomobile":"1","name":"sitereview.slideshow-list-photo"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-carousel", ' . $main_middle_id . ', 17, \'{"title":"","itemCount":"10","nomobile":"1","name":"sitereview.photos-carousel"}\', NULL)');

      $db->insert('engine4_core_content', array(
          'page_id' => $page_id,
          'type' => 'widget',
          'name' => 'core.container-tabs',
          'parent_content_id' => $main_middle_id,
          'order' => 18,
          'params' => '{"max":"5"}',
      ));
      $tab_id = $db->lastInsertId('engine4_core_content');

      $db->query('
INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES  
(' . $page_id . ', "widget", "sitereview.overview-sitereview", ' . $tab_id . ', 19, \'{"title":"Overview","titleCount":"true","loaded_by_ajax":"1","showAfterEditorReview":"2","showComments":"1","nomobile":"0","name":"sitereview.overview-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.user-sitereview", ' . $tab_id . ', 20, \'{"title":"Reviews","titleCount":"true","loaded_by_ajax":"1","itemProsConsCount":"3","itemReviewsCount":"3","nomobile":"0","name":"sitereview.user-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.discussion-sitereview", ' . $tab_id . ', 21, \'{"title":"Discussions","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.specification-sitereview", ' . $tab_id . ', 22, \'{"title":"Details","titleCount":"true","loaded_by_ajax":"1","nomobile":"0","name":"sitereview.specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $tab_id . ', 23, \'{"title":"Where to Buy","titleCount":"true","layout_column":"0","limit":"20","loaded_by_ajax":"1"}\', NULL),
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 24, \'{"title":"Ads","titleCount":"true","pluginName":"sitevideoview","nomobile":"1"}\', NULL),   
(' . $page_id . ', "widget", "sitereview.video-sitereview", ' . $tab_id . ', 24, \'{"title":"Videos","titleCount":"true","loaded_by_ajax":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.photos-sitereview", ' . $tab_id . ', 25, \'{"title":"Photos","titleCount":"true","loaded_by_ajax":1}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $tab_id . ', 26, \'{"title":"Ads","nomobile":"1","pluginName":"sitepageintegration"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.quick-specification-sitereview", ' . $right_container_id . ', 28, \'{"title":"Quick Details","titleCount":"true","show_specificationlink":"1","show_specificationtext":"Full Details","itemCount":"3","nomobile":"1","name":"sitereview.quick-specification-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.write-sitereview", ' . $right_container_id . ', 29, \'{"title":"","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.price-info-sitereview", ' . $right_container_id . ', 30, \'{"title":"Where to Buy","titleCount":"true","nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.similar-items-sitereview", ' . $right_container_id . ', 31, \'{"title":"Best Alternatives","titleCount":"true","statistics":["viewCount","likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"gridview","columnWidth":"212","columnHeight":"328","itemCount":"3","truncation":"45","nomobile":"1","name":"sitereview.similar-items-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.userlisting-sitereview", ' . $right_container_id . ', 32, \'{"title":"%s\'\'s Listings","titleCount":"true","statistics":["likeCount","commentCount","reviewCount"],"ratingType":"rating_avg","viewType":"listview","columnWidth":"180","columnHeight":"328","count":"3","truncation":"21","nomobile":"1","name":"sitereview.userlisting-sitereview"}\', NULL),
  
(' . $page_id . ', "widget", "seaocore.people-like", ' . $right_container_id . ', 33, \'{"nomobile":"1"}\', NULL),
  
(' . $page_id . ', "widget", "sitereview.ads-plugin-sitereview", ' . $right_container_id . ', 35, \'{"title":"","nomobile":"1","pluginName":"sitetagcheckin"}\', NULL);
        
');
    }
  }

}