<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Widget_ProfileSitepageoffersController extends Engine_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {

    //DON'T RENDER IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //GET SUBJECT
    $this->view->sitepage = $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');

    // PACKAGE BASE PRIYACY START
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepageoffer")) {
        return $this->setNoRender();
      }
    } else {
      $pageOwnerBase = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'offer');
      if (empty($pageOwnerBase)) {
        return $this->setNoRender();
      }
    }
    // PACKAGE BASE PRIYACY END     
    
//    //START MANAGE-ADMIN CHECK
//    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
//    if (empty($isManageAdmin)) {
//      return $this->setNoRender();
//    }

    $can_edit = 1;
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
      //return $this->_forward('requireauth', 'error', 'core');
    }

    $can_offer = 1;
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
    if (empty($isManageAdmin)) {
      $can_offer = 0;
    }
    //END MANAGE-ADMIN CHECK

    $can_create_offer = '';
    //OFFER CREATION AUTHENTICATION CHECK
    if ($can_edit == 1 && $can_offer == 1) {
      $this->view->can_create_offer = $can_create_offer =  1;
    }

    //TOTAL OFFER
    $offerCount = Engine_Api::_()->sitepage()->getTotalCount($sitepage->page_id, 'sitepageoffer', 'offers');     
    if (empty($can_create_offer) && empty($offerCount) && !(Engine_Api::_()->sitepage()->showTabsWithoutContent())) {
      return $this->setNoRender();
    }       
    
    $var = 1;
//    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('sitepageoffer_offer')->getSitepageoffersPaginator($sitepage->page_id, $var,'',$can_create_offer);
//    if(!empty($paginator)) {
//			$count = $paginator->getTotalItemCount();
//    }
    
    //GETTING TAB ID FROM CONTENT TABLE
    $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
    $this->view->widgets = $widgets = Engine_Api::_()->sitepage()->getwidget($layout, $sitepage->page_id);
    $this->view->content_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $sitepage->page_id, $layout);

    $this->view->module_tabid = $currenttabid = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab', null);
    $isajax = $this->_getParam('isajax', null);
    $this->view->isajax = $isajax;
    $this->view->showtoptitle = $showtoptitle = Engine_Api::_()->sitepage()->showtoptitle($layout, $sitepage->page_id);
    if (!empty($isajax) || ($currenttabid == $this->view->identity) || ($widgets == 0)) {
      $this->view->identity_temp = Zend_Controller_Front::getInstance()->getRequest()->getParam('identity_temp', $currenttabid);
      $this->view->show_content = true;

      //MAKE PAGINATOR
      $currentPageNumber = $this->_getParam('page', 1);
      $var = 1;
      $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('sitepageoffer_offer')->getSitepageoffersPaginator($sitepage->page_id, $var,'',$can_create_offer);
      $this->_childCount = $paginator->getTotalItemCount();
      $paginator->setItemCountPerPage(10)->setCurrentPageNumber($currentPageNumber);
    } else {
      $this->view->show_content = false;
      $this->view->identity_temp = $this->view->identity;
      $var = 1;
      $show_count = 1;
      $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('sitepageoffer_offer')->getSitepageoffersPaginator($sitepage->page_id, $var, $show_count,$can_create_offer);
      $this->_childCount = $paginator->getTotalItemCount();
    }

    // START: "SUGGEST TO FRIENDS" LINK WORK.
    $page_flag = 0;
    $is_suggestion_enabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('suggestion');
    $this->view->is_moduleEnabled = $is_moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepage');
		$isModuleInfo = Engine_Api::_()->getDbtable('modules', 'core')->getModule('suggestion');
    $isSupport = Engine_Api::_()->getApi('suggestion', 'sitepage')->isSupport();
		
    // HERE WE ARE DELETE THIS POLL SUGGESTION IF VIEWER HAVE.

    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.package.enable', 1)) {
      if ($sitepage->expiration_date <= date("Y-m-d H:i:s")) {
        $page_flag = 1;
      }
    }

    if (!empty($viewer_id) && !empty($isSupport) && empty($sitepage->closed) && !empty($sitepage->approved) && empty($sitepage->declined) && !empty($sitepage->draft) && empty($page_flag) && !empty($is_suggestion_enabled) && ($isModuleInfo->version >= '4.1.7p2')) {
      $this->view->offerSuggLink = Engine_Api::_()->suggestion()->getModSettings('sitepage', 'offer_sugg_link');
    }
    // END: "SUGGEST TO FRIENDS" LINE WORK.
  }

  public function getChildCount() {
    return $this->_childCount;
  }

}
?>