<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    sitepageevent
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Album.php 6590 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Model_Album extends Core_Model_Item_Collection {

  protected $_parent_type = 'sitepageoffer_offer';
  protected $_owner_type = 'sitepageoffer_offer';
  protected $_children_types = array('sitepageoffer_photo');
  protected $_collectible_type = 'sitepageoffer_photo';

	/**
   * Get authorization
   *
   * @return authorization value
   */
  public function getAuthorizationItem() {
    return $this->getParent('sitepageoffer_offer');
  }

	/**
   * Delete child things
   *
   */
  protected function _delete() {
    
    $photoTable = Engine_Api::_()->getItemTable('sitepageoffer_photo');
    $photoSelect = $photoTable->select()->where('album_id = ?', $this->getIdentity());
    foreach ($photoTable->fetchAll($photoSelect) as $sitepageofferPhoto) {
      $sitepageofferPhoto->delete();
    }
    parent::_delete();
  }

}
?>