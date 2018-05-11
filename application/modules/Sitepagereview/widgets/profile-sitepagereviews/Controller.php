<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2010-12-31 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Widget_ProfileSitepagereviewsController extends Engine_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {

    //GET VIEWER INFO
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //SET NO RENDER IF NO SUBJECT
    if (!Engine_Api::_()->core()->hasSubject('sitepage_page')) {
      return $this->setNoRender();
    }

//		$this->view->page_url = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_url', null);

    //GET SUBJECT
    $this->view->sitepage = $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');

    if (!empty($viewer_id)) {
      $this->view->level_id = $viewer->level_id;
    } else {
      $this->view->level_id = 0;
    }

    //TOTAL REVIEW
    $reviewCount = Engine_Api::_()->sitepage()->getTotalCount($sitepage->page_id, 'sitepagereview', 'reviews');   
    $level_allow = Engine_Api::_()->authorization()->getPermission($this->view->level_id, 'sitepagereview_review', 'create');
    if (empty($level_allow) && empty($reviewCount) && !(Engine_Api::_()->sitepage()->showTabsWithoutContent())) {
      return $this->setNoRender();
    }    

//    //START MANAGE-ADMIN CHECK
//    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
//    if (empty($isManageAdmin)) {
//      return $this->setNoRender();
//    }
//    //END MANAGE-ADMIN CHECK

    $sitepagereview_isProfile = Zend_Registry::isRegistered('sitepagereview_isProfile') ? Zend_Registry::get('sitepagereview_isProfile') : null;
    if (empty($sitepagereview_isProfile)) {
      return $this->setNoRender();
    }

//		//GET REVIEW TABLE
//		$reviewTable = Engine_Api::_()->getDbTable('reviews', 'sitepagereview');
//
//    //START TOP SECTION FOR OVERALL RATING AND IT'S PARAMETER
//    $noReviewCheck = $reviewTable->getAvgRecommendation($sitepage->page_id);
//
//    if (!empty($noReviewCheck)) {
//			$this->view->noReviewCheck = $noReviewCheck->toArray();
//      $this->view->recommend_percentage = round($noReviewCheck[0]['avg_recommend'] * 100, 3);
//    }
//
//    $this->view->ratingDataTopbox = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->ratingbyCategory($sitepage->page_id);
//    //END TOP SECTION FOR OVERALL RATING AND IT'S PARAMETER

    //AJAX AND LAYOUT WORK
    $this->view->getPackageReviewView = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepagereview');
    $this->view->module_tabid = $currenttabid = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab', null);
    $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
    $this->view->widgets = $widgets = Engine_Api::_()->sitepage()->getwidget($layout, $sitepage->page_id);
    $this->view->content_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $sitepage->page_id, $layout);
    $isajax = $this->_getParam('isajax', null);
    $this->view->isajax = $isajax;
    $this->view->showtoptitle = $showtoptitle = Engine_Api::_()->sitepage()->showtoptitle($layout, $sitepage->page_id);
    if (!empty($isajax) || ($currenttabid == $this->view->identity) || ($widgets == 0)) {
      $this->view->identity_temp = Zend_Controller_Front::getInstance()->getRequest()->getParam('identity_temp', $currenttabid);
      $this->view->show_content = true;
      
		//GET REVIEW TABLE
		$reviewTable = Engine_Api::_()->getDbTable('reviews', 'sitepagereview');

    //START TOP SECTION FOR OVERALL RATING AND IT'S PARAMETER
    $noReviewCheck = $reviewTable->getAvgRecommendation($sitepage->page_id);

		if (!empty($noReviewCheck)) {
			$this->view->noReviewCheck = $noReviewCheck->toArray();
			if($this->view->noReviewCheck)
			$this->view->recommend_percentage = round($noReviewCheck[0]['avg_recommend'] * 100, 3);
		}

    $this->view->ratingDataTopbox = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->ratingbyCategory($sitepage->page_id);
    //END TOP SECTION FOR OVERALL RATING AND IT'S PARAMETER      

			//CHECK THAT VIEWER IS POSTED REVIEW OR NOT
      $hasPosted = $reviewTable->canPostReview($sitepage->page_id, $viewer_id);
			//$level_allow = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitepagereview_review', 'create');
      if (empty($hasPosted) && !empty($viewer_id) && !empty($level_allow)) {
        $this->view->can_create = 1;
      } else {
        $this->view->can_create = 0;
      }

      //START MANAGE ADMIN AND PAGE-OWNER CAN NOT RATE & REVIEW
      $manageadmin_id = Engine_Api::_()->sitepagereview()->adminCantReview($sitepage->page_id, $viewer_id);
      $this->view->is_manageadmin = 0;
      if (!empty($manageadmin_id)) {
        $this->view->is_manageadmin = 1;
      }
      //START MANAGE ADMIN AND PAGE-OWNER CAN NOT RATE & REVIEW

      $delete_id = $this->_getParam('delete_id');
      if (!empty($delete_id)) {
        $this->delete($delete_id);
      }

      $this->view->paginator = $paginator = $reviewTable->pageReviews($sitepage->page_id);
      $paginator->setItemCountPerPage(50);
      $this->view->current_page = $this->_getParam('page');
      $this->view->paginator = $paginator->setCurrentPageNumber($this->_getParam('page'));
      $this->view->totalReviews = $paginator->getTotalItemCount();

			//ADD COUNT TO TITLE IF CONFIGURED
      if ($this->_getParam('titleCount', false) && $paginator->getTotalItemCount() > 0) {
        $this->_childCount = $paginator->getTotalItemCount();
      } 
    } else {
      $this->view->show_content = false;
      $this->view->identity_temp = $this->view->identity;
      $this->_childCount = $reviewCount;
    }
  }

  public function getChildCount() {
    return $this->_childCount;
  }

  //DELETE REVIEW
  public function delete($id) {
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $review = Engine_Api::_()->getItem('sitepagereview_review', $id);

      //DELETE REVIEW FROM DATABASE
      $review->delete();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

}
?>