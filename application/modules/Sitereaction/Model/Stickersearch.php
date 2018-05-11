<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Stickersearch.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_Model_Stickersearch extends Core_Model_Item_Abstract {
  protected $_searchTriggers = false;

  /**
   * Gets a url to the current photo representing this item. Return null if none
   * set
   *
   * @param string The photo type (null -> main, thumb, icon, etc);
   * @return string The photo url
   */
  public function getPhotoUrl($type = null) {
    $photo_id = $this->file_id;
    if (!$photo_id) {
      return null;
    }

    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($photo_id, $type);
    if (!$file) {
      return null;
    }

    return $file->map();
  }

  public function setIcon($searchIcon) {
    if ($searchIcon instanceof Zend_Form_Element_File) {
      $file = $searchIcon->getFileName();
      $fileName = $file;
    } else if ($searchIcon instanceof Storage_Model_File) {
      $file = $searchIcon->temporary();
      $fileName = $searchIcon->name;
    } else if ($searchIcon instanceof Core_Model_Item_Abstract && !empty($searchIcon->file_id)) {
      $tmpRow = Engine_Api::_()->getItem('storage_file', $searchIcon->file_id);
      $file = $tmpRow->temporary();
      $fileName = $tmpRow->name;
    } else if (is_array($searchIcon) && !empty($searchIcon['tmp_name'])) {
      $file = $searchIcon['tmp_name'];
      $fileName = $searchIcon['name'];
    } else if (is_string($searchIcon) && file_exists($searchIcon)) {
      $file = $searchIcon;
      $fileName = $searchIcon;
    } else {
      throw new Core_Model_Exception('invalid argument passed to setPhoto');
    }

    if (!$fileName) {
      $fileName = basename($file);
    }

    $extension = ltrim(strrchr(basename($fileName), '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';

    // Save
    $filesTable = Engine_Api::_()->getDbtable('files', 'storage');

    // Resize image (profile)
    $smallIconPath = $path . DIRECTORY_SEPARATOR . $base . '_s-i.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(32, 32)
      ->write($smallIconPath)
      ->destroy();

    // Store
    $smallIcon = $filesTable->createSystemFile($smallIconPath);
    // Remove temp files
    @unlink($smallIconPath);
    $this->file_id = $smallIcon->getIdentity();
    $this->save();
    return $this;
  }

}
