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
class Sitepagereview_Widget_SitemobileProfileSitepagereviewsController extends Engine_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {

    //GET VIEWER INFO
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //SET NO RENDER IF NO SUBJECT
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    $this->view->sitepage = $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');;
    $this->view->page_id = $sitepage->page_id;
    if (!empty($viewer_id)) {
      $this->view->level_id = $viewer->level_id;
    } else {
      $this->view->level_id = 0;
    }

    //TOTAL REVIEW
    $reviewCount = Engine_Api::_()->sitepage()->getTotalCount($this->view->page_id, 'sitepagereview', 'reviews');   
    $level_allow = Engine_Api::_()->authorization()->getPermission($this->view->level_id, 'sitepagereview_review', 'create');
    if (empty($level_allow) && empty($reviewCount) && !(Engine_Api::_()->sitepage()->showTabsWithoutContent())) {
      return $this->setNoRender();
    }    

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }
    //END MANAGE-ADMIN CHECK

		//GET REVIEW TABLE
		$reviewTable = Engine_Api::_()->getDbTable('reviews', 'sitepagereview');

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
    $this->view->totalReviews = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->totalReviews($sitepage->page_id);
    $this->view->ratingData = $ratingData = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->ratingbyCategory($sitepage->page_id);
		$this->view->paginator = $paginator = $reviewTable->pageReviews($sitepage->page_id);
		$this->view->paginator->setItemCountPerPage(10)->setCurrentPageNumber($this->_getParam('page', 1));
		$this->_childCount = $paginator->getTotalItemCount();

    //SET NO RENDER IF NO REVIEW CORROSPONDING TO THIS PAGE ID
    $noReviewCheck =  Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->getAvgRecommendation($sitepage->page_id);
		if (!empty($noReviewCheck)) {
			$this->view->noReviewCheck = $noReviewCheck->toArray();
			if($this->view->noReviewCheck)
			$this->view->recommend_percentage = round($noReviewCheck[0]['avg_recommend'] * 100, 3);
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