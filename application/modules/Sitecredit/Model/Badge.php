<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Badge.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Model_Badge extends Core_Model_Item_Abstract
{
 public function getPhotoUrl($type = null)
 {
  $photo_id = $this->file_id;
  if( !$photo_id ) {
    return null;
  }

  $file = Engine_Api::_()->getItemTable('storage_file')->getFile($photo_id, $type);
  if( !$file ) {
    return null;
  }

  return $file->map();
}

}