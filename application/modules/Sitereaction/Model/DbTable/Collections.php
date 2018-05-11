<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Collections.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_Model_DbTable_Collections extends Engine_Db_Table {
  protected $_rowClass = 'Sitereaction_Model_Collection';

  /**
   * Gets a select object for the user's classified entries
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Db_Table_Select
   */
  public function getCollectinosSelect($params = array()) {
    $select = $this->select();
    if (isset($params['enabled'])) {
      $select->where('enabled =?', $params['enabled']);
    }
    if (isset($params['include'])) {
      $select->where('include =?', $params['include']);
    }

    if (!empty($params['colleaction_ids'])) {
      $select->where('collection_id IN(?)', (array) $params['colleaction_ids']);
    }
    $order = !empty($params['orderDesc']) ? 'DESC' : 'ASC';
    $orderby = !empty($params['orderby']) ? $params['orderby'] : 'order';
    $select->order($orderby . ' ' . $order);
    $select->where("start_time <= FROM_UNIXTIME(?)", time());
    $select->where("end_time > FROM_UNIXTIME(?)", time());
    return $select;
  }

  public function getCollectinIds($user_id) {
    return $this->getCollectinosSelect(array(
          'enabled' => 1,
          'include' => 1
        ))
        ->from($this->info('name'), "collection_id")
        ->query()
        ->fetchAll(Zend_Db::FETCH_COLUMN);
  }

  public function getCollectinos($colleaction_ids = array()) {
    return $this->fetchAll($this->getCollectinosSelect(array(
          'colleaction_ids' => $colleaction_ids
    )));
  }

  public function getCollectinosPaginator($params = array()) {
    $paginator = Zend_Paginator::factory($this->getCollectinosSelect($params));
    if (!empty($params['page'])) {
      $paginator->setCurrentPageNumber($params['page']);
    }
    if (!empty($params['limit'])) {
      $paginator->setItemCountPerPage($params['limit']);
    }
    return $paginator;
  }

  public function getStoreCollection($params = array()) {
    $params = array_merge(array('enabled' => 1, 'include' => 0), $params);
    return $this->getCollectinosPaginator($params);
  }
}
