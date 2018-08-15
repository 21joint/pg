<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_Listings extends Sitereview_Model_DbTable_Listings
{
    protected $_rowClass = "Sitereview_Model_Listing";
    protected $_name = 'sitereview_listings';
    
    public function getListingTypes($listingtype_id = 0, $params = array()) {
        $table = Engine_Api::_()->getDbTable('listingtypes', 'sitereview');

        if (isset($params['expiry']) && $params['expiry'] == 'nonZero') {
          $columnsArray = array('title_plural', 'listingtype_id', 'expiry','admin_expiry_duration', 'redirection');
        }
        else {
          $columnsArray = array('title_plural', 'listingtype_id', 'redirection');
        }

        $select = $table->select()
                ->from($table->info('name'), $columnsArray)
                ->order("order ASC")
                ->order("listingtype_id ASC")
        ;

        if(!empty($listingtype_id)){
            if(is_array($listingtype_id)){
                $select->where('listingtype_id IN (?)', $listingtype_id);
            }else{
                $select->where('listingtype_id = ?', $listingtype_id);
            }
        }

        if (isset($params['expiry']) && $params['expiry'] == 'nonZero') {
          $select->where('expiry != ?', 0);
        }

        if (isset($params['visible']) && !empty($params['visible'])) {
          $select->where('visible = ?', $params['visible']);
        }

        if(isset($params['member_level_allow']) && !empty($params['member_level_allow'])) {

          $selectListingTypes = $select;

          //GET VIEWER ID
          $viewer = Engine_Api::_()->user()->getViewer();

          foreach($this->fetchAll($selectListingTypes) as $listingType) {
            $listingTypeId = $listingType->listingtype_id;
            $can_view = Engine_Api::_()->authorization()->isAllowed('sitereview_listing', $viewer, "view_listtype_$listingTypeId");
            if(empty($can_view)) {
              $select->where('listingtype_id != ?', $listingTypeId);
            }
          }
        }

        return $this->fetchAll($select);
  }
  
    /**
   * Get listings based on category
   * @param string $title : search text
   * @param int $category_id : category id
   * @param char $popularity : result sorting based on views, reviews, likes, comments
   * @param char $interval : time interval
   * @param string $sqlTimeStr : Time durating string for where clause 
   */
  public function listingsBySettings($params = array()) {

    $groupBy = 1;
    $listingTableName = $this->info('name');
    $viewer = Engine_Api::_()->user()->getViewer();
    $popularity = $params['popularity'];
    $interval = $params['interval'];

    //MAKE TIMING STRING
    $sqlTimeStr = '';
    $current_time = date("Y-m-d H:i:s");
    if ($interval == 'day') {
      $time_duration = date('Y-m-d H:i:s');
      $sqlTimeStr = ".creation_date BETWEEN " . "'" . $time_duration . "'" . " AND " . "'" . $current_time . "'";
    }elseif ($interval == 'week') {
      $time_duration = date('Y-m-d H:i:s', strtotime('-7 days'));
      $sqlTimeStr = ".creation_date BETWEEN " . "'" . $time_duration . "'" . " AND " . "'" . $current_time . "'";
    } elseif ($interval == 'month') {
      $time_duration = date('Y-m-d H:i:s', strtotime('-1 months'));
      $sqlTimeStr = ".creation_date BETWEEN " . "'" . $time_duration . "'" . " AND " . "'" . $current_time . "'" . "";
    }
    
    $select = $this->select()->setIntegrityCheck(false);

    if($popularity != 'end_date') {
      if (Engine_Api::_()->sitereview()->hasPackageEnable()) {
        $select->from($listingTableName, array('listing_id', 'listingtype_id', 'title', 'photo_id', 'owner_id', 'category_id', 'view_count', 'review_count', 'like_count', 'comment_count', 'rating_avg', 'rating_editor', 'rating_users', 'sponsored', 'featured', 'newlabel', 'creation_date', 'body', 'end_date', 'location', 'price','package_id','gg_author_product_rating'));
      }
      else {
        $select->from($listingTableName, array('listing_id', 'listingtype_id', 'title', 'photo_id', 'owner_id', 'category_id', 'view_count', 'review_count', 'like_count', 'comment_count', 'rating_avg', 'rating_editor', 'rating_users', 'sponsored', 'featured', 'newlabel', 'creation_date', 'body', 'end_date', 'location', 'price','gg_author_product_rating'));
      }
    }
    
    $showBasedOnPreferences = false;
    if(is_array($params['listingtype_id'])){
      if (($key = array_search("-1", $params['listingtype_id'])) !== false) {
          unset($params['listingtype_id'][$key]);
      }
      
      if (($key = array_search("9999999", $params['listingtype_id'])) !== false) {
          unset($params['listingtype_id'][$key]);
          $showBasedOnPreferences = true;
      }
    }
    
    if(empty($params['listingtype_id'])){
      unset($params['listingtype_id']);
    }
    
    if($popularity == 'end_date') {
      
      $end_date = ' CASE ';
      $where = ' CASE ';
      $listingTypeIdsArray = array();
      
      $listingTypeId = -1;
      if(isset($params['listingtype_id'])) {
        $listingTypeId = $params['listingtype_id'];
      }
      
      
            
      $listingTypes = $this->getListingTypes($listingTypeId, array('expiry' => 'nonZero'));
      foreach($listingTypes as $listingType) {
        if($listingType->expiry == 1) {
          $end_date .= " WHEN $listingTableName.listingtype_id = $listingType->listingtype_id THEN end_date ";
          
          $where .= " WHEN $listingTableName.listingtype_id = $listingType->listingtype_id THEN $listingTableName.end_date >= '$current_time'";
        }
        elseif($listingType->expiry == 2) {
          $duration = $listingType->admin_expiry_duration;
          $interval_type = $duration[1];
          $interval_type = empty($interval_type) ? 1 : $interval_type;
          $interval_value = $duration[0];
          $interval_value = empty($interval_value) ? 1 : $interval_value;
          
          $approveDate = Engine_Api::_()->sitereview()->adminExpiryDuration($listingType->listingtype_id);

          $end_date .= " WHEN $listingTableName.listingtype_id = $listingType->listingtype_id THEN  DATE_ADD(approved_date, INTERVAL $interval_value $interval_type) ";        
          
          $where .= " WHEN $listingTableName.listingtype_id = $listingType->listingtype_id THEN  DATE_ADD(approved_date, INTERVAL $interval_value $interval_type) >= '$current_time'";  
          
        }
        $listingTypeIdsArray[] = $listingType->listingtype_id;
      }
      
      if(Count($listingTypeIdsArray) > 0) {
        $select->where("$listingTableName.listingtype_id IN (?)", (array) $listingTypeIdsArray);
      }
      
      $end_date .= ' END  ';
      $where .= ' END ';

      $end_date = new Zend_Db_Expr($end_date);
      $where = new Zend_Db_Expr($where);
      
      if(Count($listingTypes) > 0) {
        $columnArray = array('listing_id', 'listingtype_id', 'title', 'photo_id', 'owner_id', 'category_id', 'view_count', 'review_count', 'like_count', 'comment_count', 'rating_avg', 'rating_editor', 'rating_users', 'sponsored', 'featured', 'newlabel', 'creation_date', 'body', 'approved_date','end_date' => $end_date,'gg_author_product_rating');
        $select->from($listingTableName, $columnArray);      

        $select->where($where);
        $select->order("end_date ASC");
      }
    }
    elseif ($interval != 'overall' && $popularity == 'review_count') {
        
      $popularityTable = Engine_Api::_()->getDbtable('reviews', 'sitereview');
      $popularityTableName = $popularityTable->info('name');
      $select = $select->joinLeft($popularityTableName, "($popularityTableName.resource_id = $listingTableName.listing_id and $reviewTableName .resource_type ='sitereview_listing')", array("COUNT(review_id) as total_count"))
              ->where($popularityTableName . "$sqlTimeStr  or " . $popularityTableName . '.creation_date is null')
              ->order("total_count DESC");

      } elseif ($interval != 'overall' && ($popularity == 'rating_avg' || $popularity == 'rating_editor' || $popularity == 'rating_users')) {
      if ($interval == 'day') {
        $time_duration = date('Y-m-d H:i:s');
        $sqlTimeStr = ".creation_date BETWEEN " . "'" . $time_duration . "'" . " AND " . "'" . $current_time . "'";
      }elseif ($interval == 'week') {
        $time_duration = date('Y-m-d H:i:s', strtotime('-7 days'));
        $sqlTimeStr = ".modified_date BETWEEN " . "'" . $time_duration . "'" . " AND " . "'" . $current_time . "'";
      } elseif ($interval == 'month') {
        $time_duration = date('Y-m-d H:i:s', strtotime('-1 months'));
        $sqlTimeStr = ".modified_date BETWEEN " . "'" . $time_duration . "'" . " AND " . "'" . $current_time . "'" . "";
      }

      $ratingTable = Engine_Api::_()->getDbTable('ratings', 'sitereview');
      $ratingTableName = $ratingTable->info('name');

      $popularityTable = Engine_Api::_()->getDbtable('reviews', 'sitereview');
      $popularityTableName = $popularityTable->info('name');
      $select = $select->joinLeft($popularityTableName, $popularityTableName . '.resource_id = ' . $listingTableName . '.listing_id', array(""))
              ->join($ratingTableName, $ratingTableName . '.review_id = ' . $popularityTableName . '.review_id')
              ->where($popularityTableName . '.resource_type = ?', 'sitereview_listing')
              ->where($ratingTableName . '.ratingparam_id = ?', 0);

      if ($popularity == 'rating_editor') {
        $select->where("$popularityTableName.type = ?", 'editor');
      } elseif ($popularity == 'rating_users') {
        $select->where("$popularityTableName.type = ?", 'user')
                ->orWhere("$popularityTableName.type = ?", 'visitor');
      }

      $select->where($popularityTableName . "$sqlTimeStr  or " . $popularityTableName . '.modified_date is null');
      $select->order("$listingTableName.$popularity DESC");
    } elseif ($interval != 'overall' && $popularity == 'like_count') {

      $popularityTable = Engine_Api::_()->getDbtable('likes', 'core');
      $popularityTableName = $popularityTable->info('name');

      $select = $select->joinLeft($popularityTableName, $popularityTableName . '.resource_id = ' . $listingTableName . '.listing_id', array("COUNT(like_id) as total_count"))
              ->where($popularityTableName . "$sqlTimeStr  or " . $popularityTableName . '.creation_date is null')
              ->order("total_count DESC");
    } elseif ($interval != 'overall' && $popularity == 'comment_count') {

      $popularityTable = Engine_Api::_()->getDbtable('comments', 'core');
      $popularityTableName = $popularityTable->info('name');

      $select = $select->joinLeft($popularityTableName, $popularityTableName . '.resource_id = ' . $listingTableName . '.listing_id', array("COUNT(comment_id) as total_count"))
              ->where($popularityTableName . "$sqlTimeStr  or " . $popularityTableName . '.creation_date is null')
              ->order("total_count DESC");
    } elseif ($popularity == 'most_discussed') {

      $popularityTable = Engine_Api::_()->getDbtable('posts', 'sitereview');
      $popularityTableName = $popularityTable->info('name');
      $select = $select->joinLeft($popularityTableName, $popularityTableName . '.listing_id = ' . $listingTableName . '.listing_id', array("COUNT(post_id) as total_count"))
              ->order("total_count DESC");

      if ($interval != 'overall') {
        $select->where($popularityTableName . "$sqlTimeStr  or " . $popularityTableName . '.creation_date is null');
      }
    } elseif ($popularity == 'view_count' || $popularity == 'listing_id' || $popularity == 'modified_date' || $popularity == 'creation_date') {
      $select->order("$listingTableName.$popularity DESC");
    } elseif ($interval == 'overall' && ($popularity == 'review_count' || $popularity == 'like_count' || $popularity == 'comment_count' || $popularity == 'rating_avg' || $popularity == 'rating_editor' || $popularity == 'rating_users')) {
      $select->order("$listingTableName.$popularity DESC");
    }
    if($interval != 'overall'){
        $listingtype_id = (isset($params['listingtype_id']) && !empty($params['listingtype_id']) && $params['listingtype_id'] != -1) ? $params['listingtype_id'] : -1;
        $select = $this->expirySQL($select, $listingtype_id);
        $select->group($listingTableName . '.listing_id');
        $select->where($listingTableName . '.closed = ?', '0')
    //            ->where($listingTableName . '.approved = ?', '1')
                ->where($listingTableName . '.search = ?', '1')
                ->where($listingTableName . '.draft = ?', '0')
                ->where($listingTableName .$sqlTimeStr);
    }else{
        $listingtype_id = (isset($params['listingtype_id']) && !empty($params['listingtype_id']) && $params['listingtype_id'] != -1) ? $params['listingtype_id'] : -1;
        $select = $this->expirySQL($select, $listingtype_id);
        $select->group($listingTableName . '.listing_id');
        $select->where($listingTableName . '.closed = ?', '0')
    //            ->where($listingTableName . '.approved = ?', '1')
                ->where($listingTableName . '.search = ?', '1')
                ->where($listingTableName . '.draft = ?', '0')
                ->where($listingTableName . '.creation_date <= ?', date('Y-m-d H:i:s'));
    }
    if (isset($params['detactLocation']) && $params['detactLocation'] && isset($params['latitude']) && $params['latitude'] && isset($params['longitude']) && $params['longitude'] && isset($params['defaultLocationDistance']) && $params['defaultLocationDistance']) {
      $locationsTable = Engine_Api::_()->getDbtable('locations', 'sitereview');
      $locationTableName = $locationsTable->info('name');
      $radius = $params['defaultLocationDistance']; //in miles
      $latitude = $params['latitude'];
      $longitude = $params['longitude'];
      $flage = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.proximity.search.kilometer', 0);
      if (!empty($flage)) {
        $radius = $radius * (0.621371192);
      }
     //  $latitudeRadians = deg2rad($latitude);
      $latitudeSin = "sin(radians($latitude))"; //sin($latitudeRadians);
      $latitudeCos = "cos(radians($latitude))";// cos($latitudeRadians);

      $select->join($locationTableName, "$listingTableName.listing_id = $locationTableName.listing_id", array("(degrees(acos($latitudeSin * sin(radians($locationTableName.latitude)) + $latitudeCos * cos(radians($locationTableName.latitude)) * cos(radians($longitude - $locationTableName.longitude)))) * 69.172) AS distance", $locationTableName.'.location AS locationName'));
      $sqlstring = "(degrees(acos($latitudeSin * sin(radians($locationTableName.latitude)) + $latitudeCos * cos(radians($locationTableName.latitude)) * cos(radians($longitude - $locationTableName.longitude)))) * 69.172 <= " . "'" . $radius . "'";
      $sqlstring .= ")";
      $select->where($sqlstring);
      $select->order("distance");
      //$select->group("$listingTableName.listing_id");
    }     
    
    $searchAliases = Engine_Api::_()->getDbtable('searchTerms', 'sdparentalguide')->getSearchAliases($params['search']);
    $db = $this->getAdapter();
    $useOR = false;
    $likeWhere = "";
    foreach($searchAliases as $searchAlias){
        if($useOR){
            $likeWhere .= " OR ";
        }
        $likeWhere .= new Zend_Db_Expr($db->quoteInto(' (`title` LIKE  ? ) ', "%".$searchAlias."%"));
        $useOR = true;
    }
    if(!empty($likeWhere)){
        $select->where($likeWhere);
    }
//    
//    if (isset($params['search']) && !empty($params['search'])) {
//      $select->where('title LIKE ? OR body LIKE ?', "%".$params['search']."%");
//    }

    if (isset($params['featured']) && !empty($params['featured'])) {
      $select->where('featured = ?', 1);
    }

    if (isset($params['sponsored']) && !empty($params['sponsored'])) {
      $select->where('sponsored = ?', 1);
    }

    if (isset($params['newlabel']) && !empty($params['newlabel'])) {
      $select->where('newlabel = ?', 1);
    }
    
    if (isset($params['sponsored_or_featured']) && !empty($params['sponsored_or_featured'])) {
      $select->where("$listingTableName.featured = 1 OR $listingTableName.sponsored = 1");
    }
    
    if(!empty($params['listing_created'])){
        if($params['listing_created'] == "day"){
            $select->where("DATE($listingTableName.creation_date) = ?",date("Y-m-d"));
        }else if($params['listing_created'] == 'week'){
            $time_duration = date('Y-m-d', strtotime('-7 days'));
            $select->where("DATE($listingTableName.creation_date) >= ?",$time_duration);
        }else if($params['listing_created'] == 'month'){
            $time_duration = date('Y-m-d', strtotime('-1 months'));
            $select->where("DATE($listingTableName.creation_date) >= ?",$time_duration);
        }
    }


    if ( isset($params['createdbyfriends']) && !empty($params['createdbyfriends']) && !empty($params['users'])) {
      $str = (string) ( is_array($params['users']) ? "'" . join("', '", $params['users']) . "'" : $params['users'] );
      $select->where($listingTableName . '.owner_id in (?)', new Zend_Db_Expr($str));
    }  

    if (isset($params['thatIcreated']) && !empty($params['thatIcreated'])) {
      $select->where($listingTableName . '.owner_id = ?', $params['thatIcreated']);
    }
        
    if (isset($params['thatIliked']) && !empty($params['thatIliked'])) {
      $tableLikesName = Engine_Api::_()->getDbtable('likes', 'core')->info('name');
      $select->join($tableLikesName, "$listingTableName.listing_id = $tableLikesName.resource_id")
              ->where($tableLikesName . '.resource_type = ?', 'sitereview_listing')
              ->where("$tableLikesName.poster_id = ?",$viewer->getIdentity());
    }
    
    if (isset($params['createdbyfriends']) && $params['createdbyfriends'] == '2' && empty($params['users'])) {
      $select->where($listingTableName . '.owner_id = ?', '0');
    }
 
    if (isset($params['listingtype_id']) && !empty($params['listingtype_id']) && $params['listingtype_id'] != -1) {
        if(is_array($params['listingtype_id'])){
            $select->where($listingTableName . '.listingtype_id IN (?)', $params['listingtype_id']);
        }else{
            $select->where($listingTableName . '.listingtype_id = ?', $params['listingtype_id']);
        }      
    }
    
    if($showBasedOnPreferences && $viewer->getIdentity()){
        $categoryIds = Sdparentalguide_Form_Signup_Interests::getSavedPreferences();
        if(count($categoryIds) > 0){
            $select->where($listingTableName . '.category_id IN (?)', $categoryIds);
        }
    }

    if (isset($params['approved']) && !empty($params['approved'])) {
      $select->where($listingTableName . '.approved = ?', 1);
    }
    
    if (isset($params['non_approved']) && !empty($params['non_approved'])) {
      $select->where($listingTableName . '.approved = ?', 0);
    }
        
    if (isset($params['category_id']) && !empty($params['category_id'])) {
      $select->where($listingTableName . '.category_id = ?', $params['category_id']);
    }

    if (isset($params['subcategory_id']) && !empty($params['subcategory_id'])) {
      $select->where($listingTableName . '.subcategory_id = ?', $params['subcategory_id']);
    }

    if (isset($params['subsubcategory_id']) && !empty($params['subsubcategory_id'])) {
      $select->where($listingTableName . '.subsubcategory_id = ?', $params['subsubcategory_id']);
    }

    if (isset($params['popularity']) && !empty($params['popularity']) && $params['popularity'] != 'creation_date' && $params['popularity'] != 'creation_date' && $params['popularity'] != 'random') {
      $select->order($listingTableName . ".creation_date DESC");
    }

    if (isset($params['popularity']) && $params['popularity'] == 'random') {
      $select->order('RAND() DESC ');
    }

    //Start Network work
    $select = $this->getNetworkBaseSql($select, array('not_groupBy' => $groupBy));

    //End Network work
    if (isset($params['paginator']) && !empty($params['paginator'])) {
      $paginator = Zend_Paginator::factory($select);
      if (isset($params['page']) && !empty($params['page'])) {
        $paginator->setCurrentPageNumber($params['page']);
      }

      if (isset($params['limit']) && !empty($params['limit'])) {
        $paginator->setItemCountPerPage($params['limit']);
      } 
      return $paginator;
    }
    if (isset($params['limit']) && !empty($params['limit'])) {
      $select->limit($params['limit']);
    }
    
    return $this->fetchAll($select);
  }
  
  /**
   * Get pages to add as item of the day
   * @param string $title : search text
   * @param int $limit : result limit
   */
  public function getDayItems($title, $limit = 10, $listingtype_id = 0) {

    //MAKE QUERY
    $select = $this->select()
            ->from($this->info('name'), array('listing_id', 'listingtype_id', 'owner_id', 'title', 'photo_id'))
            ->where('closed = ?', '0')
            ->where('approved = ?', '1')
            ->where('draft = ?', '0')
            ->where('creation_date <= ?', date('Y-m-d H:i:s'))
            ->where('search = ?', '1')
            ->order('title ASC')
            ->limit($limit);
    
    $searchAliases = Engine_Api::_()->getDbtable('searchTerms', 'sdparentalguide')->getSearchAliases($title);
    $db = $this->getAdapter();
    $select = $this->expirySQL($select, $listingtype_id);
    if ($listingtype_id > 0) {
      $select->where('listingtype_id = ?', $listingtype_id);
    }
    
    $useOR = false;
    $likeWhere = "";
    foreach($searchAliases as $searchAlias){
        if($useOR){
            $likeWhere .= " OR ";
        }
        $likeWhere .= new Zend_Db_Expr($db->quoteInto(' (`title` LIKE  ? ) ', "%".$searchAlias."%"));
        $useOR = true;
    }
    if(!empty($likeWhere)){
        $select->where($likeWhere);
    }

    //RETURN RESULTS
    return $this->fetchAll($select);
  }
  
  
  public function getSitereviewsSelect($params = array(), $customParams = null) {

    //GET LISTING TABLE NAME
    $sitereviewTableName = $this->info('name');
    $tempSelect = array();
    global $sitereviewSelectQuery;

    //GET TAGMAP TABLE NAME
    $tagMapTableName = Engine_Api::_()->getDbtable('TagMaps', 'core')->info('name');

    //GET SEARCH TABLE
    $searchTable = Engine_Api::_()->fields()->getTable('sitereview_listing', 'search')->info('name');

    //GET LOCATION TABLE
    $locationTable = Engine_Api::_()->getDbtable('locations', 'sitereview');
    $locationTableName = $locationTable->info('name');

    //GET API
    $settings = Engine_Api::_()->getApi('settings', 'core');

    //MAKE QUERY
    $select = $this->select();

    $select = $select
            ->setIntegrityCheck(false)
            ->from($sitereviewTableName)
            //->joinLeft($locationTableName, "$sitereviewTableName.listing_id = $locationTableName.listing_id   ", array())
            ->group($sitereviewTableName . '.listing_id');

    if (isset($params['type']) && !empty($params['type'])) {
      $listingtype_id = (isset($params['listingtype_id']) && !empty($params['listingtype_id']) && $params['listingtype_id'] != -1) ? $params['listingtype_id'] : -1;
      if ($params['type'] == 'browse' || $params['type'] == 'home') {
        $select = $select
                ->where($sitereviewTableName . '.approved = ?', '1')
                ->where($sitereviewTableName . '.draft = ?', '0')
                ->where($sitereviewTableName . '.creation_date <= ?', date('Y-m-d H:i:s'));
        $showExpiry = (isset($params['show']) && $params['show'] == 'only_expiry') ? 1 : 0;
        $select = $this->expirySQL($select, $listingtype_id, $showExpiry);

        if ($params['type'] == 'browse' && isset($params['showClosed']) && !$params['showClosed']) {
          $select = $select->where($sitereviewTableName . '.closed = ?', '0');
        }
      } elseif ($params['type'] == 'browse_home_zero') {
        $select = $select
                ->where($sitereviewTableName . '.closed = ?', '0')
                ->where($sitereviewTableName . '.approved = ?', '1')
                ->where($sitereviewTableName . '.draft = ?', '0')
                ->where($sitereviewTableName . '.creation_date <= ?', date('Y-m-d H:i:s'));

        $select = $this->expirySQL($select, $listingtype_id);
      }
      if ($params['type'] != 'manage') {
        $select->where($sitereviewTableName . ".search = ?", 1);
      }
    }

    if (isset($customParams) && !empty($customParams)) {

      //PROCESS OPTIONS
      $tmp = array();
      foreach ($customParams as $k => $v) {
        if (null == $v || '' == $v || (is_array($v) && count(array_filter($v)) == 0)) {
          continue;
        } else if (false !== strpos($k, '_field_')) {
          list($null, $field) = explode('_field_', $k);
          $tmp['field_' . $field] = $v;
        } else if (false !== strpos($k, '_alias_')) {
          list($null, $alias) = explode('_alias_', $k);
          $tmp[$alias] = $v;
        } else {
          $tmp[$k] = $v;
        }
      }
      $customParams = $tmp;

      $select = $select
              ->setIntegrityCheck(false)
              ->joinLeft($searchTable, "$searchTable.item_id = $sitereviewTableName.listing_id", null);

      $searchParts = Engine_Api::_()->fields()->getSearchQuery('sitereview_listing', $customParams);
      foreach ($searchParts as $k => $v) {
        $select->where("`{$searchTable}`.{$k}", $v);
      }
    }

    $addGroupBy = 1;
    if (!isset($params['location']) && (isset($params['detactLocation']) && $params['detactLocation'] && isset($params['latitude']) && $params['latitude'] && isset($params['longitude']) && $params['longitude'] && isset($params['defaultLocationDistance']) && $params['defaultLocationDistance'])) {

      $locationsTable = Engine_Api::_()->getDbtable('locations', 'sitereview');
      $locationTableName = $locationsTable->info('name');
      $radius = $params['defaultLocationDistance']; //in miles
      $latitude = $params['latitude'];
      $longitude = $params['longitude'];
      $flage = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.proximity.search.kilometer', 0);
      if (!empty($flage)) {
        $radius = $radius * (0.621371192);
      }
    //  $latitudeRadians = deg2rad($latitude);
      $latitudeSin = "sin(radians($latitude))"; //sin($latitudeRadians);
      $latitudeCos = "cos(radians($latitude))";// cos($latitudeRadians);

      $select->join($locationTableName, "$sitereviewTableName.listing_id = $locationTableName.listing_id", array("(degrees(acos($latitudeSin * sin(radians($locationTableName.latitude)) + $latitudeCos * cos(radians($locationTableName.latitude)) * cos(radians($longitude - $locationTableName.longitude)))) * 69.172) AS distance", $locationTableName.'.location AS locationName'));
      $sqlstring = "(degrees(acos($latitudeSin * sin(radians($locationTableName.latitude)) + $latitudeCos * cos(radians($locationTableName.latitude)) * cos(radians($longitude - $locationTableName.longitude)))) * 69.172 <= " . "'" . $radius . "'";
      $sqlstring .= ")";
      $select->where($sqlstring);
      $select->order("distance");
      $select->group("$sitereviewTableName.listing_id");
      $addGroupBy = 0;
    }    

    if (isset($params['sitereview_street']) && !empty($params['sitereview_street']) || isset($params['sitereview_city']) && !empty($params['sitereview_city']) || isset($params['sitereview_state']) && !empty($params['sitereview_state']) || isset($params['sitereview_country']) && !empty($params['sitereview_country'])) {
      $select->join($locationTableName, "$sitereviewTableName.listing_id = $locationTableName.listing_id   ", null);
    }

    if (isset($params['sitereview_street']) && !empty($params['sitereview_street'])) {
      $select->where($locationTableName . '.address   LIKE ? ', '%' . $params['sitereview_street'] . '%');
    } if (isset($params['sitereview_city']) && !empty($params['sitereview_city'])) {
      $select->where($locationTableName . '.city = ?', $params['sitereview_city']);
    } if (isset($params['sitereview_state']) && !empty($params['sitereview_state'])) {
      $select->where($locationTableName . '.state = ?', $params['sitereview_state']);
    } if (isset($params['sitereview_country']) && !empty($params['sitereview_country'])) {
      $select->where($locationTableName . '.country = ?', $params['sitereview_country']);
    }

    if ((isset($params['location']) && !empty($params['location'])) || (!empty($params['Latitude']) && !empty($params['Longitude']))) {
      $enable = $settings->getSetting('sitereview.proximitysearch', 1);
      if (isset($params['locationmiles']) && (!empty($params['locationmiles']) && !empty($enable))) {
        $longitude = 0;
        $latitude = 0;
        $selectLocQuery = $locationTable->select()->where('location = ?', $params['location']);
        $locationValue = $locationTable->fetchRow($selectLocQuery);

        //check for zip code in location search.
        if (empty($params['Latitude']) && empty($params['Longitude'])) {
          if (empty($locationValue)) {
            $locationResults = Engine_Api::_()->getApi('geoLocation', 'seaocore')->getLatLong(array('location' => $params['location'], 'module' => 'Multiple Listing Types'));
            if(!empty($locationResults['latitude']) && !empty($locationResults['longitude'])) {
                $latitude = $locationResults['latitude'];
                $longitude = $locationResults['longitude'];
            }
          } else {
            $latitude = (float) $locationValue->latitude;
            $longitude = (float) $locationValue->longitude;
          }
        } else {
          $latitude = (float) $params['Latitude'];
          $longitude = (float) $params['Longitude'];
        }

        $radius = $params['locationmiles'];

        $flage = $settings->getSetting('sitereview.proximity.search.kilometer', 0);
        if (!empty($flage)) {
          $radius = $radius * (0.621371192);
        }
        //  $latitudeRadians = deg2rad($latitude);
      $latitudeSin = "sin(radians($latitude))"; //sin($latitudeRadians);
      $latitudeCos = "cos(radians($latitude))";// cos($latitudeRadians);
        $select->join($locationTableName, "$sitereviewTableName.listing_id = $locationTableName.listing_id", array("(degrees(acos($latitudeSin * sin(radians($locationTableName.latitude)) + $latitudeCos * cos(radians($locationTableName.latitude)) * cos(radians($longitude - $locationTableName.longitude)))) * 69.172) AS distance"));
        $sqlstring = "(degrees(acos($latitudeSin * sin(radians($locationTableName.latitude)) + $latitudeCos * cos(radians($locationTableName.latitude)) * cos(radians($longitude - $locationTableName.longitude)))) * 69.172 <= " . "'" . $radius . "'";
        $sqlstring .= ")";
        $select->where($sqlstring);
        $select->order("distance");
      } else {
        $select->join($locationTableName, "$sitereviewTableName.listing_id = $locationTableName.listing_id", null);
        $select->where("`{$locationTableName}`.formatted_address LIKE ? or `{$locationTableName}`.location LIKE ? or `{$locationTableName}`.city LIKE ? or `{$locationTableName}`.state LIKE ?", "%" . $params['location'] . "%");
      }
    }

    if (isset($params['type']) && !empty($params['type']) && ($params['type'] == 'browse' || $params['type'] == 'home')) { 
      if($addGroupBy) { 
        $select = $this->getNetworkBaseSql($select, array('show' => $params['show']));
      }
      else {
        $select = $this->getNetworkBaseSql($select, array('not_groupBy' => 1, 'show' => $params['show']));
      } 
    }
    $api = Engine_Api::_()->sitereview();
    if (isset($params['price']['min']) && !empty($params['price']['min'])) {
      $select->where($sitereviewTableName . '.price >= ?', $api->getPriceWithCurrency($params['price']['min'], 1, 1));
    }

    if (isset($params['price']['max']) && !empty($params['price']['max'])) {
      $select->where($sitereviewTableName . '.price <= ?', $api->getPriceWithCurrency($params['price']['max'], 1, 1));
    }

    if (!empty($params['user_id']) && is_numeric($params['user_id'])) {
      $select->where($sitereviewTableName . '.owner_id = ?', $params['user_id']);
    }

    if (!empty($params['user']) && $params['user'] instanceof User_Model_User) {
      $select->where($sitereviewTableName . '.owner_id = ?', $params['user_id']->getIdentity());
    }

    if (!empty($params['users'])) {
      $str = (string) ( is_array($params['users']) ? "'" . join("', '", $params['users']) . "'" : $params['users'] );
      $select->where($sitereviewTableName . '.owner_id in (?)', new Zend_Db_Expr($str));
    }

    if (empty($params['users']) && isset($params['show']) && $params['show'] == '2') {
      $select->where($sitereviewTableName . '.owner_id = ?', '0');
    }

    if ((isset($params['show']) && $params['show'] == "4")) {
      $likeTableName = Engine_Api::_()->getDbtable('likes', 'core')->info('name');
      $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
      $select->setIntegrityCheck(false)
              ->join($likeTableName, "$likeTableName.resource_id = $sitereviewTableName.listing_id")
              ->where($likeTableName . '.poster_type = ?', 'user')
              ->where($likeTableName . '.poster_id = ?', $viewer_id)
              ->where($likeTableName . '.resource_type = ?', 'sitereview_listing');
    }

    if (!empty($params['tag_id'])) {
      $select
              ->setIntegrityCheck(false)
              ->joinLeft($tagMapTableName, "$tagMapTableName.resource_id = $sitereviewTableName.listing_id", array('tagmap_id', 'resource_type', 'resource_id', 'tag_id'))
              ->where($tagMapTableName . '.resource_type = ?', 'sitereview_listing')
              ->where($tagMapTableName . '.tag_id = ?', $params['tag_id']);
    }

    if (isset($params['listingtype_id']) && !empty($params['listingtype_id']) && $params['listingtype_id'] != -1) {
      $select->where($sitereviewTableName . '.listingtype_id = ?', $params['listingtype_id']);
    }

    if (isset($params['category_id']) && !empty($params['category_id'])) {
      $select->where($sitereviewTableName . '.category_id = ?', $params['category_id']);
    }

    if (isset($params['subcategory_id']) && !empty($params['subcategory_id'])) {
      $select->where($sitereviewTableName . '.subcategory_id = ?', $params['subcategory_id']);
    }

    if (isset($params['subsubcategory_id']) && !empty($params['subsubcategory_id'])) {
      $select->where($sitereviewTableName . '.subsubcategory_id = ?', $params['subsubcategory_id']);
    }

    if (isset($params['closed']) && $params['closed'] != "") {
      $select->where($sitereviewTableName . '.closed = ?', $params['closed']);
    }

    // Could we use the search indexer for this?
    if (!empty($params['search'])) {

      $tagName = Engine_Api::_()->getDbtable('Tags', 'core')->info('name');
      $select
              ->setIntegrityCheck(false)
              ->joinLeft($tagMapTableName, "$tagMapTableName.resource_id = $sitereviewTableName.listing_id and " . $tagMapTableName . ".resource_type = 'sitereview_listing'", array('tagmap_id', 'resource_type', 'resource_id', 'tag_id'))
              ->joinLeft($tagName, "$tagName.tag_id = $tagMapTableName.tag_id");

      $select->where($sitereviewTableName . ".title LIKE ? OR " . $sitereviewTableName . ".body LIKE ? OR " . $tagName . ".text LIKE ? ", '%' . $params['search'] . '%');
    }

    if (!empty($params['start_date'])) {
      $select->where($sitereviewTableName . ".creation_date > ?", date('Y-m-d', $params['start_date']));
    }

    if (!empty($params['end_date'])) {
      $select->where($sitereviewTableName . ".creation_date < ?", date('Y-m-d', $params['end_date']));
    }

    if (!empty($params['has_photo'])) {
      $select->where($sitereviewTableName . ".photo_id > ?", 0);
    }

    if (!empty($params['has_review'])) {
      $has_review = $params['has_review'];
      $select->where($sitereviewTableName . ".$has_review > ?", 0);
    }

    if(isset($params['most_rated'])) {
      $select->order($sitereviewTableName . '.' . 'rating_avg'. ' DESC');
    }
      
    if (!empty($params['orderby']) && $params['orderby'] == "title") {
      $select->order($sitereviewTableName . '.' . $params['orderby']);
    } else if (!empty($params['orderby']) && $params['orderby'] == "fespfe") {
      $select->order($sitereviewTableName . '.sponsored' . ' DESC')
              ->order($sitereviewTableName . '.featured' . ' DESC');
    } else if (!empty($params['orderby']) && $params['orderby'] == "spfesp") {
      $select->order($sitereviewTableName . '.featured' . ' DESC')
              ->order($sitereviewTableName . '.sponsored' . ' DESC');
    } else if (!empty($params['orderby']) && $params['orderby'] != 'creation_date') {
      $select->order($sitereviewTableName . '.' . $params['orderby'] . ' DESC');
    }
    $select->order($sitereviewTableName . '.creation_date DESC');
    return $select;
  }
} 




