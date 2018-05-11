<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_IndexController extends Core_Controller_Action_Standard {

  public function indexAction() {
    //CHECK PERMISSION FOR VIEW.
    if (!$this->_helper->requireUser()->isValid())
      return;

    //GET NAVIGATION.
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepage_main');

    //GETTING THE VIEWER ID
    $this->view->viewer_id = $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    //GETTING THE OBJECT AND PAGE ID.
    $this->view->page_id = $page_id = $this->_getParam('page_id', null);
    $current_viewer = Engine_Api::_()->user()->getViewer();
    $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
    $this->view->resource_type = $resource_type = $this->_getParam('resource_type', null);
    $this->view->listingtype_id = $listingtype_id = $this->_getParam('listingtype_id', null);
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
		$this->view->sitepages_view_menu = 12;

		$statusSelect = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration')->fetchRow(array('resource_type = ?' => $resource_type . '_' . $listingtype_id, 'enabled' => 1));
		
		$this->view->item_title = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration')->getItemsTitle($resource_type, $listingtype_id);
		
    if (empty($statusSelect)) {
      return $this->_forward('requireauth', 'error', 'core');
    }
    
    $params = array();
    $params['resource_type'] = $resource_type;
    $params['page_id'] = $page_id;
    $params['listingtype_id'] = $listingtype_id;
    $params['action'] = 'index';
    
    $Params = Engine_Api::_()->sitepageintegration()->integrationParams($resource_type, $listingtype_id);
    $this->view->createPrivacy =  $Params['create_privacy'] ;
    
//     if($resource_type == 'sitebusiness_business' || $resource_type == 'sitegroup_group' || $resource_type == 'document' || $resource_type == 'folder' || $resource_type == 'quiz') {
// 			$this->view->createPrivacy =  Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, 'create');
//     } else {
// 			$this->view->createPrivacy = 	Engine_Api::_()->authorization()->isAllowed($resource_type, $viewer, "create_listtype_$listingtype_id");
//     } 
  
    $this->view->results = Engine_Api::_()->sitepageintegration()->getContentCount($params);

    $this->view->contentResults = Engine_Api::_()->getDbtable('contents', 'sitepageintegration')->getResults($params);

    $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitepageintegration');

    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      $resource_id = $values['resource_id'];
      $resource_owner_id = $values['owner_id'];
      $row = $contentsTable->createRow();
      $row->owner_id = $viewer_id;
      $row->resource_owner_id = $resource_owner_id;
      $row->page_id = $page_id;
			$row->resource_type = $resource_type;
      $row->resource_id = $resource_id;
      $row->save();
      
      if ($resource_owner_id != $viewer) {
				$resource = Engine_Api::_()->getItem($resource_type, $resource_id);
				$owner = $resource->getOwner();
				
				if ($resource_type == 'sitereview_listing') {
				
					$sitereview_listtype = Engine_Api::_()->getItem('sitereview_listingtype', $listingtype_id);
					$text = $sitereview_listtype['language_phrases']['text_listing'];
					
					$listingtype_id = $resource->listingtype_id;
					$item_title = strtolower(Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->getListingTypeColumn($listingtype_id, 'title_singular'));
					
				} else {
					$item_title = $resource->getShortType();
				}

				$item_title_url = $resource->getHref();
				$item_title_baseurl = 'http://' . $_SERVER['HTTP_HOST'] . $item_title_url;
				if ($resource_type == 'sitereview_listing') { 
					$item_title_link = "<a href='$item_title_baseurl'>" . $item_title . "</a>" . ' '. strtolower($text);
				} else {
					$item_title_link = "<a href='$item_title_baseurl'>" . $item_title . "</a>";
				}
				
				Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $current_viewer, $sitepage, 'integration_sitepage_page', array('linkname' => "$item_title_link"));
			}
			
    }
  }

  //ACTION FOR RESOURCE TYPE AUTO-SUGGEST LIST
  public function manageAutoSuggestAction() {

    //USER VALIDATION
    if (!$this->_helper->requireUser()->isValid())
      return;

    //GETTING THE VIEWER ID.
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $page_id = $this->_getParam('page_id', $this->_getParam('id', null));
    $resource_type = $this->_getParam('resource_type', $this->_getParam('resource_type', null));
    $listingtype_id = $this->_getParam('listingtype_id', $this->_getParam('listingtype_id', null));
    $searchText = $this->_getParam('text', null);

//     switch($resource_type) {
// 			case 'pennyauction_product' :
// 			case 'groupbuy_deal':
// 			case 'folder':
// 			case 'quiz':
// 			 $resourceuser_id = 'user_id';
// 			break;
// 			default:
// 			 $resourceuser_id = 'owner_id';
// 			break;
// 		}
		
		$Params = Engine_Api::_()->sitepageintegration()->integrationParams($resource_type, $listingtype_id);
		$resourceuser_id = $Params['resourceuser_id'];
		
    $resourceTypeTable = Engine_Api::_()->getItemTable($resource_type); 
    $primaryId = current($resourceTypeTable->info("primary"));
    
    $params = array();
    $params['resource_type'] = $resource_type;
    $params['page_id'] = $page_id;
    $params['listingtype_id'] = $listingtype_id;
    $params['resourceuser_id'] = $resourceuser_id;
    $params['searchText'] = $searchText;

    $resourceTypeResults = Engine_Api::_()->sitepageintegration()->getAutoSuggetContents($params);

    $data = array();

    foreach ($resourceTypeResults as $contentTypeList) {

      $content_photo = $this->view->itemPhoto($contentTypeList, 'thumb.icon');

      $content_array['owner_id'] = $contentTypeList->$resourceuser_id;
      $content_array['id'] = $contentTypeList->$primaryId;
      if ($resource_type == 'document') {
				$content_array['label'] = $contentTypeList->document_title;
      } else {
				$content_array['label'] = $contentTypeList->title;
      }
      $content_array['photo'] = $content_photo;
      if ($contentTypeList->getType() == 'sitereview_listing') {
				$content_array['listingtype_id'] = $contentTypeList->listingtype_id;
			}
			$data[] = $content_array;
    }

    if ($this->_getParam('sendNow', true)) {
      //RETURN TO THE RETRIVE RESULT.
      return $this->_helper->json($data);
    } else {
      $this->_helper->viewRenderer->setNoRender(true);
      $data = Zend_Json::encode($data);
      $this->getResponse()->setBody($data);
    }
  }

  //THIS ACTION FOR DELETE CONTENT ID AND RESOURCE TYPE.
  public function deleteAction() {

    //GET THE CONTENT ID AND RESOURCE TYPE.
    $content_id = (int) $this->_getParam('content_id');
    $resource_type = $this->_getParam('resource_type');
    Engine_Api::_()->getDbtable('contents', 'sitepageintegration')->delete(array('content_id = ?' => $content_id, 'resource_type = ?' => $resource_type));
    exit();
  }
  
  public function storeintegrationAction() {
  
		$page_id = $this->_getParam('resource_id', null);
		$sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
		$owner_id = $sitepage->owner_id; 
		$this->view->form = $form = new Sitepageintegration_Form_Storeintergration(array("ownerId" => $owner_id));

		//PROCESS FORM
		if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) )	{
			$values = $form->getValues(); 
			$store_id= $values['store_id'];
			$this->_forward('success', 'utility', 'core', array(
				'smoothboxClose' => 500,
				'parentRedirect' => $this->_helper->url->url(array('action' => 'create', 'store_id' => $store_id, 'page_id' => $page_id), 'sitestoreproduct_general', true),
				'parentRedirectTime' => '2',
			));
		}
  }
}