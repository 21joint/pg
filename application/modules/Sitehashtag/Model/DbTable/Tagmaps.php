<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Tagmaps.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_Model_DbTable_Tagmaps extends Engine_Db_Table
{
  protected $_rowClass = "Sitehashtag_Model_Tagmap";

  public function getActionTagMaps($action_id)
  {
    $select = $this->select()
      ->where('action_id = ? ', $action_id);
    return $this->fetchAll($select);
  }

}
