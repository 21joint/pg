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
} 




