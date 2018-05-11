<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Badges.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitecredit_Model_DbTable_Badges extends Engine_Db_Table
{
  protected $_rowClass = "Sitecredit_Model_Badge";

  function setPhoto($photo, $param = array()) {
     
    if ($photo instanceof Zend_Form_Element_File) {
        $file = $photo->getFileName();
        $fileName = $file;
    } else if ($photo instanceof Storage_Model_File) {
        $file = $photo->temporary();
        $fileName = $photo->name;
    } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
        $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
        $file = $tmpRow->temporary();
        $fileName = $tmpRow->name;
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
        $file = $photo['tmp_name'];
        $fileName = $photo['name'];
    } else if (is_string($photo) && file_exists($photo)) {
        $file = $photo;
        $fileName = $photo;
    } else {
        throw new Zend_Exception("invalid argument passed to setPhoto");
    }
    if (!$fileName) {
        $fileName = basename($file);
    }
    
    $extension = ltrim(strrchr(basename($fileName), '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';

    $viewer = Engine_Api::_()->user()->getViewer();


    $params = array(
        'parent_type' => 'sitecredit_badges',
        'parent_id' => $viewer->getIdentity(),
        'user_id' => $viewer->getIdentity(),
        'name' => $fileName,
        );
        // Save
    $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
        //Fetching the width and height of thumbmail
        $normalHeight = 375;//Engine_Api::_()->getApi('settings', 'core')->getSetting('normal.news.height', 375);
        $normalWidth = 375;//Engine_Api::_()->getApi('settings', 'core')->getSetting('normal.news.width', 375);
        $largeHeight = 720;//Engine_Api::_()->getApi('settings', 'core')->getSetting('normallarge.news.height', 720);
        $largeWidth = 720;//Engine_Api::_()->getApi('settings', 'core')->getSetting('normallarge.news.width', 720);
        $mainHeight = 1600;//Engine_Api::_()->getApi('settings', 'core')->getSetting('main.news.height', 1600);
        $mainWidth = 1600;//Engine_Api::_()->getApi('settings', 'core')->getSetting('main.news.height', 1600);

        // Resize image (main)
        $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
        ->resize($mainWidth, $mainHeight)
        ->write($mainPath)
        ->destroy();

        // Resize image (large)
        $profilePath = $path . DIRECTORY_SEPARATOR . $base . '_l.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
        ->resize($largeWidth, $largeHeight)
        ->write($profilePath)
        ->destroy();

        // Resize image (normal)
        $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
        ->resize($normalWidth, $normalHeight)
        ->write($normalPath)
        ->destroy();

        // Resize image (icon)
        $squarePath = $path . DIRECTORY_SEPARATOR . $base . '_is.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file);

        $size = min($image->height, $image->width);
        $x = ($image->width - $size) / 2;
        $y = ($image->height - $size) / 2;

        $image->resample($x, $y, $size, $size, 48, 48)
        ->write($squarePath)
        ->destroy();

        // Store
        $iMain = $filesTable->createFile($mainPath, $params);
        $iProfile = $filesTable->createFile($profilePath, $params);
        $iIconNormal = $filesTable->createFile($normalPath, $params);
        $iSquare = $filesTable->createFile($squarePath, $params);

        $iMain->bridge($iProfile, 'thumb.profile');
        $iMain->bridge($iIconNormal, 'thumb.normal');
        $iMain->bridge($iSquare, 'thumb.icon');
        $iMain->bridge($iMain, 'thumb.main');

        // Remove temp files
        @unlink($mainPath);
        @unlink($profilePath);
        @unlink($normalPath);
        @unlink($squarePath);

        return $iMain->getIdentity();
    }  

    function getBadge($params=array()) {
      
       $credits = Engine_Api::_()->getDbtable('credits','sitecredit')->Credits($params);
       
       $select =$this->select();
       $select->where("credit_count <= ?",$credits->credit)->order("credit_count DESC")->limit($params['count']);
       return $this->fetchAll($select);

   }

   function getNextBadge($params=array()) {
      
    $credits = Engine_Api::_()->getDbtable('credits','sitecredit')->Credits($params);
    $select =$this->select();
    if(empty($credits->credit)) {
        $select->where("credit_count > 0");
    } else {
        $select->where("credit_count > ?",$credits->credit);
    }
    
    $select->order("credit_count ASC")->limit($params['count']);
    return $this->fetchRow($select);
}
}



