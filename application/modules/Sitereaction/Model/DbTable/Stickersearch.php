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

class Sitereaction_Model_DbTable_Stickersearch extends Engine_Db_Table {
  protected $_primary = 'stickersearch_id';
  protected $_rowClass = 'Sitereaction_Model_Stickersearch';

  public function getList() {
    $select = $this->select()
      ->order('order ASC');
    return $this->fetchAll($select);
  }

}
