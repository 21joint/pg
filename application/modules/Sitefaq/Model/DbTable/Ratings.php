<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Ratings.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Model_DbTable_Ratings extends Engine_Db_Table
{
  protected $_rowClass = "Sitefaq_Model_Rating";

  /**
   * Return rating data
   *
   * @param array params
   * @return Zend_Db_Table_Select
   */
  public function getAvgRating($faq_id) {

    //FETCH DATA
    $avg_rating = $this->select()
                    ->from($this->info('name'), array('AVG(rating) AS avg_rating'))
                    ->where('faq_id = ?', $faq_id)
                    ->group('faq_id')
                    ->query()
                    ->fetchColumn();

    //RETURN DATA
    return $avg_rating;
  }

	 /**
   * Do sitefaq rating
   * @param int $faq_id : sitefaq id
	 * @param int $user_id : user id
	 * @param int $rating : $rating id
   */
  public function setFaqRating($faq_id, $user_id, $rating) {

    //FETCH DATA
    $done_rating = $this->select()
                    ->from($this->info('name'), array('faq_id'))
                    ->where('faq_id = ?', $faq_id)
                    ->where('user_id = ?', $user_id)
                    ->query()
                    ->fetchColumn();

		//INSERT RATING ENTRIES IN TABLE
    if (empty($done_rating)) {
      $this->insert(array(
          'faq_id' => $faq_id,
          'user_id' => $user_id,
          'rating' => $rating
      ));
    }
  }

	/**
   * Get previous rated or not by user
   * @param int $faq_id : sitefaq id
	 * @param int $user_id : user id
   */
  public function isRated($faq_id, $user_id) {

		//FETCH DATA
    $done_rating = $this->select()
                    ->from($this->info('name'), array('faq_id'))
                    ->where('faq_id = ?', $faq_id)
                    ->where('user_id = ?', $user_id)
                    ->query()
										->fetchColumn();

		//RETURN DATA
    if (!empty($done_rating))
      return true;
    
		return false;
  }
  
	/**
   * Get total rating
   * @param int $faq_id : sitefaq id
	 * @return  total rating
   */
  public function countRating($faq_id) {

    //FETCH DATA
    $total_count = $this->select()
                    ->from($this->info('name'), array('COUNT(faq_id) AS total_count'))
                    ->where('faq_id = ?', $faq_id)
                    ->query()
                    ->fetchColumn();

    //RETURN DATA
    return $total_count;
  }

}