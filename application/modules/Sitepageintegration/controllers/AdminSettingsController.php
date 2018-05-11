<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_AdminSettingsController extends Core_Controller_Action_Admin {

    public function __call($method, $params) {
        /*
         * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
         * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
         * REMEMBER:
         *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
         *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
         */
        if (!empty($method) && $method == 'Sitepageintegration_Form_Admin_Global') {

        }
        return true;
    }
    
  public function indexAction() {

		$apiSettings = Engine_Api::_()->getApi('settings', 'core');
		$groupSettings = $apiSettings->getSetting('sitepage.group.integration', 0);
		$documentSettings = $apiSettings->getSetting('sitepage.document.integration', 0);
		$storeproductSettings = $apiSettings->getSetting('sitepage.storeproduct.integration', 0);

		//START GROUP / COMMUNITIES PLUGIN INTEGRATION.
		if(Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled('sitegroup')) {
		  if(isset($_POST['sitepage_group_integration']) && $_POST['sitepage_group_integration'] != $groupSettings)
			$this->pluginIntegration('sitepage_group_integration', 'Groups', 'sitegroup_group_0', 'sitegroup', 'group_id');
		}
		//END GROUP / COMMUNITIES PLUGIN INTEGRATION.

	  //START DOCUMENT PLUGIN INTEGRATION.
	  if(Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled('document')) {
	    if(isset($_POST['sitepage_document_integration']) && $_POST['sitepage_document_integration'] != $documentSettings)
			$this->pluginIntegration('sitepage_document_integration', 'Documents', 'document_0', 'document', 'document_id');
	  }
	  //START DOCUMENT PLUGIN INTEGRATION.
	  
	  //START STORE PRODUCTS PLUGIN WORK
	  if(Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled('sitestoreproduct')) {
	    if(isset($_POST['sitepage_storeproduct_integration']) && $_POST['sitepage_storeproduct_integration'] != $storeproductSettings)
			$this->pluginIntegration('sitepage_storeproduct_integration', 'Products', 'sitestoreproduct_product_0', 'sitestoreproduct', 'product_id');
	  }
    
    $pluginName = 'sitepageintegration';
    if (!empty($_POST[$pluginName . '_lsettings']))
      $_POST[$pluginName . '_lsettings'] = @trim($_POST[$pluginName . '_lsettings']);
    
		//END STORE PRODUCTS PLUGIN WORK	
    $reload_count = 0;
    $page_reload = 1;
    include_once APPLICATION_PATH . '/application/modules/Sitepageintegration/controllers/license/license1.php';
  }

  public function faqAction() {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepageintegration_admin_main', array(), 'sitepageintegration_admin_main_faq');
  }
  
  public function readmeAction() {
    
  }
  
  public function setDefaultData($pageTable, $pageTableName, $coreContentTable, $coreContentTableName, $pageId, $content_id, $forPage=null, $module, $params, $resourceType = null) {
    $db = Engine_Db_Table::getDefaultAdapter();

    $select = $pageTable->select()
            ->from($pageTableName, array($pageId))
            ->where('name = ?', 'sitepage_index_view');

    if (!empty($forPage)) {
      $select->where("$pageId = ?", $forPage);
    }

    $getPageId = $select->query()->fetchColumn();

    // @Make an condition
    if (!empty($getPageId)) {
      $getContainerId = $coreContentTable->select()
              ->from($coreContentTableName, array($content_id))
              ->where('type = ?', 'container')
              ->where("$pageId = ?", $getPageId)
              ->where('name = ?', 'main')
              ->query()
              ->fetchColumn();

      if (!empty($getContainerId)) {
        $getMiddleContentId = $coreContentTable->select()
                ->from($coreContentTableName, array($content_id))
                ->where('type = ?', 'container')
                ->where("parent_content_id = ?", $getContainerId)
                ->where('name = ?', 'middle')
                ->query()
                ->fetchColumn();

        if (!empty($getMiddleContentId)) {

          $getCoreContainerContentId = $coreContentTable->select()
                  ->from($coreContentTableName, array($content_id))
                  ->where('type = ?', 'widget')
                  ->where("$pageId = ?", $getPageId)
                  ->where('name = ?', 'core.container-tabs')
                  ->query()
                  ->fetchColumn();

          $select = new Zend_Db_Select($db);
          $select
                  ->from('engine4_core_modules')
                  ->where('name = ?', $module);
          $check_list = $select->query()->fetchObject();

          if (!empty($check_list)) {
            if (!empty($getCoreContainerContentId)) {
              $contentPageId = $coreContentTable->select()
                      ->from($coreContentTableName, array($content_id))
                      ->where('parent_content_id = ?', $getCoreContainerContentId)
                      ->where('name = ?', 'sitepageintegration.profile-items')
                      ->where('type = ?', 'widget')
                      ->where('params LIKE ?', '%' . $resourceType . '%')
                      ->query()
                      ->fetchColumn();

              if (empty($contentPageId)) {
                //INSERT MAIN-MIDDLE CONTAINER
                $insertMainItem = $coreContentTable->createRow();
                $insertMainItem->$pageId = $getPageId;
                $insertMainItem->type = 'widget';
                $insertMainItem->name = 'sitepageintegration.profile-items';
                $insertMainItem->parent_content_id = $getCoreContainerContentId;
                $insertMainItem->order = 999;
                $insertMainItem->params = $params;
                $insertMainItem->save();
              }
            } else {
              $contentPageId = $coreContentTable->select()
                      ->from($coreContentTableName, array($content_id))
                      ->where('parent_content_id = ?', $getMiddleContentId)
                      ->where('name = ?', 'sitepageintegration.profile-items')
                      ->where('type = ?', 'widget')
                      ->where('params LIKE ?', '%' . $resourceType . '%')
                      ->query()
                      ->fetchColumn();

              if (empty($contentPageId)) {
                //INSERT MAIN-MIDDLE CONTAINER
                $insertMainItem = $coreContentTable->createRow();
                $insertMainItem->$pageId = $getPageId;
                $insertMainItem->type = 'widget';
                $insertMainItem->name = 'sitepageintegration.profile-items';
                $insertMainItem->parent_content_id = $getMiddleContentId;
                $insertMainItem->order = 999;
                $insertMainItem->params = $params;
                $insertMainItem->save();
              }
            }
          }
        }
      }
    }
  }
  
  public function pluginIntegration($globalFormValue, $titleName, $resourceType, $moduleName, $resource_id) {

  		$coreTable = Engine_Api::_()->getDbtable('pages', 'core');
		$coreTableName = $coreTable->info('name');
		$coreContentTable = Engine_Api::_()->getDbtable('content', 'core');
		$coreContentTableName = $coreContentTable->info('name');
		$adminContentTable = Engine_Api::_()->getDbtable('admincontent', 'sitepage');
		$adminContentTableName = $adminContentTable->info('name');
		$contentPageTable = Engine_Api::_()->getDbtable('contentpages', 'sitepage');
		$contentPageTableName = $contentPageTable->info('name');
		$contentSitepageTable = Engine_Api::_()->getDbtable('content', 'sitepage');
		$contentSitepageTableName = $contentSitepageTable->info('name');
		$db = Engine_Db_Table::getDefaultAdapter();
		
		if (isset($_POST["$globalFormValue"]) && $_POST["$globalFormValue"] == '0') {
			$db->query("UPDATE `engine4_sitepageintegration_mixsettings` SET `enabled` = '0' WHERE `engine4_sitepageintegration_mixsettings`.`resource_type` = '$resourceType' LIMIT 1 ;");
		} else {
			$this->setDefaultData($coreTable, $coreTableName, $coreContentTable, $coreContentTableName, 'page_id', 'content_id', null, "$moduleName", '{"title":"'.$titleName.'","resource_type":"'.$resourceType.'","nomobile":"0","name":"sitepageintegration.profile-items"}', "$resourceType");

			$this->setDefaultData($coreTable, $coreTableName, $adminContentTable, $adminContentTableName, 'page_id', 'admincontent_id', null, "$moduleName", '{"title":"'.$titleName.'","resource_type":"'.$resourceType.'","nomobile":"0","name":"sitepageintegration.profile-items"}', "$resourceType");

			$totalPages = $contentPageTable->select()
											->from($contentPageTableName, array('count(*) as count'))
											->where('name =?', 'sitepage_index_view')->query()->fetchColumn();

			$limit = 1000;
			$reload_count = round($totalPages / $limit);
			$page_reload = $this->_getParam('page_reload', 1);
			$offset = ($page_reload - 1) * $limit;

			//page profile page
			$select = new Zend_Db_Select($db);
			$select
							->from('engine4_sitepage_contentpages', array('page_id'))
							->where('name = ?', 'sitepage_index_view')
							->limit($limit, $offset);
			$results = $select->query()->fetchAll();

			foreach ($results as $result) {
				$this->setDefaultData($contentPageTable, $contentPageTableName, $contentSitepageTable, $contentSitepageTableName, 'contentpage_id', 'content_id', $result['page_id'], "$moduleName", '{"title":"'.$titleName.'","resource_type":"'.$resourceType.'","nomobile":"0","name":"sitepageintegration.profile-items"}', "$resourceType");
			}
      
      if(Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled($moduleName)) {
				$db->query("INSERT IGNORE INTO `engine4_sitepageintegration_mixsettings` (`module`, `resource_type`, `resource_id`, `item_title`, `enabled`) VALUES ('$moduleName', '$resourceType', '$resource_id', '$titleName', 1);");
			}

			$db->query("UPDATE `engine4_sitepageintegration_mixsettings` SET `enabled` = '1' WHERE `engine4_sitepageintegration_mixsettings`.`resource_type` = '$resourceType' LIMIT 1 ;");
		}
  }

}