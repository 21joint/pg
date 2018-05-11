<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Ratings.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Model_DbTable_Ratings extends Engine_Db_Table {

  protected $_rowClass = 'Sitepagereview_Model_Rating';

	/**
   * Return review ratings data
   *
   * @param Int review_id
   * @return Zend_Db_Table_Select
   */
	public function ratingsData($review_id) {
    $select = $this->select()
									->from($this->info('name'), array('reviewcat_id', 'rating'))
									->where("review_id = ?", $review_id);
    return $this->fetchAll($select)->toArray();
	}

  /**
   * Returns a rating datas according to page_id
   *
   * @param Int page_id
   * @return Zend_Db_Table_Select
   */
  public function ratingbyCategory($page_id) {

		//RETURN IF PAGE ID IS EMPTY
    if (empty($page_id)) {
      return;
    }

    $tableRatingName = $this->info('name');
    $tableCategory = Engine_Api::_()->getDbtable('reviewcats', 'sitepagereview');
    $tableCategoryName = $tableCategory->info('name');
    $select = $this
                    ->select()
                    ->setIntegrityCheck(false)
                    ->from($tableRatingName, array('AVG(rating) AS avg_rating'))
                    ->joinLeft($tableCategoryName, "$tableRatingName.reviewcat_id = $tableCategoryName.reviewcat_id", array('reviewcat_name'))
                    ->where($tableRatingName . ".rating != ?", 0)
                    ->where("page_id = ?", $page_id)
                    ->group($tableRatingName.'.reviewcat_id');
    return $this->fetchAll($select)->toArray();

  }

  /**
   * Returns a rating datas according to review_id
   *
   * @param Int review_id
   * @return Zend_Db_Table_Select
   */
  public function profileRatingbyCategory($review_id) {

		//RETURN IF REVIEW ID IS EMPTY
    if (empty($review_id)) {
      return;
    }

		//GET RATING TABLE NAME
    $tableRatingName = $this->info('name');

		//GET REVIEW PARAMETER TABLE INFO
    $tableCategory = Engine_Api::_()->getDbtable('reviewcats', 'sitepagereview');
    $tableCategoryName = $tableCategory->info('name');

		//MAKE QUERY
    $select = $this
                    ->select()
                    ->setIntegrityCheck(false)
                    ->from($tableRatingName, array('rating'))
                    ->joinLeft($tableCategoryName, "$tableRatingName.reviewcat_id = $tableCategoryName.reviewcat_id", array('reviewcat_name'))
                    ->where("review_id = ?", $review_id);

		//RETURN RESULTS
    return $this->fetchAll($select)->toArray();
  }

  /**
   * If page category is updated than update review and rating entries
   *
   * @param Int page_id, pre_cat_id, curr_cat_id
   */
  public function editPageCategory($page_id, $pre_cat_id, $curr_cat_id) {

		//DELETE ENTRIES BELONGS TO THIS PAGE ID 
		$this->delete(array('reviewcat_id != ?' => 0, 'page_id = ?' => $page_id));
 
		//JUST UPDATE CATEGORY ID
		$this->update(array('category_id' => $curr_cat_id), array('category_id = ?' => $pre_cat_id, 'page_id = ?' => $page_id, 'reviewcat_id = ?' => 0));
  }

  /**
   * Update overall page rating
   *
   * @param Int page_id
   */
  public function pageRatingUpdate($page_id) {

		//RETURN IF PAGE ID IS EMPTY
    if (empty($page_id)) {
      return;
    }

    //UPDATE PAGE RATING AVERAGE IN PAGE TABLE
    $avg_rating = $this
                    ->select()
                    ->from($this->info('name'), array('AVG(rating) AS avg_rating'))
                    ->where("reviewcat_id = ?", 0)
                    ->where("page_id = ?", $page_id)
                    ->group('page_id')
										->query()
                    ->fetchColumn();

   // if (!empty($avg_rating)) {
		$sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
		$sitepage->rating = round($avg_rating, 4);
		$sitepage->save();
   // }
  }

  /**
   * Return rating corrosponding to page_id and review_id
   *
   * @param Int page_id, review_id
   */
	public function getRating($page_id, $review_id) {

		//FETCH DATA
    $rating = $this->select()
                    ->from($this->info('name'), 'rating')
                    ->where('page_id = ?', $page_id)
                    ->where('review_id = ?', $review_id)
                    ->where('reviewcat_id = ?', 0)
										->query()
                    ->fetchColumn();
	
		//RETURN DATA
    return $rating;
	}
}
?>