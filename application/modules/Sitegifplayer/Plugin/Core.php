<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegifplayer
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2017-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegifplayer_Plugin_Core extends Zend_Controller_Plugin_Abstract
{
  public function onStorageFileCreateAfter($event)
  {
    $file = $event->getPayload();
    if( !( $file instanceof Core_Model_Item_Abstract && $file->storage_path && strtolower($file->mime_minor) === 'gif' ) ) {
      return;
    }
    $this->createFile($file);
  }

  public function onStorageFileUpdateAfter($event)
  {
    $file = $event->getPayload();
    if( !( $file instanceof Core_Model_Item_Abstract && $file->storage_path && strtolower($file->mime_minor) === 'gif' ) ) {
      return;
    }

    if( in_array('parent_file_id', $file->getModifiedFieldsName()) ) {
      $type = 'thumb.gif-' . md5(substr($file->storage_path, -46));
      $thumbFile = Engine_Api::_()->getItemTable('storage_file')->getFile($file->file_id, $type);
      if( $thumbFile->file_id && $thumbFile->parent_file_id == $file->file_id ) {
        try {
          $thumbFile->parent_file_id = $file->parent_file_id;
          $thumbFile->save();
        } catch( Exception $e ) {
          
        }
        return;
      }
    }
    if( !in_array('storage_path', $file->getModifiedFieldsName()) || strpos('thumb.gif-', $file->type) === 0 ) {
      return;
    }

    $this->createFile($file);
  }

  private function createFile($file)
  {
    $feedPath = '';
    try {
      $tmpRow = $file;
      $tempFile = Zend_Registry::isRegistered($tmpRow->hash) ? Zend_Registry::get($tmpRow->hash): $tmpRow->temporary();
      $fileName = $tmpRow->name;

      if( !$fileName ) {
        $fileName = $tempFile;
      }
      $sitegifplayerCreateFile = Zend_Registry::isRegistered('sitegifplayerCreateFile') ? Zend_Registry::get('sitegifplayerCreateFile') : null;
        if (empty($sitegifplayerCreateFile))
            return;
      $extension = 'jpeg';
      $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
      $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
      $type = 'thumb.gif-' . md5(substr($file->storage_path, -46));
      $params = array(
        'parent_type' => $file->parent_type,
        'parent_id' => $file->parent_id,
        'user_id' => $file->user_id,
        'name' => $fileName,
        'type' => $type,
        'parent_file_id' => $file->parent_file_id ? $file->parent_file_id : $file->file_id
      );

      // Save
      $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
      $feedPath = $path . DIRECTORY_SEPARATOR . $base . '_gif.' . $extension;
      $image = Sitegifplayer_Image::factory();
      $image->open($tempFile)
        ->setFormat($extension);
      $image->write($feedPath)
        ->destroy();
      $filesTable->createFile($feedPath, $params);
    } catch( Exception $e ) {
      // Remove temp files
    }
    if( $feedPath ) {
      @unlink($feedPath);
    }
  }

}
