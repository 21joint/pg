<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    sitepageevent
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Photo.php 6590 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Model_Photo extends Core_Model_Item_Collectible {

  protected $_parent_type = 'sitepageoffer_album';
  protected $_owner_type = 'user';
  protected $_collection_type = 'sitepageoffer_album';

	public function getMediaType() {
		return 'photo';
	}
	
  /**
   * Get photo url
   *
   * @param string $type
   * @return photo url
   */
  public function getPhotoUrl($type = null) {
    if (empty($this->file_id)) {
      return null;
    }

		$file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id, $type);
    if (!$file) {
      return null;
    }

    return $file->map();
  }

}
?>