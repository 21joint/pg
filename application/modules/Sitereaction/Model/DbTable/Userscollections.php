<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Userscollections.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_Model_DbTable_Userscollections extends Engine_Db_Table {
  protected $_primary = 'collection_id';

  public function getCollectinIds($user_id) {
    return$this->select()
        ->from($this->info('name'), "collection_id")
        ->where('user_id =?', $user_id)->query()
        ->fetchAll(Zend_Db::FETCH_COLUMN);
  }

  public function fetchUserCollection($user_id, $collection_id) {
    $select = $this->select()
      ->where('user_id =?', $user_id)
      ->where('collection_id =?', $collection_id);
    return $this->fetchRow($select);
  }

  public function add($user_id, $collection_id) {
    $row = $this->createRow();
    $row->setFromArray(array(
      'user_id' => $user_id,
      'collection_id' => $collection_id
    ));
    $row->save();
  }

  public function remove($user_id, $collection_id) {
    $this->fetchUserCollection($user_id, $collection_id)->delete();
  }

}
