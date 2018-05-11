<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Widget_ReviewContentController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {
//GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    if (!empty($viewer_id)) {
      $this->view->level_id = $viewer->level_id;
    } else {
      $this->view->level_id = 0;
    }

    //GET REVIEW MODEL
    $this->view->sitepagereview = $sitepagereview = Engine_Api::_()->getItem('sitepagereview_review', Zend_Controller_Front::getInstance()->getRequest()->getParam('review_id'));
    if (empty($sitepagereview)) {
      return $this->setNoRender();
    }

    $this->view->page_id = $page_id = $sitepagereview->page_id;

    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);

    $this->view->sitepage_slug = $sitepage->getSlug();
    $this->view->tab_selected_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab');

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'comment');
    if (empty($isManageAdmin)) {
      $this->view->can_comment = 0;
    } else {
      $this->view->can_comment = 1;
    }
    //END MANAGE-ADMIN CHECK
    //GET OWNER INFORMATION
    $this->view->owner = $owner = $sitepagereview->getOwner();

    //INCREMENT IN NUMBER OF VIEWS
    if (!$owner->isSelf($viewer)) {
      $sitepagereview->view_count++;
      $sitepagereview->save();
    }

    //REPORT CODE
    $this->view->review_report = $review_report = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.report', 1);
    if (!empty($viewer_id) && $review_report == 1) {
      $report = $this->view->report = $sitepagereview;
    }

    // Start: "Suggest to Friends" link work.
    $page_flag = 0;
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $is_suggestion_enabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('suggestion');
    $is_moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepage');
    $isSupport = Engine_Api::_()->getApi('suggestion', 'sitepage')->isSupport();
    if (!empty($is_suggestion_enabled)) {
      // Here we are delete this review suggestion if viewer have.
      if (!empty($is_moduleEnabled)) {
        Engine_Api::_()->getApi('suggestion', 'sitepage')->deleteSuggestion($viewer_id, 'page_review', Zend_Controller_Front::getInstance()->getRequest()->getParam('review_id'), 'page_review_suggestion');
      }

      $SuggVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('suggestion')->version;
      $versionStatus = strcasecmp($SuggVersion, '4.1.7p1');
      if ($versionStatus >= 0) {
        $modContentObj = Engine_Api::_()->suggestion()->getSuggestedFriend('sitepagereview', Zend_Controller_Front::getInstance()->getRequest()->getParam('review_id'), 1,null);
        if (!empty($modContentObj)) {
          $contentCreatePopup = @COUNT($modContentObj);
        }
      }

      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.package.enable', 1)) {
        if ($sitepage->expiration_date <= date("Y-m-d H:i:s")) {
          $page_flag = 1;
        }
      }
      if (!empty($contentCreatePopup) && !empty($isSupport) && empty($sitepage->closed) && !empty($sitepage->approved) && empty($sitepage->declined) && !empty($sitepage->draft) && empty($page_flag) && !empty($viewer_id) && !empty($is_suggestion_enabled)) {
        $this->view->reviewSuggLink = Engine_Api::_()->suggestion()->getModSettings('sitepage', 'review_sugg_link');
      }
      // End: "Suggest to Friends" link work.
    }
  }

}
?>