<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Contents.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Model_DbTable_Contents extends Engine_Db_Table {

  protected $_name = 'sitepageintegration_contents';
  protected $_rowClass = 'Sitepageintegration_Model_Content';

  /**
   * Return count results according to intregration modules.
   *
   * @param int $page_id
   */
  public function getCountResults($modNameKey, $listingtype_id = null) {
  
    $count = 0;
    $contentsTableName = $this->info('name');
		$resource_typetable = Engine_Api::_()->getItemTable($modNameKey);

		//GIVE AUTO INCREMENT ID NAME ACCRODING TO RESOURCE TYPE.
		$primaryId = current($resource_typetable->info("primary"));
		$resource_typetableTableName = $resource_typetable->info('name');

		$select = $resource_typetable->select()
						->setIntegrityCheck(false)
						->from($resource_typetableTableName, array('count(*) as count'))
						->joinleft($contentsTableName, $resource_typetableTableName . ".$primaryId = " .                       $contentsTableName . '.resource_id')
						->where($contentsTableName . '.resource_type = ?', $modNameKey);
		if ($modNameKey == 'sitereview_listing') {
			$select->where($resource_typetableTableName . '.listingtype_id = ?', $listingtype_id);
		}
		$row = $select->query()->fetchColumn();
		return $row;
  }
  
  /**
   * Return results according to intregration modules.
   *
   * @param array $params
   */
  public function getResults($params) {

		$contentsTableName = $this->info('name');
		
		$resource_typetable = Engine_Api::_()->getItemTable($params['resource_type']);

		//GIVE AUTO INCREMENT ID NAME ACCRODING TO RESOURCE TYPE.
		$primaryId = current($resource_typetable->info("primary"));
		$resource_typetableTableName = $resource_typetable->info('name');

    $resourceTypeColumns = array('*');
    if(isset($params['resource_type']) && $params['resource_type'] == 'sitereview_listing') {
        $resourceTypeColumns = array('listing_id', 'listingtype_id', 'title', 'photo_id', 'owner_id', 'view_count', 'review_count', 'like_count', 'comment_count', 'rating_avg', 'rating_editor', 'rating_users', 'sponsored', 'featured', 'newlabel', 'creation_date', 'body', 'closed');
    }
    elseif(isset($params['resource_type']) && $params['resource_type'] == 'sitebusiness_business') {
        $resourceTypeColumns = array('business_id', 'business_url','title', 'photo_id', 'owner_id', 'view_count', 'like_count', 'comment_count', 'sponsored', 'featured', 'creation_date', 'body', 'closed');
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessreview')) {
            $resourceTypeColumns[] = 'rating';
            $resourceTypeColumns[] = 'review_count';
        }
    }
    elseif(isset($params['resource_type']) && $params['resource_type'] == 'sitegroup_group') {
        $resourceTypeColumns = array('group_id', 'group_url','title', 'photo_id', 'owner_id', 'view_count', 'like_count', 'comment_count', 'sponsored', 'featured', 'creation_date', 'body', 'closed');
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupreview')) {
            $resourceTypeColumns[] = 'rating';
            $resourceTypeColumns[] = 'review_count';
        }
    }    
    
		$contentSelect = $resource_typetable->select()
						->setIntegrityCheck(false)
						->from($resource_typetableTableName, $resourceTypeColumns);
		if (isset($params['action']) && $params['action'] == 'index') {
			$contentSelect->joinleft($contentsTableName, $resource_typetableTableName . ".$primaryId = " .$contentsTableName . '.resource_id'); 
		} else {
			$contentSelect->joinleft($contentsTableName, $resource_typetableTableName . ".$primaryId = " .$contentsTableName . '.resource_id', null); 
		}
		$contentSelect->where($contentsTableName . '.resource_type = ?', $params['resource_type'])
		              ->where($contentsTableName . '.page_id = ?', $params['page_id']);

		if (isset($params['listingtype_id']) && $params['resource_type'] == 'sitereview_listing') {
			$contentSelect->where($resource_typetableTableName . '.listingtype_id = ?', $params['listingtype_id']);
			$contentSelect = Engine_Api::_()->sitereview()->addPrivacyListingsSQl($contentSelect);
		}

		if($params['resource_type'] == 'sitebusiness_business' ) {
			$contentSelect->where($resource_typetableTableName . '.approved = ?', '1')
			->where($resource_typetableTableName . '.declined = ?', '0')
			->where($resource_typetableTableName . '.draft = ?', '1')
			->where($resource_typetableTableName . ".search = ?", 1)
			->where($resource_typetableTableName . '.closed = ?', '0');
		}
		
		if (!empty($params['search'])) {
			$contentSelect->where($resource_typetableTableName . ".title LIKE ? OR " . $resource_typetableTableName . ".body LIKE ?", '%' . $params['search'] . '%');
		}
		
		if (isset($params['orderby']) && !empty($params['orderby'])) {
			$contentSelect->order($resource_typetableTableName . '.' . $params['orderby'] . ' DESC' );
		} else {
			$contentSelect->order($resource_typetableTableName . '.creation_date DESC' );
		}

		$paginator = Zend_Paginator::factory($contentSelect);
		
    return $paginator;
  }
  
  /**
   * Return resource ids array
   *
   * @param int $page_id
   */
	public function getResourceIds($page_id, $resource_type) {

    $select = $this->select()
                    ->from($this->info('name'), 'resource_id')
                    ->where('resource_type = ?', $resource_type)
                    ->where('page_id = ?', $page_id);
    $resource_ids = $select->query()->fetchAll(Zend_Db::FETCH_COLUMN);
    return $resource_ids;
	}
}