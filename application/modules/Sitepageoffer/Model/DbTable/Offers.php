<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Offers.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Model_DbTable_Offers extends Engine_Db_Table {

  protected $_rowClass = "Sitepageoffer_Model_Offer";

  /**
   * Get page offers if sticky is 1
   *
   * @param int $page_Id
   * @return array Zend_Db_Table_Select;
   */
  public function getSitepageoffer($page_id) {
    $result_offer = $this->fetchRow(array("page_id =?" => $page_id, 'sticky= ?' => "1"));
    return $result_offer;
  }

  /**
   * Get page offer detail
   *
   * @param int $offerId
   * @return array Zend_Db_Table_Select;
   */
  public function getOfferDetail($offerId) {

    $select = $this->select()->where($this->info('name') . '.offer_id = ?', $offerId)->limit(1);
    return $this->fetchRow($select);
  }

  /**
   * Make sticky and corrosponding data entry
   *
   * @param int $offer_id
   * @param int $page_id
   */
  public function makeSticky($offer_id, $page_id) {

    $sticky = $this->select()
                    ->from($this->info('name'), array('sticky'))
                    ->where('offer_id = ?', $offer_id)
                    ->query()
                    ->fetchColumn();
    if (!empty($sticky)) {
      $this->update(array('sticky' => 0), array('offer_id = ?' => $offer_id, 'page_id = ?' => $page_id));
      $sitepageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
      $sitepageTable->update(array('offer' => 0), array('page_id = ?' => $page_id));
    } else {
      $this->update(array('sticky' => 1), array('offer_id = ?' => $offer_id, 'page_id = ?' => $page_id));
      $sitepageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
      $sitepageTable->update(array('offer' => 1), array('page_id = ?' => $page_id));
      $this->update(array('sticky' => 0), array('offer_id != ?' => $offer_id, 'page_id = ?' => $page_id));
    }
  }

  /**
   * Return page offers
   *
   * @param int $totalOffers
   * @param string $offerType
   * @return Zend_Db_Table_Select
   */
  public function getWidgetOffers($totalOffers, $offerType,$category_id,$popularity = null) {

    //OFFER TABLE NAME
    $offerTableName = $this->info('name');

    //PAGE TABLE
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');
  
    $pagePackagesTable = Engine_Api::_()->getDbtable('packages', 'sitepage');
    $pagePackageTableName = $pagePackagesTable->info('name');

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $currentTime = date("Y-m-d H:i:s");
    if (!empty($viewer_id)) {
      // Convert times
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
      $currentTime = date("Y-m-d H:i:s");
      date_default_timezone_set($oldTz);
    }
    
    //QUERY MAKING
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($pageTableName, array('photo_id', 'title as sitepage_title'))
                    ->join($offerTableName, $offerTableName . '.page_id = ' . $pageTableName . '.page_id');

    if ($offerType == 'sponsored') {
       
        $select = $select->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)");
				$select->join($pagePackageTableName, "$pagePackageTableName.package_id = $pageTableName.package_id",array('package_id', 'price'));
        $select->where($pagePackageTableName . '.price != ?', '0.00');
        $select->order($pagePackageTableName . '.price' . ' DESC');

    }

    if ($offerType == 'hot') {
      $select = $select->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)  AND ($offerTableName.hotoffer  = 1)")
                      ->order('RAND() DESC ');
    } elseif ($offerType == 'latest') {
      $select = $select->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)")
                      ->limit($totalOffers)
                      ->order('creation_date DESC');
    }

    if ($offerType == 'alloffers') {
      $select = $select->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)");
			if ($popularity == 'view_count') {
				$select->order($offerTableName . '.view_count' . ' DESC');
			}
      elseif ($popularity == 'like_count') {
			  $select->order($offerTableName . '.like_count' . ' DESC');
			}
			elseif ($popularity == 'comment_count') {
				$select->order($offerTableName . '.comment_count' . ' DESC');
			}
			elseif ($popularity == 'popular') {
        $select->where($offerTableName . '.claimed !=?','0');
				$select->order($offerTableName . '.claimed' . ' DESC');
        $select->order($offerTableName . '.creation_date DESC');
			}
    }


    if (!empty($category_id)) {
			$select = $select->where($pageTableName . '.	category_id =?', $category_id);
		}
    $select = $select->limit($totalOffers);

    $select = $select
                    ->where($pageTableName . '.closed = ?', '0')
                    ->where($pageTableName . '.approved = ?', '1')
                    ->where($pageTableName . '.search = ?', '1')
                    ->where($pageTableName . '.declined = ?', '0')
                    ->where($pageTableName . '.draft = ?', '1');

    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $select->where($pageTableName . '.expiration_date  > ?', $currentTime);
    }

    //Start Network work
    $select = $pageTable->getNetworkBaseSql($select, array('not_groupBy' => 1, 'extension_group' => $offerTableName . ".offer_id"));
    //End Network work
    return $this->fetchAll($select);
  }

  /**
   * Return page offers
   *
   * @param string $hotOffer
   * @return Zend_Db_Table_Select
   */
  public function getOffers($hotOffer = 'null',$params = array(),$sponsoredOffer = null, $customParams = null) {

    //OFFER TABLE NAME
    $offerTableName = $this->info('name');

    //PAGE TABLE
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');

    $pagePackagesTable = Engine_Api::_()->getDbtable('packages', 'sitepage');
    $pagePackageTableName = $pagePackagesTable->info('name');
    
    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $currentTime = date("Y-m-d H:i:s");
    if (!empty($viewer_id)) {
      // Convert times
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
      $currentTime = date("Y-m-d H:i:s");
    }
    
    //QUERY MAKING
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($pageTableName, array('photo_id', 'title as sitepage_title'))
                    ->join($offerTableName, $offerTableName . '.page_id = ' . $pageTableName . '.page_id')
                    ->join($pagePackageTableName, "$pagePackageTableName.package_id = $pageTableName.package_id",array('package_id', 'price'));

    if (empty($hotOffer) && (isset($params['orderby']) && $params['orderby'] != 'end_offer')) {
      $select = $select
                      ->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)");
    } elseif ($hotOffer == 1) {
      $select = $select
                      ->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)  AND ($offerTableName.hotoffer  = 1)");
    }

    if(!empty($sponsoredOffer)) {

      $select->where($pagePackageTableName . '.price != ?', '0.00');
      $select->order($pagePackageTableName . '.price' . ' DESC');

    }

    $searchTable = Engine_Api::_()->fields()->getTable('sitepageoffer_offer', 'search')->info('name');

    if (isset($customParams)) {
      $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
      $coreversion = $coremodule->version;
      if ($coreversion > '4.1.7') {

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
      }

      $select = $select
              ->setIntegrityCheck(false)
              ->joinLeft($searchTable, "$searchTable.item_id = $offerTableName.offer_id", null);

      $searchParts = Engine_Api::_()->fields()->getSearchQuery('sitepageoffer_offer', $customParams);
      foreach ($searchParts as $k => $v) {
        //$v = str_replace("%2C%20",", ",$v);
        $select->where("`{$searchTable}`.{$k}", $v);
      }
    }
    if (!empty($params['category'])) {
      $select->where($pageTableName . '.category_id = ?', $params['category']);
    }

    if (!empty($params['category_id'])) {
      $select->where($pageTableName . '.category_id = ?', $params['category_id']);
    }

		if (!empty($params['subcategory'])) {
      $select->where($pageTableName . '.subcategory_id = ?', $params['subcategory']);
    }

    if (!empty($params['subcategory_id'])) {
      $select->where($pageTableName . '.subcategory_id = ?', $params['subcategory_id']);
    }

    if (!empty($params['subsubcategory'])) {
      $select->where($pageTableName . '.subsubcategory_id = ?', $params['subsubcategory']);
    }

    if (!empty($params['subsubcategory_id'])) {
      $select->where($pageTableName . '.subsubcategory_id = ?', $params['subsubcategory_id']);
    }
		if(empty($params['orderby'])) {
			$order = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.order', 1);
			switch ($order) {
				case "1":
					$select->order($offerTableName . '.creation_date DESC');
					break;
				case "2":
					$select->order($offerTableName . '.title');
					break;
				case "3":
					$select->order($offerTableName . '.hotoffer' . ' DESC');
					break;
				case "4":
					$select->order($pageTableName . '.package_id' . ' DESC');
					break;
				case "5":
					$select->order($offerTableName . '.hotoffer' . ' DESC');
					$select->order($pagePackageTableName . '.price' . ' DESC');
					break;
				case "6":
					$select->order($pagePackageTableName . '.price' . ' DESC');
					$select->order($offerTableName . '.hotoffer' . ' DESC');
					break;
			}
		}
  
  if ((isset($params['sitepage_location']) && !empty($params['sitepage_location'])) || (!empty($params['formatted_address']))) {
   $locationTable = Engine_Api::_()->getDbtable('locations', 'sitepage');
   $locationName = $locationTable->info('name');
   $enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.proximitysearch', 1);
   if (isset($params['locationmiles']) && (!empty($params['locationmiles']) && !empty($enable))) {
    $longitude = 0;
    $latitude = 0;
    $selectLocQuery = $locationTable->select()->where('location = ?', $params['sitepage_location']);
    $locationValue = $locationTable->fetchRow($selectLocQuery);

    //check for zip code in location search.
    if(empty($params['Latitude']) && empty($params['Longitude'])) {
     if (empty($locationValue)) {
              $locationResults = Engine_Api::_()->getApi('geoLocation', 'seaocore')->getLatLong(array('location' => $params['sitepage_location'], 'module' => 'Directory / Pages - Offers'));
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

    $radius = $params['locationmiles']; //in miles

    $flage = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.proximity.search.kilometer', 0);
    if (!empty($flage)) {
     $radius = $radius * (0.621371192);
    }
    //$latitudeRadians = deg2rad($latitude);
    
    $latitudeSin = "sin(radians($latitude))";
    $latitudeCos = "cos(radians($latitude))";
    $select->join($locationName, "$pageTableName.page_id = $locationName.page_id   ", null);
    $sqlstring = "((degrees(acos($latitudeSin * sin(radians($locationName.latitude)) + $latitudeCos * cos(radians($locationName.latitude)) * cos(radians($longitude - $locationName.longitude)))) * 69.172 <= " . "'" . $radius . "'";
    $sqlstring .= ") OR (" . $locationName . ".latitude = '" . $latitude . "' AND  " . $locationName . ".longitude= '" . $longitude . "'))";
    $select->where($sqlstring);
   } 
   else {
// 						if ($params['sitepage_postalcode'] == 'postalCode') { 
// 							$select->join($locationName, "$pageTableName.page_id = $locationName.page_id", null);
// 							$select->where("`{$locationName}`.formatted_address LIKE ? ", "%" . $params['formatted_address'] . "%");
// 						} 
// 						else {
     $select->join($locationName, "$pageTableName.page_id = $locationName.page_id", null);
     $select->where("`{$locationName}`.formatted_address LIKE ? or `{$locationName}`.location LIKE ? or `{$locationName}`.city LIKE ? or `{$locationName}`.state LIKE ?", "%" . urldecode($params['sitepage_location']) . "%");
    //}
   }
  } 
		if (isset($params['orderby']) && !empty($params['offer'])) {
        if($params['orderby'] == 'hotoffer') {
        $select->where($offerTableName . '.hotoffer = ?', '1');
        }
        elseif ($params['orderby'] == 'end_week') {
          $time_duration = date('Y-m-d H:i:s', strtotime('7 days'));
					$sqlTimeStr = ".end_time BETWEEN " . "'" . $current_time . "'" . " AND " . "'" . $time_duration . "'";
					$select = $select->where($offerTableName . "$sqlTimeStr");
					$select = $select
												->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime')");
        }
        elseif ($params['orderby'] == 'end_offer') {
					$select = $select
												->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time < '$currentTime')");
        }
        elseif ($params['orderby'] == 'end_month') {
          $time_duration = date('Y-m-d H:i:s', strtotime('1 months'));
					$sqlTimeStr = ".end_time BETWEEN " . "'" . $current_time . "'" . " AND " . "'" . $time_duration . "'";
					$select = $select->where($offerTableName . "$sqlTimeStr");
					$select = $select
												->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime')");
        }
        elseif ($params['orderby'] == 'sponsored offer') {
           //$select->where($offerTableName . '.paid = ?', '1');
           $select->where($pagePackageTableName . '.price != ?', '0.00');
           $select->order($pagePackageTableName . '.price' . ' DESC');
        }
        elseif ($params['orderby'] == 'view_count' || $params['offer'] == 'view') {
          $select->order($offerTableName . '.view_count' . ' DESC');
        }
        elseif ($params['orderby'] == 'comment_count' || $params['offer'] == 'comment') {
          $select->order($offerTableName . '.comment_count' . ' DESC');
        }
        elseif ($params['orderby'] == 'like_count' || $params['offer'] == 'like') {
          $select->order($offerTableName . '.like_count' . ' DESC');
        }
        elseif ($params['orderby'] == 'claimed' || $params['offer'] == 'popular') {
          $select->where($offerTableName . '.claimed != ?', '0');
          $select->order($offerTableName . '.claimed' . ' DESC');
        }
        else {
          $select->order($offerTableName . '.creation_date DESC');
        }
    }
    $select->order($offerTableName . '.creation_date DESC');
    if (!empty($params['title'])) {

       $select->where($pageTableName . ".title LIKE ? ", '%' . $params['title'] . '%');
    }


    if (!empty($params['search_offer'])) {

       $select->where($offerTableName . ".title LIKE ? ", '%' . $params['search_offer'] . '%');
    }

    $select = $select
                    ->where($pageTableName . '.closed = ?', '0')
                    ->where($pageTableName . '.approved = ?', '1')
                    ->where($pageTableName . '.search = ?', '1')
                    ->where($pageTableName . '.declined = ?', '0')
                    ->where($pageTableName . '.draft = ?', '1');

    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $select->where($pageTableName . '.expiration_date  > ?', date("Y-m-d H:i:s"));
    }
   if (isset($params['orderby']) && $params['orderby'] == 'Networks') {
          $select = $pageTable->getNetworkBaseSql($select, array('browse_network' => 1));

     }
   
    //Start Network work
  $select = $pageTable->getNetworkBaseSql($select, array('not_groupBy' => 1, 'extension_group' => $offerTableName . ".offer_id"));
    //End Network work
  
    if (!empty($viewer_id)) {
      // Convert times
      date_default_timezone_set($oldTz);
    }
  
    if(isset($params['offertype']) && $params['offertype'] = 'hotoffer') {
			if (isset($params['limit']) && !empty($params['limit'])) {
				if (!isset($params['start_index']))
					$params['start_index'] = 0;
				$select->limit($params['limit'], $params['start_index']);
			}
      return $this->fetchAll($select);
    }
    else {
			return Zend_Paginator::factory($select);
    }
  }

  public function getOfferList() {
    global $sitepageoffer_list;
    return $sitepageoffer_list;
  }

  /**
   * Get page offers list
   *
   * @param array $params
   * @param int $var
   * @param int $show_count
   * @return array $paginator;
   */
  public function getsitepageoffersPaginator($params = 0, $var = null, $show_count = null, $can_create_offer = null) {
    $paginator = Zend_Paginator::factory($this->getsitepageoffersSelect($params, $var, $show_count, $can_create_offer));
    if (!empty($params['page'])) {
      $paginator->setCurrentPageNumber($params['page']);
    }
    if (!empty($params['limit'])) {
      $paginator->setItemCountPerPage($params['limit']);
    }
    return $paginator;
  }

  /**
   * Get page offer select query
   *
   * @param array $params
   * @param int $var
   * @param int $show_count
   * @return string $select;
   */
  public function getsitepageoffersSelect($page_id = 0, $var, $show_count = null, $can_create_offer = null) {

    //OFFER TABLE NAME
    $offerTable = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');
    $offerTableName = $offerTable->info('name');

    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    
    //GET CURRENT TIME
    $currentTime = date("Y-m-d H:i:s");
    if (!empty($viewer_id)) {
      // Convert times
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
      $currentTime = date("Y-m-d H:i:s");
    }

    //QUERY MAKING
    if ($show_count) {
      $select = $offerTable->select()
                      ->from($offerTableName, array(
                          'COUNT(*) AS show_count'))
                      ->where($offerTableName . '.page_id = ?', $page_id);
    } else {
      $select = $offerTable->select()
                      ->from($offerTableName)
                      ->where($offerTableName . '.page_id = ?', $page_id)
                      ->order('sticky DESC')
                      ->order('creation_date DESC');
    }

    if (!empty($can_create_offer)) {
      $select = $offerTable->select()
                      ->from($offerTableName)
                      ->where($offerTableName . '.page_id = ?', $page_id)
                      ->order('sticky DESC')
                      ->order('creation_date DESC');
    } elseif ($var == 1) {
      $select = $select
                      ->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)");
    }
    if (!empty($viewer_id)) {
      // Convert times
      date_default_timezone_set($oldTz);
    }
    return $select;
  }

  public function topcreatorData($limit = null,$category_id) {

    //OFFER TABLE NAME
    $offerTableName = $this->info('name');

    //PAGE TABLE
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');

    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($pageTableName, array('photo_id', 'title as sitepage_title','page_id'))
                    ->join($offerTableName, "$pageTableName.page_id = $offerTableName.page_id", array('COUNT(engine4_sitepage_pages.page_id) AS item_count'))
                    ->where($pageTableName.'.approved = ?', '1')
										->where($pageTableName.'.declined = ?', '0')
										->where($pageTableName.'.draft = ?', '1')
                    ->group($offerTableName . ".page_id")
                    ->order('item_count DESC')
                    ->limit($limit);
    if (!empty($category_id)) {
      $select->where($pageTableName . '.category_id = ?', $category_id);
    }
    return $select->query()->fetchAll();
  }

  /**
   * Return offer count
   *
   * @param int $page_id
   * @return offer count
   */
  public function getPageOfferCount($page_id) {

    $selectOffer = $this->select()
                    ->from($this->info('name'), 'count(*) as count')
                    ->where('page_id = ?', $page_id);
    $data = $this->fetchRow($selectOffer);
    return $data->count;
  }

  /**
   * Return offer of the day
   *
   * @return Zend_Db_Table_Select
   */
  public function offerOfDay() {

     //GET LOGGED IN USER INFORMATION
    $db = $this->getAdapter();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    if (!empty($viewer_id)) {
      // Convert times
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
    }
  
    //CURRENT DATE TIME
    $date = date("Y-m-d H:i:s");

    //GET ITEM OF THE DAY TABLE NAME
    $offerOfTheDayTableName = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->info('name');

		//GET PAGE TABLE NAME
		$pageTableName = Engine_Api::_()->getDbtable('pages', 'sitepage')->info('name');

    //GET OFFER TABLE NAME
    $offerTableName = $this->info('name');

    //MAKE QUERY
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($offerTableName, array('offer_id', 'title', 'page_id', 'owner_id', 'description','photo_id','claimed','claim_count','end_settings','end_time'))
                    ->join($offerOfTheDayTableName, $offerTableName . '.offer_id = ' . $offerOfTheDayTableName . '.resource_id')
										->join($pageTableName, $offerTableName . '.page_id = ' . $pageTableName . '.page_id', array(''))
										->where($pageTableName.'.approved = ?', '1')
										->where($pageTableName.'.declined = ?', '0')
										->where($pageTableName.'.draft = ?', '1')
                    ->where('resource_type = ?', 'sitepageoffer_offer')
                    ->where('start_date <= ?', $date)
                    //->where('end_time >= ?', $date)
                    ->where('(' . $db->quoteInto('end_settings = ?', 0) . ') OR (' . $db->quoteInto('end_settings = ?', 1) . ' AND ' . $db->quoteInto('end_time >= ?', $date) . ')')
                    ->order('Rand()');

		//PAGE SHOULD BE AUTHORIZED
    if (Engine_Api::_()->sitepage()->hasPackageEnable())
      $select->where($pageTableName.'.expiration_date  > ?', date("Y-m-d H:i:s"));

		//PAGE SHOULD BE AUTHORIZED
    $stusShow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.status.show', 1);
    if ($stusShow == 0) {
      $select->where($pageTableName.'.closed = ?', '0');
    }

    if (!empty($viewer_id)) {
      // Convert times
      date_default_timezone_set($oldTz);
    }
    
    //RETURN RESULTS
    return $this->fetchRow($select);
  }

}
?>