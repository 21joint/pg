<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Badgerequests.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Model_DbTable_Badgerequests extends Engine_Db_Table {

  protected $_rowClass = "Sitepagebadge_Model_Badgerequest";

	/**
   * Check that this page id have badge request which is in pending or holding status.
   *
   * @param Int page_id
   * @return Zend_Db_Table_Select
   */
	public function badgeRequestStatus($page_id) {

		//MAKE QUERY
		$status = 0;
    $status = $this->select()
                    ->from($this->info('name'), array('status'))
                    ->where('page_id = ?', $page_id)
                    ->where("(status = 3 OR status = 4)")
										->query()
										->fetchColumn();
		//FETCH DATA
    return $status;
	}

}
?>