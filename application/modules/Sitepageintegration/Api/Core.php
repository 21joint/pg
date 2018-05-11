<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Api_Core extends Core_Api_Abstract {

	public function pageintergrationTitleEdit($title_plural, $listingtype_id, $previousTitlePlu) {
	
	  $db = Engine_Db_Table::getDefaultAdapter();
		$db->query("UPDATE `engine4_sitepageintegration_mixsettings` SET `item_title` = '". $title_plural ."' WHERE `engine4_sitepageintegration_mixsettings`.`resource_type` = 'sitereview_listing_".$listingtype_id."' LIMIT 1 ;");

		$db->query("UPDATE `engine4_core_menuitems` SET `label` = 'Post New ". ucfirst($title_plural) ."' WHERE `engine4_core_menuitems`.`name` ='sitepage_sitereview_gutter_create_".$listingtype_id."' AND `engine4_core_menuitems`.`module` ='sitepage'  LIMIT 1 ;");

		$db->query("UPDATE `engine4_core_content` SET `params` = REPLACE(params, '$previousTitlePlu', '".ucfirst($title_plural)."') WHERE `name` = 'sitepageintegration.profile-items' AND `params` Like '%$previousTitlePlu%'");
		
		$db->query("UPDATE `engine4_sitepage_admincontent` SET `params` = REPLACE(params, '$previousTitlePlu', '".ucfirst($title_plural)."') WHERE `name` = 'sitepageintegration.profile-items' AND `params` Like '%$previousTitlePlu%'");
		
		$db->query("UPDATE `engine4_sitepage_content` SET `params` = REPLACE(params, '$previousTitlePlu', '".ucfirst($title_plural)."') WHERE `name` = 'sitepageintegration.profile-items' AND `params` Like '%$previousTitlePlu%'");

	}
	
	public function deleteContents($listingtype_id) {

	  $mixsettingsTable = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration');
	  
		$contentsTable = Engine_Api::_()->getDbtable('contents', 'sitepageintegration');
		$contentsTableName = $contentsTable->info('name');
		
		$resource_typetable = Engine_Api::_()->getItemTable('sitereview_listing');

		$primaryId = current($resource_typetable->info("primary"));
		$resource_typetableTableName = $resource_typetable->info('name');
		
		$select = $mixsettingsTable->select()
						->from($mixsettingsTable->info('name'))
						->where('resource_type = ?', 'sitereview_listing_' . $listingtype_id);
		$result = $mixsettingsTable->fetchRow($select);
		
		if (!empty($result)) {
			$mixsettingsTable->delete(array('resource_type =?' => 'sitereview_listing_' . $listingtype_id));
		}

		$contentsselect = $resource_typetable->select()
							->setIntegrityCheck(false)
							->from($resource_typetableTableName)
							->join($contentsTableName, $resource_typetableTableName . ".$primaryId = " .                       $contentsTableName . '.resource_id')
							->where($resource_typetableTableName . '.listingtype_id = ?', $listingtype_id);
		$listingDatas = $resource_typetable->fetchAll($contentsselect);

		foreach($listingDatas as $listingData) {
			$contentsTable->delete(array('resource_id = ?' => $listingData->listing_id, 'resource_type = ?' => 'sitereview_listing'));
		}
		$db = Engine_Db_Table::getDefaultAdapter();
		$db->query("DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` ='sitepage_sitereview_gutter_create_".$listingtype_id."' AND `engine4_core_menuitems`.`module` ='sitepage'  LIMIT 1;");
	}
  
  public function getPageType($getKey) {
    $getSiteintLsettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageintegration.lsettings');
    $getSiteintType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageint.get.type', null);
    $getGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageint.global.type', null);
    if( !empty($getGlobalType) ) {
      return true;
    }
    $getStr = $getKey . $getSiteintLsettings;
    $getStr = @md5($getStr);
    if( $getSiteintType == $getStr ) {
      return true;
    }
    return false;
  }
	
	public function getContentCount($params) {
	
	  switch($params['resource_type']) {
			case 'pennyauction_product' :
			case 'groupbuy_deal':
			case 'folder':
			case 'quiz':
			 $resourceuser_id = 'user_id';
			break;
			default:
			 $resourceuser_id = 'owner_id';
			break;
		}
		
	  //Get all manage admin ids.
		$user_ids = Engine_Api::_()->getDbtable('manageadmins', 'sitepage')->getManageAdminIds($params['page_id'], 'pageintergration');
		
		$viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
		
	  //FETCH DATA ACCRODING TO RESOURCE TYPE.
    $resourceTypeTable = Engine_Api::_()->getItemTable($params['resource_type']);
    $resourceTypeTableName = $resourceTypeTable->info('name');
    
	  $contentSelect = $resourceTypeTable->select();
    if ($params['resource_type'] == 'sitereview_listing' || $params['resource_type'] == 'list_listing') {
			$contentSelect = $contentSelect->where($resourceTypeTableName . '.approved = ?', '1');
		}
		
		if ($params['resource_type'] == 'sitereview_listing') {
			$contentSelect = $contentSelect->where($resourceTypeTableName . '.listingtype_id = ?', $params['listingtype_id'])
			->where($resourceTypeTableName . '.draft = ?', '0');
		} elseif($params['resource_type'] == 'list_listing') {
			$contentSelect = $contentSelect->where($resourceTypeTableName . '.draft = ?', '1');
		}
    	$addableListing = Engine_Api::_()->getApi( 'settings' , 'core' )->getSetting( 'addable.integration');

		if ($addableListing == 2 && !empty($user_ids)) {
			$contentSelect->where($resourceTypeTableName . "." . $resourceuser_id . " IN (?)", (array) $user_ids);
    } elseif($addableListing == 1) {
			$contentSelect->where($resourceTypeTableName . "." . $resourceuser_id . " = ?", $viewer_id);
    }

    $results = $contentSelect->query()->fetchAll(Zend_Db::FETCH_COLUMN);
    
    return $results;
	}
	
	public function getAutoSuggetContents($params) {
	
	  $resourceTypeTable = Engine_Api::_()->getItemTable($params['resource_type']); 
    $resourceTypeTableName = $resourceTypeTable->info('name');

    //GIVE AUTO INCREMENT ID NAME ACCRODING TO CONTENT TYPE.
    $primaryId = current($resourceTypeTable->info("primary"));

    //Get all resource ids.
    $resource_ids = Engine_Api::_()->getDbtable('contents', 'sitepageintegration')->getResourceIds($params['page_id'], $params['resource_type']);
    
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    
    //Get all manage admin ids.
    $user_ids = Engine_Api::_()->getDbtable('manageadmins', 'sitepage')->getManageAdminIds($params['page_id'], 'pageintergration');

    $select = $resourceTypeTable->select()
							->from($resourceTypeTableName);
							if ($params['resource_type'] == 'document') {
							$select->where('document_title  LIKE ? ', '%' . $params['searchText'] . '%');
							} else {
							$select->where('title  LIKE ? ', '%' . $params['searchText'] . '%');
							}
							if ($params['resource_type'] != 'folder' || $params['resource_type'] != 'quiz') {
							$select->where($resourceTypeTableName . '.approved = ?', '1');
							}

    if ($params['resource_type'] == 'sitereview_listing') {
			$select->where('listingtype_id = ?', $params['listingtype_id'])
			       ->where($resourceTypeTableName . '.draft = ?', '0');
		} 
		elseif($params['resource_type'] == 'list_listing') {
			$select->where($resourceTypeTableName . '.draft = ?', '1');
		}
		elseif($params['resource_type'] == 'sitebusiness_business' || $params['resource_type'] == 'sitegroup_group') {
			$select->where($resourceTypeTableName . '.declined = ?', '0')
						->where($resourceTypeTableName . '.draft = ?', '1')
						->where($resourceTypeTableName . ".search = ?", 1)
						->where($resourceTypeTableName . '.closed = ?', '0');
		}
		
		$addableListing = Engine_Api::_()->getApi( 'settings' , 'core' )->getSetting( 'addable.integration');

		if ($addableListing == 2 && !empty($user_ids)) {
			$select->where($resourceTypeTableName . "." . $params['resourceuser_id'] . " IN (?)", (array) $user_ids);
    } elseif($addableListing == 1) {
			$select
			//->where($resourceTypeTableName . "." . $params['resourceuser_id'] . " IN (?)", $viewer_id);
			->where($resourceTypeTableName . "." . $params['resourceuser_id'] . " = ?", $viewer_id);
    }

    if (!empty($resource_ids)) {
      $select->where($resourceTypeTableName . "." . $primaryId . " NOT IN (?)", (array) $resource_ids);
    }
    if ($params['resource_type'] == 'document') {
    $select->order('document_title ASC')->limit(40); 
    } else {
    $select->order('title ASC')->limit(40); 
    }

    $resourceTypeResults = $resourceTypeTable->fetchAll($select);
    
	  return $resourceTypeResults;	
	}
      
  public function getEnabled($listingTypeId, $enabled) {

    $db = Engine_Db_Table::getDefaultAdapter();
    $mixsettingsTable = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration');
		$tableName = $mixsettingsTable->info('name');
		$select = $mixsettingsTable->select()
							->setIntegrityCheck(false)
							->from($tableName)
							->where($tableName . '.resource_type = ?', "sitereview_listing_$listingTypeId");
		$row = $select->query()->fetchColumn();
		if (!empty($row)) {
			$db->query("UPDATE `engine4_sitepageintegration_mixsettings` SET `enabled` = '" . $enabled ."' WHERE `engine4_sitepageintegration_mixsettings`.`resource_type` = 'sitereview_listing_".$listingTypeId."' LIMIT 1 ;");
		}
  }
  
  public function integrationParams($resource_type, $listingtype_id = null, $page_id = null, $item_title = null) {
  
		$temp = array();
		$viewer = Engine_Api::_()->user()->getViewer();
		switch($resource_type) {
			case 'sitebusiness_business':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Businesses';
				$temp['plular'] = 'Business';
				$temp['singular_small'] = 'businesses';
				$temp['icon_name'] = 'sitebusiness';
				$temp['app_icon_name'] = 'business';
				$temp['ul_class'] = 'sitebusinesses_profile_tab';
				$temp['plugin_name'] = 'Directory / Businesses Plugin';
				$temp['level_setting_title'] = "Allow Adding of Businesses from Directory / Businesses Plugin";
				if(!empty($page_id)) {
					if (Engine_Api::_()->sitebusiness()->hasPackageEnable()) {
						$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','page_id' => $page_id),'sitebusiness_packages', true);
					} else {
						$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'sitebusiness_general', true);
					}
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
			case 'sitegroup_group':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Groups';
				$temp['plular'] = 'Group';
				$temp['singular_small'] = 'groups';
				$temp['icon_name'] = 'sitegroup';
				$temp['app_icon_name'] = 'group';
				$temp['plugin_name'] = 'Groups / Communities Plugin';
				$temp['level_setting_title'] = "Allow Adding of Groups from Groups / Communities Plugin";
				$temp['ul_class'] = 'sitegroups_profile_tab';
				if(!empty($page_id)) {
					if (Engine_Api::_()->sitegroup()->hasPackageEnable()) {
						$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','page_id' =>$page_id),'sitegroup_packages', true);
					} else {
						$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','page_id' => $page_id),'sitegroup_general', true);
					}
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
		  case 'sitetutorial_tutorial':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Tutorials';
				$temp['plular'] = 'Tutorial';
				$temp['singular_small'] = 'tutorials';
				$temp['icon_name'] = 'tutorial';
				$temp['app_icon_name'] = 'tutorial';
				$temp['plugin_name'] = 'Tutorials Plugin';
				$temp['level_setting_title'] = "Allow Adding of Tutorials from Tutorials Plugin";
				$temp['ul_class'] = 'tutorial_list';
				if(!empty($page_id)) {
					$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'sitetutorial_general', true);
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
			case 'sitefaq_faq':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'FAQs';
				$temp['plular'] = 'FAQ';
				$temp['singular_small'] = 'faqs';
				$temp['icon_name'] = 'faq';
				$temp['app_icon_name'] = 'faq';
				$temp['ul_class'] = 'faq_list';
				$temp['plugin_name'] = 'FAQs, Knowledgebase, Tutorials & Help Center Plugin';
				$temp['level_setting_title'] = "Allow Adding of FAQs from FAQs, Knowledgebase, Tutorials & Help Center Plugin";
				if(!empty($page_id)) {
					$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'sitefaq_general', true);
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
			case 'document':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Documents';
				$temp['plular'] = 'Document';
				$temp['singular_small'] = 'documents';
				$temp['icon_name'] = 'document';
				$temp['app_icon_name'] = 'document';
				$temp['plugin_name'] = 'Documents Plugin';
				$temp['level_setting_title'] = "Allow Adding of Documents from Documents Plugin";
				$temp['ul_class'] = 'seaocore_profile_list';
				if(!empty($page_id)) {
					$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'document_create', true);
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
			case 'folder':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Folders';
				$temp['plular'] = 'Folder';
				$temp['singular_small'] = 'folders';
				$temp['icon_name'] = 'folder';
				$temp['app_icon_name'] = 'folder';
				$temp['plugin_name'] = 'Folders Plugin';
				$temp['level_setting_title'] = "Allow Adding of Folders from Folders Plugin";
				$temp['ul_class'] = 'seaocore_profile_list';
				if(!empty($page_id)) {
					$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'folder_create', true);
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
			case 'quiz':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Quizzes';
				$temp['plular'] = 'Quiz';
				$temp['singular_small'] = 'quizzes';
				$temp['icon_name'] = 'quiz';
				$temp['app_icon_name'] = 'quiz';
				$temp['plugin_name'] = 'Quizzes Plugin';
				$temp['level_setting_title'] = "Allow Adding of Quizzes from Quizzes Plugin";
				$temp['ul_class'] = 'seaocore_profile_list';
				if(!empty($page_id)) {
					$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'quiz_create', true);
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
		  case 'list_listing':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Listings';
				$temp['plular'] = 'Listing';
				$temp['singular_small'] = 'listings';
				$temp['icon_name'] = 'listing';
				$temp['app_icon_name'] = 'listing';
				$temp['plugin_name'] = 'Listings / Catalog Showcase Plugin';
				$temp['level_setting_title'] = "Allow Adding of Listings from Listings / Catalog Showcase Plugin";
				$temp['ul_class'] = 'seaocore_profile_list';
				if(!empty($page_id)) {
					$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'list_general', true);
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
			case 'sitereview_listing':
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, "create_listtype_$listingtype_id");
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Listings';
				$temp['plular'] = '';
				$temp['singular_small'] = '';
				if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereview') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewlistingtype')) {
					$temp['plugin_name'] = 'Reviews & Ratings - Multiple Listing Types Plugin';
				} else {
					$temp['plugin_name'] = 'Multiple Listing Types Plugin Core (Reviews & Ratings Plugin)';
				}
				$temp['icon_name'] = "sitereview_listtype_$listingtype_id";
				$temp['app_icon_name'] = "listtype_$listingtype_id";
				$temp['level_setting_title'] = "Allow Adding of Multiple Listing Types - $item_title";
				$temp['ul_class'] = 'sr_browse_list';
				
				$sitereviewpaidlistingEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting');
				if($sitereviewpaidlistingEnabled) {
					$total_packages = Engine_Api::_()->getDbtable('packages', 'sitereviewpaidlisting')->getTotalPackage($listingtype_id);
				}
				
				if($sitereviewpaidlistingEnabled && $total_packages == 1) {
				  $package = Engine_Api::_()->getDbTable('packages', 'sitereviewpaidlisting')->getEnabledPackage($listingtype_id);
					$route_name = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create', 'id' => $package->package_id, 'page_id' => $page_id),"sitereview_general_listtype_$listingtype_id", true);
				} elseif($sitereviewpaidlistingEnabled && $total_packages > 1) {
					$route_name = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','page_id' => $page_id),"sitereview_all_package_listtype_$listingtype_id", true);
				} else {
				  $route_name = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'sitereview_general_listtype_' . $listingtype_id, true);
				}
				
				if(!empty($page_id)) {
				
					$temp['URL'] = $route_name; //'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create','page_id' => $page_id),'sitereview_general_listtype_' . $listingtype_id, true);
					
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
			  }
			break;
		  case 'sitestoreproduct_product':
		  
		    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
		    if(!empty($sitepage->owner_id)) {
					$store = Engine_Api::_()->getDbtable('stores', 'sitestore')->getStoreId($sitepage->owner_id);
		    }
		    
				$temp['create_privacy'] = Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
				$temp['resourceuser_id'] = 'owner_id';
				$temp['singular'] = 'Products';
				$temp['plular'] = 'Product';
				$temp['singular_small'] = 'products';
				$temp['icon_name'] = 'sitestoreproduct';
				$temp['app_icon_name'] = 'product';
				$temp['plugin_name'] = 'Products / Communities Plugin';
				$temp['level_setting_title'] = "Allow Adding of Products from Products / Communities Plugin";
				$temp['ul_class'] = 'sr_sitestoreproduct_browse_list';
				
				if(!empty($page_id)) {
					if(!empty($store)) {
						if(count($store) == 1) {
							$store_id = $store[0]['store_id'];
							if (Engine_Api::_()->sitestore()->hasPackageEnable()) {
								$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'create', 'store_id' => $store_id, 'page_id' => $page_id),'sitestoreproduct_general', true);
							} else {
								$temp['URL'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','page_id' => $page_id),'sitestore_general', true);
							}
						} else {
							$temp['store_count']  = count($store);
							
						}
					}
					
					$temp['manage_url'] = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index','resource_type' => $resource_type, 'page_id' => $page_id, 'listingtype_id' => $listingtype_id ),'sitepageintegration_create', true);
				}
			break;
			case 'pennyauction_product' :
			case 'groupbuy_deal':
				$temp['resourceuser_id'] = 'user_id';
			break;
			// 			default:
			// 				$temp['resourceuser_id'] = 'owner_id';
			// 			break;
		}
		return $temp;
  }

