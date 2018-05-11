<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Reactionicons.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_Model_DbTable_Reactionicons extends Engine_Db_Table {
  protected $_rowClass = 'Sitereaction_Model_Reactionicon';

  public function getReactions($params = array()) {
    $select = $this->select();
    $reactionsTablename = $this->info('name');

    if (isset($params['orderby']) && !empty($params['orderby']))
      $select->order($reactionsTablename . "." . $params['orderby'] . " ASC");

    return $this->fetchAll($select);
  }

}
