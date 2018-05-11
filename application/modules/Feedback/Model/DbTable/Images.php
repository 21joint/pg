<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Images.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_DbTable_Images extends Engine_Db_Table
{
  protected $_rowClass = 'Feedback_Model_Image';

	/**
   * Return images data
   *
   * @param int feedback_id
   * @return images data corrosponding to feedback_id
   */
	public function getFeedbackImages($feedback_id) {

		//MAKE QUERY
		$selectImage = $this->select()
														   ->from($this->info('name'), array('image_id','user_id', 'album_id', 'file_id'))
														   ->where('feedback_id = ?', $feedback_id);
		//FETCH AND RETURN DATA
		return $this->fetchAll($selectImage);
	}
}
