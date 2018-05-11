<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Votes.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_DbTable_Votes extends Engine_Db_Table
{
  protected $_name = 'feedback_votes';
  protected $_rowClass = 'Feedback_Model_Vote';

	/**
   * Return count of votes
   *
   * @param int feedback_id
   * @return count of votes for feedback_id
   */
	public function countFeedbackVote($feedback_id) {

		//FETCH DATA
		$count = 0;
		$count = $this->select()
											->from($this->info('name'), array('COUNT(*) AS count'))
											->where('feedback_id = ?', $feedback_id)
											->query()
                      ->fetchColumn();
		
		//RETURN DATA
		return $count;
	}

	/**
   * Return vote_id
   *
   * @param int feedback_id
   * @return vote_id corrosponding to feedback_id and viewer_id
   */
  public function getFeedbackVoteId($viewer_id, $feedback_id) {

		//FETCH DATA
		$vote_id = 0;
		$vote_id = $this->select()
								->from($this->info('name'), array('vote_id'))
								->where('voter_id = ?', $viewer_id)
								->where('feedback_id = ?', $feedback_id)
								->query()
								->fetchColumn();

		//RETURN DATA
		return $vote_id;
  }
}
