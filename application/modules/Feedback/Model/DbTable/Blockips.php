<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Blockips.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_DbTable_Blockips extends Engine_Db_Table
{
  protected $_rowClass = 'Feedback_Model_Blockip';

	/**
   * Return block ip address
   *
   * @param int ip
   * @return block ip address corrosponding to ip
   */
	public function blockipAdded($ip) {

		//FETCH DATA
		$blockip_address = 0;
		$blockip_address = $this->select()
													->from($this->info('name'), 'blockip_address')
													->where('blockip_address = ?', $ip)
                          ->query()
													->fetchColumn();

		//RETURN DATA
		return $blockip_address;
	}
   
}
