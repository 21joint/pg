<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Category.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_Category extends Core_Model_Item_Abstract
{

  /**
   * Get total participants
   *
   * @param int $feedback_id
   * @return total participants
   */
	public function getUsedCount(){

		//GET FEEDBACK TABLE
		$feedbackTable = Engine_Api::_()->getDbTable('feedbacks', 'feedback');

		//FETCH DATA
		$total_count = 0;
    $total_count = $feedbackTable->select()
                    ->from($feedbackTable->info('name'), array('COUNT(feedback_id) AS total_count'))
										->where('category_id = ?', $this->category_id)
										->query()
                    ->fetchColumn();

		//RETURN DATA
		return $total_count;
  }
}
