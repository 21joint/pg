<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Api_Core extends Core_Api_Abstract {

  /**
   * Delete all rating datas belongs to category_id
   *
   * @param Int category_id
   */
  public function deleteCategory($category_id) {

		//RETURN IF CATEGORY ID IS EMPTY
    if (empty($category_id)) {
      return;
    }

    //DELETE ALL REVIEW CATEGORIES BELONGS TO THIS CATEGORY ID
    $tableCategory = Engine_Api::_()->getDbtable('reviewcats', 'sitepagereview');
    $tableCategory->delete(array('category_id = ?' => $category_id));

    //FIRST SAVE PAGE ID'S CORROSPONDING TO CATEGORY ID FOR UPDATION AFTER DELETE FROM RATING TABLE
    $tableRating = Engine_Api::_()->getDbtable('ratings', 'sitepagereview');

    $tableRating->delete(array('reviewcat_id != ?' => 0, 'category_id = ?' => $category_id));

    $tableRating->update(array('category_id' => 0), array('category_id = ?' => $category_id));
  }

  /**
   * Delete the sitepagereview and ratings
   * 
   * @param int $review_id
   */
  public function deleteContent($review_id) {

		//GET THE SITEPAGENOTE ITEM
    $sitepagereview = Engine_Api::_()->getItem('sitepagereview_review', $review_id);

		//RETRUN IF REVIEW OBJECT IS EMPTY
		if(empty($sitepagereview)) {
			return;
		}

		//DELETE RATING ENTRIES
		$ratingTable = Engine_Api::_()->getDbtable('ratings', 'sitepagereview');
		$ratingTable->delete(array('review_id =?' => $sitepagereview->review_id));

		//DELETE REVIEW OF THE DAY ENTRIES
		Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->delete(array('resource_id =?' => $sitepagereview->review_id, 'resource_type' => 'sitepagereview_review'));

		//UPDATE RATING IN PAGE TABLE
		$ratingTable->pageRatingUpdate($sitepagereview->page_id);

		//UPDATE review_count IN PAGE TABLE
		$pageTable = Engine_Api::_()->getItemTable('sitepage_page');
		$sitepage = $pageTable->fetchRow(array('page_id = ?' => $sitepagereview->page_id));
		$sitepage->review_count--;
		$sitepage->save();

		$sitepagereview->delete();
	}

  public function getRecommendProfile() {
    global $sitepagereview_profileType;
    return $sitepagereview_profileType;
  }

  /**
   * If review category is deleted than update review and rating entries
   *
   * @param Int reviewcat_id
   */
  public function deleteReviewCategory($reviewcat_id) {

		//RETURN IF REVIEW CATEGORY ID IS EMPTY
    if (empty($reviewcat_id)) {
      return;
    }

    //DELETE ENTRIES FROM RATING TABLE CORROSPONDING TO REVIEW CATEGORY ID
    Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->delete(array('reviewcat_id = ?' => $reviewcat_id));
  }

  /**
   * Truncation of text
   * @params string $text
   * @params int $limit
   * @return truncate text
   */
  public function truncateText($text, $limit) {
    $tmpBody = strip_tags($text);
    return ( Engine_String::strlen($tmpBody) > $limit ? Engine_String::substr($tmpBody, 0, $limit) . '..' : $tmpBody );
  }

  /**
   * Show rating stars and rectangles
   *
   * @param float rating
   * @param string image_type
   * @return Zend_Db_Table_Select
   */
  public function showRatingImage($rating = 0, $image_type = 'star') {

    if ($image_type == 'star') {
      switch ($rating) {
        case 0:
          $rating_value = '';
          break;
        case $rating < .5:
          $rating_value = '';
          $rating_valueTitle = 0;
          break;
        case $rating < 1:
          $rating_value = 'halfstar';
          $rating_valueTitle = .5;
          break;
        case $rating < 1.5:
          $rating_value = 'onestar';
          $rating_valueTitle = 1;
          break;
        case $rating < 2:
          $rating_value = 'onehalfstar';
          $rating_valueTitle = 1.5;
          break;
        case $rating < 2.5:
          $rating_value = 'twostar';
          $rating_valueTitle = 2;
          break;
        case $rating < 3:
          $rating_value = 'twohalfstar';
          $rating_valueTitle = 2.5;
          break;
        case $rating < 3.5:
          $rating_value = 'threestar';
          $rating_valueTitle = 3;
          break;
        case $rating < 4:
          $rating_value = 'threehalfstar';
          $rating_valueTitle = 3.5;
          break;
        case $rating < 4.5:
          $rating_value = 'fourstar';
          $rating_valueTitle = 4;
          break;
        case $rating < 5:
          $rating_value = 'fourhalfstar';
          $rating_valueTitle = 4.5;
          break;
        case $rating >= 5:
          $rating_value = 'fivestar';
          $rating_valueTitle = 5;
          break;
      }

      $showRatingImage = array();
      $showRatingImage['rating_value'] = $rating_value;
      $showRatingImage['rating_valueTitle'] = $rating_valueTitle;
      return $showRatingImage;
    } else {
      switch ($rating) {
        case 0:
          $rating_value = '';
          break;
        case $rating < .5:
          $rating_value = '';
          break;
        case $rating < 1:
          $rating_value = 'halfstar-small-box';
          break;
        case $rating < 1.5:
          $rating_value = 'onestar-small-box';
          break;
        case $rating < 2:
          $rating_value = 'onehalfstar-small-box';
          break;
        case $rating < 2.5:
          $rating_value = 'twostar-small-box';
          break;
        case $rating < 3:
          $rating_value = 'twohalfstar-small-box';
          break;
        case $rating < 3.5:
          $rating_value = 'threestar-small-box';
          break;
        case $rating < 4:
          $rating_value = 'threehalfstar-small-box';
          break;
        case $rating < 4.5:
          $rating_value = 'fourstar-small-box';
          break;
        case $rating < 5:
          $rating_value = 'fourhalfstar-small-box';
          break;
        case $rating >= 5:
          $rating_value = 'fivestar-small-box';
          break;
      }

      $showRatingImage = array();
      $showRatingImage['rating_value'] = $rating_value;
      return $showRatingImage;
    }
  }

	/**
   * Return array for prefield star's and rectangles
   *
   * @param array $post_data
   * @return Zend_Db_Table_Select
   */
	public function prefieldRatingData($post_data) {
		
		//SHOW PRE-FIELD THE RATINGS IF OVERALL RATING IS EMPTY
		$reviewRateData = array();
		foreach ($post_data as $key => $ratingdata) {
			$string_exist = strstr($key, 'review_rate_');
			if ($string_exist) {
				$reviewcat_id = explode('review_rate_', $key);
				$reviewRateData[$reviewcat_id[1]]['reviewcat_id'] = $reviewcat_id[1];
				$reviewRateData[$reviewcat_id[1]]['rating'] = $ratingdata;
			}
		}

		return $reviewRateData;
	}

	/**
   * Returns: admin can't review and rate the page
   *
   * @param Int page_id
	 * @param Int viewer_id
   * @return Zend_Db_Table_Select
   */
	public function adminCantReview($page_id, $viewer_id) {

		//GET MANAGE ADMIN TABLE
		$manageadminTable = Engine_Api::_()->getDbtable('manageadmins', 'sitepage');

		//FETCH DATA
		$manageadmin_id = 0;
    $manageadmin_id = $manageadminTable->select()
                    ->from($manageadminTable->info('name'), 'manageadmin_id')
										->where('page_id = ?', $page_id)
                    ->where('user_id = ?', $viewer_id)
                    ->query()
										->fetchColumn();

		//RETURN DATA
    return $manageadmin_id;
	}

	public function getLinkedPage($review_id) {

		//GET REVIEW TABLE
		$reviewTable = Engine_Api::_()->getDbtable('reviews', 'sitepagereview');
		$reviewTableName = $reviewTable->info('name');
		
		//GET PAGE TABLE
		$pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
		$pageTableName = $pageTable->info('name');

		$select = $pageTable->select()
												->setIntegrityCheck(false)
												->from($pageTableName, array('page_id', 'title', 'photo_id', 'page_url', 'owner_id', 'rating'))
												->join($reviewTableName, "$pageTableName.page_id = $reviewTableName.page_id", array('review_id'))
												->where('review_id = ?', $review_id)
												->limit(1);
		return $pageTable->fetchRow($select);
	}

  public function setReviewPackages() {
    $check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.isvar');
    $base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.basetime');
    $filePath = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.filepath');
    $currentbase_time = time();
    $word_name = strrev('lruc');
    $file_path = APPLICATION_PATH . '/application/modules/' . $filePath;

    if (($currentbase_time - $base_result_time > 5356800) && empty($check_result_show)) {
      $is_file_exist = file_exists($file_path);
      if (!empty($is_file_exist)) {
        $fp = fopen($file_path, "r");
        while (!feof($fp)) {
          $get_file_content .= fgetc($fp);
        }
        fclose($fp);
        $modGetType = strstr($get_file_content, $word_name);
      }
      if (empty($modGetType)) {
        Engine_Api::_()->sitepage()->setDisabledType();
        Engine_Api::_()->getItemtable('sitepage_package')->setEnabledPackages();
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagereview.set.type', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagereview.view.info', 1);
      } else {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagereview.isvar', 1);
      }
    }
  }
}
?>