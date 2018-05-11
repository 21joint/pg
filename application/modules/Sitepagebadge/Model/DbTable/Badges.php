<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Badges.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Model_DbTable_Badges extends Engine_Db_Table {

  protected $_rowClass = "Sitepagebadge_Model_Badge";

	/**
   * Return badge data
   *
   * @param Int page_id
   * @return Zend_Db_Table_Select
   */
	public function getBadgesData($params = array(),$totalBadges = null) {

		$badgeTableName = $this->info('name');
    $pageTable = Engine_Api::_()->getDbTable('pages', 'sitepage');
		$pageTableName = $pageTable->info('name');

		if(isset($params['popular_badges']) && !empty($params['popular_badges'])) {
			$total_sitepagebadges = $params['totalbadges'];
			$select = $this->select()
											->setIntegrityCheck(false)
											->from($badgeTableName)
											->joinLeft($pageTableName, "$pageTableName.badge_id = $badgeTableName.badge_id", array('COUNT(engine4_sitepage_pages.badge_id) AS Frequency'))
											->group($pageTableName . '.badge_id')
											->order('Frequency DESC')
											->order($badgeTableName . '.creation_date DESC')
											->limit($total_sitepagebadges);

			$select = $select
											->where($pageTableName . '.search = ?', '1')
											->where($pageTableName . '.closed = ?', '0')
											->where($pageTableName . '.approved = ?', '1')
											->where($pageTableName . '.declined = ?', '0')
											->where($pageTableName . '.draft = ?', '1');
      if (isset($params['category_id']) && !empty($params['category_id'])) {
				$select = $select->where($pageTableName . '.	category_id =?', $params['category_id']);
			}
			if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
				$select->where($pageTableName . '.expiration_date  > ?', date("Y-m-d H:i:s"));
			}
    //Start Network work
      $select = $pageTable->getNetworkBaseSql($select, array('not_groupBy' => 1));
    //End Network work
			return $this->fetchAll($select);

		}
		else {
      if (isset($params['category_id']) && !empty($params['category_id'])) {
        $select = $this->select()
                  ->setIntegrityCheck(false)
                  ->from($badgeTableName)
									->join($pageTableName, "$pageTableName.badge_id = $badgeTableName.badge_id", array())
									->where($pageTableName . '.	category_id =?', $params['category_id'])
                  ->order($badgeTableName. '.creation_date DESC');
			}
      else {
				//MAKE QUERY
				$select = $this->select()->order('creation_date DESC');
      }
      $select->limit($totalBadges);
			if(isset($params['search_code']) && !empty($params['search_code'])) {
				$select->from($badgeTableName, array('badge_id', 'title'));
			}

			//FETCH RESULTS
			return $this->fetchAll($select);
		}
  }

}
?>