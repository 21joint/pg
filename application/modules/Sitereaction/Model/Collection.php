<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Collection.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_Model_Collection extends Core_Model_Item_Abstract {
  protected $_searchTriggers = false;

  public function getPhotoUrl($type = null) {
    if (empty($this->sticker_id)) {
      $stickerTable = Engine_Api::_()->getItemTable('sitereaction_sticker');
      $stickerInfo = $stickerTable->select()
        ->from($stickerTable, array('sticker_id', 'file_id'))
        ->where('collection_id = ?', $this->collection_id)
        ->order('order ASC')
        ->limit(1)
        ->query()
        ->fetch();
      if (!empty($stickerInfo)) {
        $this->sticker_id = $stickerInfo['sticker_id'];
        $this->save();
        $file_id = $stickerInfo['file_id'];
      } else {
        return;
      }
    } else {
      $stickerTable = Engine_Api::_()->getItemTable('sitereaction_sticker');
      $file_id = $stickerTable->select()
        ->from($stickerTable, 'file_id')
        ->where('sticker_id = ?', $this->sticker_id)
        ->query()
        ->fetchColumn();
    }

    if (!$file_id) {
      return;
    }

    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($file_id, $type);
    if (!$file) {
      return;
    }

    return $file->map();
  }

  public function getStickers() {
    $stickerTable = Engine_Api::_()->getItemTable('sitereaction_sticker');
    $select = $stickerTable->select()
      ->where('collection_id = ?', $this->collection_id)
      ->order('order ASC');
    return $stickerTable->fetchAll($select);
  }

  public function getFirstSticker() {
    $stickerTable = Engine_Api::_()->getItemTable('sitereaction_sticker');
    $select = $stickerTable->select()
      ->where('collection_id = ?', $this->collection_id)
      ->order('order ASC')
      ->limit(1);
    return $stickerTable->fetchRow($select);
  }

  public function getLastSticker() {
    $stickerTable = Engine_Api::_()->getItemTable('sitereaction_sticker');
    $select = $stickerTable->select()
      ->where('collection_id = ?', $this->collection_id)
      ->order('order DESC')
      ->limit(1);
    return $stickerTable->fetchRow($select);
  }

  public function count() {
    $stickerTable = Engine_Api::_()->getItemTable('sitereaction_sticker');
    return $stickerTable->select()
        ->from($stickerTable, new Zend_Db_Expr('COUNT(sticker_id)'))
        ->where('collection_id = ?', $this->collection_id)
        ->limit(1)
        ->query()
        ->fetchColumn();
  }

  public function createStickers($stickers = array()) {
    $stickerTable = Engine_Api::_()->getDbtable('stickers', 'sitereaction');
    $stickerRows = array();
    foreach ($stickers as $stickerData) {
      $sticker = $stickerTable->createRow();
      $sticker->save();
      $sticker->order = $sticker->sticker_id;
      $sticker->collection_id = $this->collection_id;
      $sticker->setSticker($stickerData['Filedata']);
      $sticker->save();
      $stickerRows[] = $sticker;
    }

    if (!$this->sticker_id && $stickerRows) {
      $this->sticker_id = $stickerRows[0]->sticker_id;
      $this->save();
    }
    return $stickerRows;
  }

}
