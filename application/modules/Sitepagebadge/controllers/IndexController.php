<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_IndexController extends Seaocore_Controller_Action_Standard {

  //ACTION FOR SHOW BADGE LISTING
  public function showbadgesAction() {
      
    //CHECK VIEW PRIVACY
    if (!$this->_helper->requireAuth()->setAuthParams('sitepage_page', null, 'view')->isValid())
      return;

    //NAVIGATION WORK FOR FOOTER.(DO NOT DISPLAY NAVIGATION IN FOOTER ON VIEW PAGE.)
    if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
         if(!Zend_Registry::isRegistered('sitemobileNavigationName')){
         Zend_Registry::set('sitemobileNavigationName','setNoRender');
         }
    }
    
    //CHECK THE VERSION OF THE CORE MODULE
    $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if ($coreversion < '4.1.0') {
      $this->_helper->content->render();
    } else {
      $this->_helper->content
              ->setNoRender()
              ->setEnabled()
      ;
    }
  }

  //ACTION FOR BADGE REQUEST
  public function badgerequestAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepage_main');

    //GET PAGE ITEM
    $this->view->page_id = $page_id = $this->_getParam('page_id', null);
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);

    $this->view->is_ajax = $this->_getParam('is_ajax', '');

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'badge');
    if (empty($isManageAdmin)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }

    $sitepageBadgeHostName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
    //END MANAGE-ADMIN CHECK

    $this->view->sitepages_view_menu = 21;

    //CHECK THE THE PREVIOUS BADGE REQUEST STATUS IF REQUESTED
		$this->view->previous_request_status = Engine_Api::_()->getDbtable('badgerequests', 'sitepagebadge')->badgeRequestStatus($page_id);

    $this->view->sitepagebadges_value = Engine_Api::_()->getApi('settings', 'core')->sitepagebadge_badgeprofile_widgets;

    if ($sitepage->badge_id) {
      $this->view->sitepagebadge = Engine_Api::_()->getItem('sitepagebadge_badge', $sitepage->badge_id);
    }

    $this->view->page_id = $page_id = $this->_getParam('page_id');

    //GET PAGE ITEM
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    $this->view->previous_badge_id = $previous_badge_id = $sitepage->badge_id;

    //SHOW BADGE OPTIONS FOR SENDING A REQUEST
    $this->view->badgeData = $badgeData = Engine_Api::_()->getDbTable('badges', 'sitepagebadge')->getBadgesData($params = array());
    $this->view->badgeCount = Count($badgeData);

    if ($this->getRequest()->isPost()) {

      //FORM VALIDATION WORK
      $this->view->posted_value = array();
      $this->view->posted_value = $_POST;
      $this->view->error_contactno = 0;
      $this->view->error_comment = 0;
      $this->view->error_badge_id = 0;
      $this->view->error_same_badge = 0;
      $this->view->form_error = 0;

      if (empty($_POST['contactno'])) {
        $this->view->error_contactno = 1;
        $this->view->form_error = 1;
        //return;
      }

      if (empty($_POST['user_comment'])) {
        $this->view->error_comment = 1;
        $this->view->form_error = 1;
        //return;
      }

      $isModType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.set.type', 0);
      if (empty($isModType)) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagebadge.link.type', convert_uuencode($sitepageBadgeHostName));
      }

      if (empty($_POST['badge_id'])) {
        $this->view->error_badge_id = 1;
        $this->view->form_error = 1;
        //return;
      } elseif ($_POST['badge_id'] == $previous_badge_id) {
        $this->view->error_same_badge = 1;
        $this->view->form_error = 1;
        //return;
      }

      if ($this->view->form_error == 1) {
        return;
      }

      //END FORM VALIDATION WORK
      Engine_Api::_()->sitepagebadge()->setBadgePackages();
      $tableBadgeRequest = Engine_Api::_()->getItemTable('sitepagebadge_badgerequest');
      $db = $tableBadgeRequest->getAdapter();
      $db->beginTransaction();

      try {
        $values = $_POST;
        $badgerequest = $tableBadgeRequest->createRow();
        $badgerequest->setFromArray($values);
        $badgerequest->page_id = $page_id;
        $badgerequest->status = 3;
        $badgerequest->save();

        // Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      return $this->_helper->redirector->gotoRoute(array('page_id' => $page_id), 'sitepagebadge_request', true);
    }
  }

  //ACTION FOR REMOVE BADGE
  public function removeAction() {

		//GET VIEWER DETAIL
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    //CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid())
      return;

		$page_id = $this->view->page_id = $this->getRequest()->getParam('page_id');
    $page = Engine_Api::_()->getItem('sitepage_page', $page_id);

		//START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($page, 'badge');
    if (empty($isManageAdmin)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($page, 'edit');
    if (empty($isManageAdmin)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }
    //END MANAGE-ADMIN CHECK

    //SMOOTHBOX
    if (null === $this->_helper->ajaxContext->getCurrentContext()) {
      $this->_helper->layout->setLayout('default-simple');
    } else {//NO LAYOUT
      $this->_helper->layout->disableLayout(true);
    }

    if (!$this->getRequest()->isPost())
      return;

		//REMOVE BADGE ENTRY IN DATABASE 
    if ($viewer_id) { 
      $this->view->permission = true;
      $this->view->success = false;
      $db = Engine_Api::_()->getDbtable('pages', 'sitepage')->getAdapter();
      $db->beginTransaction();
      try { 
        $page->badge_id = 0;
        $page->save();
        $db->commit();
        $this->view->success = true;
      } catch (Exception $e) {
        $db->rollback();
        throw $e;
      }
    } 
		else {
      $this->view->permission = false;
    }

    $this->_forwardCustom('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('')
    ));
  }
}
?>