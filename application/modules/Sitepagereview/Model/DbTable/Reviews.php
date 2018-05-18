<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Reviews.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Model_DbTable_Reviews extends Engine_Db_Table {

  protected $_rowClass = "Sitepagereview_Model_Review";

  /**
   * Return review data for checking that viewer has been posted a review or not
   *
   * @param Int page_id
   * @param Int viewer_id
   * @return Zend_Db_Table_Select
   */
  public function canPostReview($page_id, $viewer_id) {

    //MAKE QUERY
    $hasPosted = $this->select()
                    ->from($this->info('name'), array('review_id'))
                    ->where('page_id = ?', $page_id)
                    ->where('owner_id = ?', $viewer_id)
                    ->query()
                    ->fetchColumn();

    //RETURN RESULTS
    return $hasPosted;
  }

  /**
   * Return average recommendetion for page reviews
   *
   * @param Int page_id
   * @return Zend_Db_Table_Select
   */
  public function getAvgRecommendation($page_id) {

    //MAKE QUERY
    $select = $this->select()
                    ->from($this->info('name'), array('*', 'AVG(recommend) AS avg_recommend'))
                    ->where('page_id = ?', $page_id)
                    ->group('page_id');

    //RETURN RESULTS
    return $this->fetchAll($select);
  }

  /**
   * Return total reviews for page
   *
   * @param Int page_id
   * @return Zend_Db_Table_Select
   */
  public function totalReviews($page_id) {

    //MAKE QUERY
    $totalReviews = $this->select()
                    ->from($this->info('name'), array('COUNT(*) AS count'))
                    ->where('page_id = ?', $page_id)
                    ->query()
                    ->fetchColumn();

    //RETURN RESULTS
    return $totalReviews;
  }

  /**
   * Return page reviews
   *
   * @param Int page_id
   * @return Zend_Db_Table_Select
   */
  public function pageReviews($page_id) {

    //MAKE QUERY
    $select = $this->select()
										->where('page_id = ?', $page_id)
										->order('modified_date DESC');
    //RETURN RESULTS
    return Zend_Paginator::factory($select);
  }

  /**
   * Get reviews to add as item of the day
   * @param string $title : search text
   * @param int $limit : result limit
   */
  public function getDayItems($search_text, $limit=10) {

    //GET PAGE TABLE NAME
    $tablePageName = Engine_Api::_()->getDbTable('pages', 'sitepage')->info('name');

    //GET ITEM TABLE NAME
    $itemTableName = $this->info('name');

    //MAKE QUERY
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($itemTableName, array('review_id', 'title', 'owner_id', 'page_id'))
                    ->joinLeft($tablePageName, "$tablePageName.page_id = $itemTableName.page_id", array(''))
                    ->where($tablePageName . '.closed = ?', '0')
                    ->where($tablePageName . '.declined = ?', '0')
                    ->where($tablePageName . '.approved = ?', '1')
                    ->where($tablePageName . '.draft = ?', '1')
                    ->where($itemTableName . '.title  LIKE ? ', '%' . $search_text . '%')
                    ->order($itemTableName . '.title ASC')
                    ->limit($limit);

    //RETURN DATA
    return $this->fetchAll($select);
  }

  /**
     * Return paginator
     * @param Array $params
     * @param Array $customParams
     * @return paginator
     */
    public function getReviewsPaginator($params = array(), $customParams = null) {

        $paginator = Zend_Paginator::factory($this->reviewRatingData($params, $customParams));
        if (!empty($params['page'])) {
            $paginator->setCurrentPageNumber($params['page']);
        }

        if (!empty($params['limit'])) {
            $paginator->setItemCountPerPage($params['limit']);
        }

        return $paginator;
    }
  
  /**
   * Return page reviews
   *
   * @param array params
   * @return Zend_Db_Table_Select
   */
  public function reviewRatingData($params = array(),$widgetType = null) {

    //GET PAGE TABLE NAME
    $tablePage = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $tablePageName = $tablePage->info('name');

    //GET RATING TABLE NAME
    $tableRatingName = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->info('name');

    //GET REVIEW TABLE NAME
    $tableReviewName = $this->info('name');

    //MAKE QUERY
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($tableReviewName)
                    ->joinLeft($tableRatingName, "$tableRatingName.review_id = $tableReviewName.review_id", array('rating'));


    if (isset($params['page_id']) && !empty($params['page_id'])) {
      $select = $select->where($tableReviewName . '.page_id = ?', $params['page_id']);
    }

    if (isset($params['zero_count']) && !empty($params['zero_count'])) {
      $select = $select->where($tableReviewName . '.' . $params['zero_count'] . '!= ?', 0);
    }

    if (isset($params['orderby']) && !empty($params['orderby'])) {
      $select = $select->order($tableReviewName . '.' . $params['orderby']);
    }

    if (isset($params['limit']) && !empty($params['limit'])) {
      if (!isset($params['start_index']))
        $params['start_index'] = 0;
      $select->limit($params['limit'], $params['start_index']);
    }

    if (isset($params['page_validation']) && !empty($params['page_validation'])) {

      $select = $select->joinLeft($tablePageName, "$tablePageName.page_id = $tableReviewName.page_id", array('page_id', 'title AS page_title', 'photo_id'))
                      ->where($tablePageName . '.search = ?', '1')
                      ->where($tablePageName . '.closed = ?', '0')
                      ->where($tablePageName . '.approved = ?', '1')
                      ->where($tablePageName . '.declined = ?', '0')
                      ->where($tablePageName . '.draft = ?', '1');
      if (!empty($params['category_id'])) {
      $select->where($tablePageName . '.category_id = ?', $params['category_id']);
			}
      if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
        $select->where($tablePageName . '.expiration_date  > ?', date("Y-m-d H:i:s"));
      }
    }
    
    if($widgetType == 'browsereview') {
      if (!empty($params['title'])) {
				$select->where($tablePageName . ".title LIKE ? ", '%' . $params['title'] . '%');
			}

      if (!empty($params['search_review'])) {
				$select->where($tableReviewName . ".title LIKE ? ", '%' . $params['search_review'] . '%');
      }
     
      if ((isset($params['orderby_browse']) && $params['orderby_browse'] == 'view_count') || !empty($params['viewedreview'])) {
			$select = $select
											->order($tableReviewName .'.view_count DESC')
											->order($tableReviewName .'.creation_date DESC');
			} elseif ((isset($params['orderby_browse']) && $params['orderby_browse'] == 'comment_count') || !empty($params['commentedreview'])) {
				$select = $select
												->order($tableReviewName .'.comment_count DESC')
												->order($tableReviewName .'.creation_date DESC');
			} elseif ((isset($params['orderby_browse']) && $params['orderby_browse'] == 'rating')) {
				$select = $select
												->order($tableRatingName .'.rating DESC')
												->order($tableReviewName .'.creation_date DESC');
			} elseif ((isset($params['orderby_browse']) && $params['orderby_browse'] == 'like_count') || !empty($params['likedreview'])) {
				$select = $select
												->order($tableReviewName .'.like_count DESC')
												->order($tableReviewName .'.creation_date DESC');
			}

			if (!empty($params['category_id'])) {
				$select->where($tablePageName . '.category_id = ?', $params['category_id']);
			}

			if (!empty($params['subcategory'])) {
				$select->where($tablePageName . '.subcategory_id = ?', $params['subcategory']);
			}

			if (!empty($params['subcategory_id'])) {
				$select->where($tablePageName . '.subcategory_id = ?', $params['subcategory_id']);
			}

			if (!empty($params['subsubcategory'])) {
				$select->where($tablePageName . '.subsubcategory_id = ?', $params['subsubcategory']);
			}

			if (!empty($params['subsubcategory_id'])) {
				$select->where($tablePageName . '.subsubcategory_id = ?', $params['subsubcategory_id']);
			}
     
      if((isset($params['show']) && $params['show'] == 'featured')) {
				$select = $select
												->where($tableReviewName . '.featured = ?', 1)
												->order($tableReviewName .'.creation_date DESC');
			}
      elseif((isset($params['show']) && $params['show'] == 'my_friend_review')) {
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $membership_table = Engine_Api::_()->getDbtable( 'membership' , 'user' ) ;
				$member_name = $membership_table->info( 'name' ) ;
        $select->joinInner( $member_name , "$member_name . resource_id = $tableReviewName . owner_id" , NULL )
								->where( $member_name . '.user_id = ?' , $viewer_id )
								->where( $member_name . '.active = ?' , 1 );
      }
      elseif (isset($params['show']) && $params['show'] == 'Networks') {
				$select = $tablePage->getNetworkBaseSql($select, array('browse_network' => 1));
			}
			elseif (isset($params['show']) && $params['show'] == 'my_like') {
				$likeTableName = Engine_Api::_()->getDbtable('likes', 'core')->info('name');
				$viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
				$select
              ->join($likeTableName, "$likeTableName.resource_id = $tablePageName.page_id")
							->where($likeTableName . '.poster_type = ?', 'user')
							->where($likeTableName . '.poster_id = ?', $viewer_id)
              ->where($likeTableName . '.resource_type = ?', 'sitepage_page');
			}
      
      if(empty($params['orderby_browse'])) {
				$order = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.order', 1);
				switch ($order) {
					case "1":
						$select->order($tableReviewName . '.creation_date DESC');
						break;
					case "2":
						$select->order($tableReviewName . '.title');
						break;
					case "3":
						$select->order($tableReviewName . '.featured' . ' DESC');
						break;
				}
      }
    }
    
    $select = $select->order($tableReviewName . '.review_id DESC')->where($tableRatingName . '.reviewcat_id = ?', 0);

    //Start Network work
    if (!isset($params['page_id']) || empty($params['page_id'])) {
     $select = $tablePage->getNetworkBaseSql($select, array('not_groupBy' => 1, 'extension_group' => $tableReviewName . ".review_id"));
    }
    //End Network work
    //RETURN RESULTS
    if ((isset($params['featured']) && !empty($params['featured'])) || ($widgetType == 'browsereview')) {
      if($widgetType != 'browsereview') {
				$select = $select->where($tableReviewName . '.featured = ?', 1);
      }
      if($widgetType == 'featuredcarousel') {
        return $this->fetchAll($select);
      }
      else {
      return Zend_Paginator::factory($select);
      }
    } else {
      return $this->fetchAll($select);
    }
  }

  /**
   * Return review of the day
   *
   * @return Zend_Db_Table_Select
   */
  public function reviewOfDay() {

    //CURRENT DATE TIME
    $date = date('Y-m-d');

    //GET ITEM OF THE DAY TABLE NAME
    $reviewOfTheDayTableName = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->info('name');

		//GET PAGE TABLE NAME
		$pageTableName = Engine_Api::_()->getDbtable('pages', 'sitepage')->info('name');

    //GET REVIEW TABLE NAME
    $reviewTableName = $this->info('name');

    //MAKE QUERY
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($reviewTableName, array('review_id', 'title', 'page_id', 'owner_id', 'body'))
                    ->join($reviewOfTheDayTableName, $reviewTableName . '.review_id = ' . $reviewOfTheDayTableName . '.resource_id')
										->join($pageTableName, $reviewTableName . '.page_id = ' . $pageTableName . '.page_id', array(''))
										->where($pageTableName.'.approved = ?', '1')
										->where($pageTableName.'.declined = ?', '0')
										->where($pageTableName.'.draft = ?', '1')
                    ->where('resource_type = ?', 'sitepagereview_review')
                    ->where('start_date <= ?', $date)
                    ->where('end_date >= ?', $date)
                    ->order('Rand()');

		//PAGE SHOULD BE AUTHORIZED
    if (Engine_Api::_()->sitepage()->hasPackageEnable())
      $select->where($pageTableName.'.expiration_date  > ?', date("Y-m-d H:i:s"));

		//PAGE SHOULD BE AUTHORIZED
    $stusShow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.status.show', 1);
    if ($stusShow == 0) {
      $select->where($pageTableName.'.closed = ?', '0');
    }

    //RETURN RESULTS
    return $this->fetchRow($select);
  }

  /**
   * Return top reviewers
   *
   * @param int itemCount
   * @return Zend_Db_Table_Select
   */
  public function topReviewers($itemCount,$category_id) {

    //GET USER TABLE INFO
    $tableUser = Engine_Api::_()->getDbtable('users', 'user');
    $tableUserName = $tableUser->info('name');

    //PAGE TABLE
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');

    //GET REVIEW TABLE NAME
    $tableReviewName = $this->info('name');

    //MAKE QUERY
    $select = $tableUser->select()
                    ->setIntegrityCheck(false)
                    ->from($tableUserName, array('user_id', 'username', 'displayname', 'photo_id'))
                    ->join($tableReviewName, "$tableUserName.user_id = $tableReviewName.owner_id", array('COUNT(engine4_sitepagereview_reviews.review_id) AS review_count', 'MAX(engine4_sitepagereview_reviews.review_id) as max_review_id'));
    if(!empty($category_id)) {
			$select ->join($pageTableName, "$tableReviewName.page_id = $pageTableName.page_id", array())
							->where($pageTableName . '.category_id = ?', $category_id);
    }
    $select->group($tableUserName . ".user_id")
						->order('review_count DESC')
						->order('user_id DESC')
						->limit($itemCount);

    //RETURN THE RESULTS
    return $tableUser->fetchAll($select);
  }
 
  public function topcreatorData($limit = null,$category_id) {

    //REVIEW TABLE NAME
    $reviewTableName = $this->info('name');

    //PAGE TABLE
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');

    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($pageTableName, array('photo_id', 'title as sitepage_title','page_id'))
                    ->join($reviewTableName, "$pageTableName.page_id = $reviewTableName.page_id", array('COUNT(engine4_sitepage_pages.page_id) AS item_count'))
                    ->where($pageTableName.'.approved = ?', '1')
										->where($pageTableName.'.declined = ?', '0')
										->where($pageTableName.'.draft = ?', '1')
                    ->group($reviewTableName . ".page_id")
                    ->order('item_count DESC')
                    ->limit($limit);
    if (!empty($category_id)) {
      $select->where($pageTableName . '.category_id = ?', $category_id);
    }
    return $select->query()->fetchAll();
  }

}
?>
