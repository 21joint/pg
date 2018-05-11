<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Widget_ProfileItemsController extends Seaocore_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {

    //DON'T RENDER IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    //GET THE RESOURCE TYPE.
    $this->view->resource_type = $resource_type = $this->_getParam('resource_type', null);
    
    if (empty($resource_type)) {
      return $this->setNoRender();
    }
    
    $pieces = explode("_", $resource_type);
    if ($resource_type == 'document_0' || $resource_type == 'folder_0' || $resource_type == 'quiz_0') {
      $this->view->listingTypeId = $listingTypeId = $pieces[1];
      $resource_type = $pieces[0];
    } else { 
      $this->view->listingTypeId = $listingTypeId = $pieces[2];
      $resource_type = $pieces[0] . '_' . $pieces[1];
    }

    $sub_status_select = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration')->fetchRow(array('resource_type = ?' => $resource_type . '_' . $listingTypeId, 'enabled = ?' => 1));
    if (empty($sub_status_select)) {
      return $this->setNoRender();
    }
        
    //GET SUBJECT AND PAGE ID.
    $this->view->sitepage = $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');

    //PACKAGE BASE AND MEMBER LEVEL SETTINGS PRIYACY START
    $sitepageintegrationPageOwner = Zend_Registry::isRegistered('sitepageintegrationPageOwner') ? Zend_Registry::get('sitepageintegrationPageOwner') : null;
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", $resource_type. '_' . $listingTypeId)) {
        return $this->setNoRender();
      }
    } else {
      $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, $resource_type . '_' . $listingTypeId);
      if (empty($isPageOwnerAllow)) {
        return $this->setNoRender();
      }
    }

    if (empty($sitepageintegrationPageOwner)) {
      return $this->setNoRender();
    }
    
    $this->view->title_truncation = $this->_getParam('title_truncation', 70);
    $this->view->show_posted_date = $this->_getParam('show_posted_date', 0);    
    
    $request = Zend_Controller_Front::getInstance()->getRequest();
    
    if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {

      //GET LAYOUT
      $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);

      //GET THE THIRD TYPE LAYOUT
      $this->view->widgets = $widgets = Engine_Api::_()->sitepage()->getwidget($layout, $sitepage->page_id);
      //SHOWING THE TOP TITLE IN CASE OF NON TABBED LAYOUT
      $this->view->showtoptitle = Engine_Api::_()->sitepage()->showtoptitle($layout, $sitepage->page_id);
      //GET THE CURRENT TAB ID
      $this->view->module_tabid = $currenttabid = $request->getParam('tab', null);

      //$this->view->content_id = $request->getParam('tab', null);

      if ($request->getParam('resource_type', null)) {
        $this->view->content_id = $request->getParam('tab', null);
        $this->view->change_url = 1;
      } else {
        $this->view->content_id = Engine_Api::_()->sitepage()->getTabIdInfoIntegration('sitepageintegration.profile-items', $sitepage->page_id, $layout);
        $this->view->change_url = 0;
      }
      //REQUEST TYPE IS AJAX OR NOT
      $this->view->isajax = $isajax = $this->_getParam('isajax', null);
      if (!empty($isajax)) {
        $this->getElement()->removeDecorator('Title');
        $this->getElement()->removeDecorator('Container');
        //$this->getElement()->removeDecorator('Class');
      }
    } else {
      $this->view->resource_type = $resource_type = $this->_getParam('resource_type', null);
      $currenttabid = $this->view->identity;
    }

    //SHOWING THE NOTES
    if (!empty($isajax) || ($currenttabid == $this->view->identity ) || ($widgets == 0)) {
        
      if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {  
        $Params = Engine_Api::_()->sitepageintegration()->integrationParams($resource_type, $listingTypeId, $sitepage->page_id);
        if(isset($Params['create_privacy']))
        $this->view->createPrivacy =  $Params['create_privacy'] ;

        if($resource_type == 'list_listing') {
          //RATING IS ENABLED OR NOT
          $this->view->ratngShow = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('list.rating', 1);
        } elseif($resource_type == 'sitereview_listing') {
          $this->view->listingType = Engine_Api::_()->getItem('sitereview_listingtype', $listingTypeId);
        }
        $this->view->mixSettingsResults = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration')->getIntegrationItems(); 
      }

      $this->view->search = $search = $this->_getParam('search');
      $this->view->selectbox = $selectbox = $this->_getParam('selectbox');
      if (!empty($selectbox)) {
        $params['orderby'] = $selectbox;
      } /* else {
        $params['orderby'] = 'creation_date';
        } */

      if (!empty($search)) {
        $params['search'] = $search;
      }

      //GET THE TAB ID
      $this->view->identity_temp = $request->getParam('identity_temp', $currenttabid);

      $this->view->show_content = true;

      $this->view->resource_type = $resource_type = $request->getParam('resource_type', $resource_type);
      $this->view->title_truncation = $request->getParam('title_truncation', 70);
      $this->view->show_posted_date = $request->getParam('show_posted_date', 0);

      $pieces = explode("_", $resource_type);
      if ($resource_type == 'document_0' || $resource_type == 'folder_0' || $resource_type == 'quiz_0') {
        $this->view->listingTypeId = $params['listingtype_id'] = $listingTypeId = $pieces[1];
        $params['resource_type'] = $resource_type = $pieces[0];
      }	else {
        if(isset($pieces[2]))
        $this->view->listingTypeId = $params['listingtype_id'] = $listingTypeId = $pieces[2];
        $params['resource_type'] = $resource_type = $pieces[0] . '_' . $pieces[1];
      }

      $params['page_id'] = $sitepage->page_id;

      $this->view->contentResults = $paginator = Engine_Api::_()->getDbtable('contents', 'sitepageintegration')->getResults($params);

      //ADD COUNT TO TITLE IF CONFIGURED
      if ($paginator->getTotalItemCount() > 0) {
        $this->_childCount = $paginator->getTotalItemCount();
      }
      $paginator->setItemCountPerPage('300');
    } else {

      //SHOWING THE CONTENT NOT BY THE AJAX
      $this->view->show_content = false;

      //GET THE TAB ID
      $this->view->identity_temp = $this->view->identity;
      $params['resource_type'] = $resource_type;
      $params['listingtype_id'] = $listingTypeId;
      $params['page_id'] = $sitepage->page_id;

      $paginator = Engine_Api::_()->getDbtable('contents', 'sitepageintegration')->getResults($params);

      //ADD COUNT TO TITLE IF CONFIGURED
      if ($paginator->getTotalItemCount() > 0) {
        $this->_childCount = $paginator->getTotalItemCount();
      }

      // Do not render if nothing to show
      if ($paginator->getTotalItemCount() <= 0) {
        return $this->setNoRender();
      }
    }
  }

  public function getChildCount() {
    return $this->_childCount;
  }

}