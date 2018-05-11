<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Blockusers.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_DbTable_Blockusers extends Engine_Db_Table
{
  protected $_name = 'feedback_blockusers';
  protected $_rowClass = 'Feedback_Model_Blockuser';

	/**
   * Return count of block users
   *
   * @param int block_user_id
   * @return count of block users
   */
	public function countBlockUser($block_user_id) {
	  	
		//FETCH DATA
		$count = 0;
		$count = $this->select()
                     ->from($this->info('name'), array('COUNT(*) AS count'))
    				 				 ->where('blockuser_id = ?', $block_user_id)
										 ->query()
										 ->fetchColumn();

		//RETURN DATA
		return $count;
	}
}
