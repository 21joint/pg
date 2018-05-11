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
class Sitepageoffer_Widget_SitemobileProfileSitepageoffersController extends Engine_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {

    //DON'T RENDER IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //GET SUBJECT
    $this->view->sitepage = $sitepage = $subject = Engine_Api::_()->core()->getSubject('sitepage_page');

    //GET PAGE ID
    $page_id = $subject->page_id;

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
    
    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }

    $can_edit = 1;
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
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
    $offerCount = Engine_Api::_()->sitepage()->getTotalCount($page_id, 'sitepageoffer', 'offers');     
    if (empty($can_create_offer) && empty($offerCount) && !(Engine_Api::_()->sitepage()->showTabsWithoutContent())) {
      return $this->setNoRender();
    }       

		$this->view->paginator = $paginator = Engine_Api::_()->getItemTable('sitepageoffer_offer')->getSitepageoffersPaginator($page_id, 1,'',$can_create_offer);
		$this->view->paginator->setItemCountPerPage(10)->setCurrentPageNumber($this->_getParam('page', 1));
    $this->_childCount = $paginator->getTotalItemCount();
  }

  public function getChildCount() {
    return $this->_childCount;
  }

}