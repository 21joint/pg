<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Image.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_Model_Image extends Core_Model_Item_Abstract {
  
  public function setPhoto($photo) {
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
    } else if (is_string($photo) && file_exists($photo)) {
      $file = $photo;
    } else {
      throw new Engine_Exception('invalid argument passed to setPhoto');
    }

    $name = basename($file);
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';

    // Add autorotation for uploded images. It will work only for SocialEngine-4.8.9 Or more then.
    $hasVersion = Engine_Api::_()->seaocore()->usingLessVersion('core', '4.8.9');
    if(!empty($hasVersion)) {
      $image = Engine_Image::factory();
      $thumb_file = $path . '/in_' . $name;    
      $image->open($file)
              ->resize(140, 160)
              ->write($thumb_file)
              ->destroy();

      $thumb_file_main = $path . '/m_' . $name;
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(1600, 1600)
              ->write($thumb_file_main)
              ->destroy();
    }else {
      $image = Engine_Image::factory();
      $thumb_file = $path . '/in_' . $name;    
      $image->open($file)
              ->autoRotate()
              ->resize(140, 160)
              ->write($thumb_file)
              ->destroy();

      $thumb_file_main = $path . '/m_' . $name;
      $image = Engine_Image::factory();
      $image->open($file)
              ->autoRotate()
              ->resize(1600, 1600)
              ->write($thumb_file_main)
              ->destroy();
    }

    try {
      $thumbFileRow = Engine_Api::_()->storage()->create($thumb_file, array(
          'parent_type' => $this->getType(),
          'parent_id' => $this->getIdentity()
              ));
      
      $thumbFileRowMain = Engine_Api::_()->storage()->create($thumb_file_main, array(
          'parent_type' => $this->getType(),
          'parent_id' => $this->getIdentity()
              ));

      // Remove temp file
      @unlink($thumb_file);
      @unlink($thumb_file_main);
    } catch (Exception $e) {
      
    }

    $this->icon_id = $thumbFileRow->file_id;
    $this->file_id = $thumbFileRowMain->file_id;

    $this->save();

    return $this;
  }

}