//   public function itemPrivacyCheck($subject) {
//   
//     //$subject = Engine_Api::_()->core()->getSubject();
//     $resource_id = $subject->getIdentity();
//     $resource_type = $subject->getType();
//     
//     $itemPrivacyCheck = false;
//     
//     if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepageintegration')) {
// 			$contentsintegrationTable = Engine_Api::_()->getDbtable('contents', 'sitepageintegration');
// 			$contentsintegrationTableName = $contentsintegrationTable->info('name');
// 			$item_id = 'page_id';
// 			$item_type = 'sitepage_page';
// 	
// 			$itemId = $contentsintegrationTable->select()
// 						->from($contentsintegrationTableName, new Zend_Db_Expr("MAX(`$item_id`) as $item_id"))
// 						->where('resource_id = ?', $resource_id)
// 						->where('resource_type = ?', $resource_type)
// 						->query()->fetchColumn();
// 			//$result = $select->query()->fetch();
// 
// 			if (!empty($itemId)) {
// 
// 				$item_object = Engine_Api::_()->getItem($item_type, $itemId);
// 
// 				//START MANAGE-ADMIN CHECK
// 				$isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($item_object, 'view'); 
// 				if (empty($isManageAdmin)) {
// 					$itemPrivacyCheck = true;
// 				}
// 				
// 				//PAGE VIEW AUTHORIZATION
// 				if (!Engine_Api::_()->sitepage()->canViewPage($item_object)) {
// 					$itemPrivacyCheck  = true;
// 				}
// 			}
// 		}
// 		
// 		if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessintegration')) {
// 			$contentsintegrationTable = Engine_Api::_()->getDbtable('contents', 'sitebusinessintegration');
// 			$contentsintegrationTableName = $contentsintegrationTable->info('name');
// 			$item_id = 'business_id';
// 			$item_type = 'sitebusiness_business';
// 	
// 			$itemId = $contentsintegrationTable->select()
// 						->from($contentsintegrationTableName, new Zend_Db_Expr("MAX(`$item_id`) as $item_id"))
// 						->where('resource_id = ?', $resource_id)
// 						->where('resource_type = ?', $resource_type)->query()->fetchColumn();
// 			//$result = $select->query()->fetch();
// 
// 			if (!empty($itemId)) {
// 
// 				$item_object = Engine_Api::_()->getItem($item_type, $itemId);
// 
// 				//START MANAGE-ADMIN CHECK
// 				$isManageAdmin = Engine_Api::_()->sitebusiness()->isManageAdmin($item_object, 'view'); 
// 				if (empty($isManageAdmin)) {
// 					$itemPrivacyCheck  = true;
// 				}
// 				
// 				//BUSINESS VIEW AUTHORIZATION
// 				if (!Engine_Api::_()->sitebusiness()->canViewBusiness($item_object)) {
// 					$itemPrivacyCheck  = true;
// 				}
// 			}
// 		}
// 		
// 		if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupintegration')) {
// 			$contentsintegrationTable = Engine_Api::_()->getDbtable('contents', 'sitegroupintegration');
// 			$contentsintegrationTableName = $contentsintegrationTable->info('name');
// 			$item_id = 'group_id';
// 			$item_type = 'sitegroup_group';
// 	
// 			$itemId = $contentsintegrationTable->select()
// 						->from($contentsintegrationTableName, new Zend_Db_Expr("MAX(`$item_id`) as $item_id"))
// 						->where('resource_id = ?', $resource_id)
// 						->where('resource_type = ?', $resource_type)->query()->fetchColumn();
// 			//$result = $select->query()->fetch();
// 
// 			if (!empty($itemId)) {
// 
// 				$item_object = Engine_Api::_()->getItem($item_type, $itemId);
// 
// 				//START MANAGE-ADMIN CHECK
// 				$isManageAdmin = Engine_Api::_()->sitegroup()->isManageAdmin($item_object, 'view'); 
// 				if (empty($isManageAdmin)) {
// 					$itemPrivacyCheck  = true;
// 				}
// 				
// 				//GROUP VIEW AUTHORIZATION
// 				if (!Engine_Api::_()->sitegroup()->canViewGroup($item_object)) {
// 					$itemPrivacyCheck  = true;
// 				}
// 			}
// 		}
// 		return $itemPrivacyCheck;
//   }
}