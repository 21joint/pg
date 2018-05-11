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
class Sitepagereview_Widget_RatingsSitepagereviewsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    //SET NO RENDER IF NO SUBJECT
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

		$page_url = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_url', null);
		$page_id = Engine_Api::_()->sitepage()->getPageId($page_url);

    //GET OBJECT
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page',$page_id);

//    //START MANAGE-ADMIN CHECK
//    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
//    if (empty($isManageAdmin)) {
//      return $this->setNoRender();
//    }
//    //END MANAGE-ADMIN CHECK

    $sitepagereview_ratingInfo = Zend_Registry::isRegistered('sitepagereview_ratingInfo') ? Zend_Registry::get('sitepagereview_ratingInfo') : null;
    if (empty($sitepagereview_ratingInfo)) {
      return $this->setNoRender();
    }

    //SET NO RENDER IF NO REVIEW CORROSPONDING TO THIS PAGE ID
    $noReviewCheck =  Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->getAvgRecommendation($sitepage->page_id);
		if (empty($noReviewCheck)) {
      return $this->setNoRender();
    }
			if (!empty($noReviewCheck)) {
				$this->view->noReviewCheck = $noReviewCheck->toArray();
				if($this->view->noReviewCheck)
				$this->view->recommend_percentage = round($noReviewCheck[0]['avg_recommend'] * 100, 3);
			}
    //GETTING RATING DATA
    $this->view->ratingData = $ratingData = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->ratingbyCategory($sitepage->page_id);

    if (empty($ratingData)) {
      return $this->setNoRender();
    }

    //GET VIEWER INFO
		$viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

    //CAN CREATE A REVIEW OR NOT
    $hasPosted = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->canPostReview($sitepage->page_id, $viewer_id);
		$level_allow = Engine_Api::_()->authorization()->getPermission($level_id, 'sitepagereview_review', 'create');
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
    //END MANAGE ADMIN AND PAGE-OWNER CAN NOT RATE & REVIEW

    //TOTAL REVIEWS BELONGS TO THIS PAGE
    $this->view->totalReviews = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->totalReviews($sitepage->page_id);

    $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
    $this->view->content_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $sitepage->page_id, $layout);
  }

}
?>