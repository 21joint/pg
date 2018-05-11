<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_IndexController extends Seaocore_Controller_Action_Standard {

  //ACTION FOR POSTING A REVIEW
  public function createAction() {
    $getPackageReviewCreate = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepagereview');
    if ((!$this->_helper->requireUser()->isValid()) || empty($getPackageReviewCreate))
      return;

    //GET VIEWER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    //GET LISITING
    $page_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_id', null);
		
    //GET OBJECT
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page',$page_id);
    $this->view->page_id = $page_id;

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }
    //END MANAGE-ADMIN CHECK

    $sitepagereview_isCraete = Zend_Registry::isRegistered('sitepagereview_isCraete') ? Zend_Registry::get('sitepagereview_isCraete') : null;
    if (empty($sitepagereview_isCraete)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }
    $this->view->page_title_with_link = "<b>$sitepage->title</b>";

    $sitepageModHostName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));

    //SET PROS-CONS AND RECOMMAND FIELD
    $this->view->prefield_recommand = 1;
    $this->view->showProsConsField = $showProsConsField = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.proscons', 1);
    $this->view->showRecommendField = $showRecommendField = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1);

    //VIEWER IS AUTHORIZED OR NOT FOR POSTING A REVIEW
    $hasPosted = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->canPostReview($page_id, $viewer_id);
		$level_allow = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitepagereview_review', 'create');
    if (!empty($hasPosted) || empty($viewer_id) || empty($level_allow)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }

    //START MANAGE ADMIN AND PAGE-OWNER CAN NOT RATE & REVIEW
    $manageadmin_id = Engine_Api::_()->sitepagereview()->adminCantReview($page_id, $viewer_id);
    if (!empty($manageadmin_id)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepage_main');

    //FATCH REVIEW CATEGORIES
    $this->view->reviewCategory = Engine_Api::_()->getDbtable('reviewcats', 'sitepagereview')->reviewParams($sitepage->category_id);
    $this->view->total_reviewcats = Count($this->view->reviewCategory);

    $this->view->tab_selected_id = $this->_getParam('tab');

    if (Engine_Api::_()->seaocore()->isSitemobileApp()) {
          Zend_Registry::set('setFixedCreationForm', true);
          Zend_Registry::set('setFixedCreationFormBack', 'Back');
          Zend_Registry::set('setFixedCreationHeaderTitle', Zend_Registry::get('Zend_Translate')->_('Write a Review'));
          Zend_Registry::set('setFixedCreationHeaderSubmit', Zend_Registry::get('Zend_Translate')->_('Submit'));
          Zend_Registry::set('setFixedCreationFormId', '#sitepage_review_create');
    }
         
    if ($this->getRequest()->isPost()) {

      $getrecommendprofile = Engine_Api::_()->sitepagereview()->getRecommendProfile();
      $this->view->prefield_title = $_POST['title'];
      $this->view->prefield_body = $_POST['body'];
      if (empty($getrecommendprofile)) {
        return;
      }

      $isModType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.set.type', 0);
      if (empty($isModType)) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagereview.view.info', convert_uuencode($sitepageModHostName));
      }

      if (!empty($showRecommendField)) {
        $this->view->prefield_recommand = $_POST['recommend'];
      }

      $this->view->form_error = 0;
      if (empty($_POST['review_rate_0'])) {

        //SHOW PRE-FIELD THE RATINGS IF OVERALL RATING IS EMPTY
        $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

        $this->view->overallrating_required = 1;
        $this->view->form_error = 1;
        //return;
      }

      if (!empty($showProsConsField)) {
        $this->view->prefield_pros = $_POST['pros'];
        $this->view->prefield_cons = $_POST['cons'];

        if (empty($_POST['pros'])) {

          //SHOW PRE-FIELD THE RATINGS IF PROS IS EMPTY
          $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

          $this->view->pros_required = 1;
          $this->view->form_error = 1;
          //return;
        }

        if (empty($_POST['cons'])) {

          //SHOW PRE-FIELD THE RATINGS IF CONS IS EMPTY
          $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

          $this->view->cons_required = 1;
          $this->view->form_error = 1;
          //return;
        }
      }

      if (empty($_POST['title'])) {

        //SHOW PRE-FIELD THE RATINGS IF TITLE IS EMPTY
        $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

        $this->view->title_required = 1;
        $this->view->form_error = 1;
        //return;
      }

      if (empty($_POST['body'])) {

        //SHOW PRE-FIELD THE RATINGS IF BODY IS EMPTY
        $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

        $this->view->body_required = 1;
        $this->view->form_error = 1;
        //return;
      }

      if ($this->view->form_error == 1) {
        return;
      }

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

        $values = $_POST;
        $values['owner_id'] = $viewer_id;
        $values['page_id'] = $sitepage->getIdentity();

        // Create topic
        $reviewTable = Engine_Api::_()->getDbtable('reviews', 'sitepagereview');
        $review = $reviewTable->createRow();
        $review->setFromArray($values);
        $review->view_count = 1;
        $review->save();

        //INCREASE REVIEW COUNT IN PAGE TABLE
        $sitepage->review_count++;
        $sitepage->save();

        //DO ENTRY IN REVIEW RATING TABLE
        foreach ($_POST as $key => $ratingdata) {
          $string_exist = strstr($key, 'review_rate_');
          if ($string_exist) {
            $reviewcat_id = explode('review_rate_', $key);
            $reviewRatingTable = Engine_Api::_()->getDbtable('ratings', 'sitepagereview');
            $reviewRating = $reviewRatingTable->createRow();
            $reviewRating->review_id = $review->review_id;
            $reviewRating->category_id = $sitepage->category_id;
            $reviewRating->page_id = $review->page_id;
            $reviewRating->reviewcat_id = $reviewcat_id[1];
            $reviewRating->rating = $ratingdata;
            $reviewRating->save();
          }
        }

        //UPDATE RATING IN PAGE TABLE
        Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->pageRatingUpdate($review->page_id);

        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $subject = $sitepage;
        $subjectOwner = $subject->getOwner('user');

        //ACTIVITY FEED
        $action = $activityApi->addActivity($viewer, $subject, 'sitepagereview_new');

        if ($action != null) {
          Engine_Api::_()->getApi('subCore', 'sitepage')->deleteFeedStream($action);
          $activityApi->attachActivity($action, $review);
        }

        //SENDING ACTIVITY FEED TO FACEBOOK.
        $enable_Facebooksefeed = $enable_fboldversion = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('facebooksefeed');

        if (!empty($enable_Facebooksefeed)) {

          $review_array = array();
          $review_array['type'] = 'sitepagereview_new';
          $review_array['object'] = $review;

          Engine_Api::_()->facebooksefeed()->sendFacebookFeed($review_array);
        }
      
				//PAGE REIVEW CREATE NOTIFICATION AND EMAIL WORK
				$sitepageVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitepage')->version;
				if ($sitepageVersion >= '4.3.0p1') {
					//Engine_Api::_()->sitepage()->sendNotificationEmail($review, $action, 'sitepagereview_create', 'SITEPAGEREVIEW_CREATENOTIFICATION_EMAIL', 'Pageevent Invite');
// 					$isPageAdmins = Engine_Api::_()->getDbtable('manageadmins', 'sitepage')->isPageAdmins($viewer->getIdentity(), $page_id);
// 						if (!empty($isPageAdmins)) {
// 							//NOTIFICATION FOR ALL FOLLWERS.
// 							Engine_Api::_()->sitepage()->sendNotificationToFollowers($review, $action, 'sitepagereview_create');
// 						}
				}
				
        //COMMENT PRIVACY
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        $commentMax = array_search("everyone", $roles);
        foreach ($roles as $i => $role) {
          $auth->setAllowed($review, $role, 'comment', ($i <= $commentMax));
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      //GO TO SITEPAGE PROFILE PAGE WITH SITEPAGE REVIEW SELECTED TAB
      return $this->_redirectCustom($review->getHref(), array('prependBase' => false));
    }
  }

  //ACTION FOR VIEW THE REVIEW
  public function viewAction() {

    $sitepagereview = Engine_Api::_()->getItem('sitepagereview_review', $this->_getParam('review_id'));
    if (empty($sitepagereview)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }

		if ($sitepagereview) {
			Engine_Api::_()->core()->setSubject($sitepagereview);
		}

    $page_id = $sitepagereview->page_id;
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }
    //END MANAGE-ADMIN CHECK

    //NAVIGATION WORK FOR FOOTER.(DO NOT DISPLAY NAVIGATION IN FOOTER ON VIEW PAGE.)
    if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
         if(!Zend_Registry::isRegistered('sitemobileNavigationName')){
         Zend_Registry::set('sitemobileNavigationName','setNoRender');
         }
    }
    
    //SET BACK ICON FOR APP
    if (Engine_Api::_()->seaocore()->isSitemobileApp()) {
      Zend_Registry::set('setFixedCreationFormBack', 'Back');
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

  //ACTION FOR EDIT A REVIEW
  public function editAction() {
    if (!$this->_helper->requireUser()->isValid())
      return;

    //GET REVIEW AND PAGE INFO
    $page_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_id', null);
		
    //GET OBJECT
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page',$page_id);
    $this->view->page_id = $page_id;
    $review_id = $this->_getParam('review_id');
    $this->view->tab_selected_id = $this->_getParam('tab');
    $review = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
    $this->view->prefield_title = $review->title;
    $this->view->prefield_body = $review->body;
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    //ONLY REVIEW OWNER CAN EDIT THE REVEIW
    if ($review->owner_id != $viewer_id) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }

    $this->view->page_title_with_link = "<b>$sitepage->title</b>";

    //SHOW PROS AND CONS OR NOT
    $this->view->showProsConsField = $showProsConsField = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.proscons', 1);
    $this->view->showRecommendField = $showRecommendField = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1);
    if (!empty($showProsConsField)) {
      $this->view->prefield_pros = $review->pros;
      $this->view->prefield_cons = $review->cons;
    }
    if (!empty($showRecommendField)) {
      $this->view->prefield_recommand = $review->recommend;
    }

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepage_main');

    //GET REIVEW PARAMETERS
    $this->view->reviewCategory = Engine_Api::_()->getDbtable('reviewcats', 'sitepagereview')->reviewParams($sitepage->category_id);
    $this->view->total_reviewcats = Count($this->view->reviewCategory);

    //GET THE REVIEW RATING DATAS
    $this->view->reviewRateData = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->ratingsData($review_id);

    if (Engine_Api::_()->seaocore()->isSitemobileApp()) {
          Zend_Registry::set('setFixedCreationForm', true);
          Zend_Registry::set('setFixedCreationFormBack', 'Back');
          Zend_Registry::set('setFixedCreationHeaderTitle', Zend_Registry::get('Zend_Translate')->_('Edit Your Review'));
          Zend_Registry::set('setFixedCreationHeaderSubmit', Zend_Registry::get('Zend_Translate')->_('Submit'));
          Zend_Registry::set('setFixedCreationFormId', '#sitepage_review_edit');
    }
    //PROCESS FORM
    if ($this->getRequest()->isPost()) {

      $this->view->prefield_title = $_POST['title'];
      $this->view->prefield_body = $_POST['body'];

      if (!empty($showRecommendField)) {
        $this->view->prefield_recommand = $_POST['recommend'];
      }

      if (empty($_POST['review_rate_0'])) {

        //SHOW PRE-FIELD THE RATINGS IF OVERALL RATING IS EMPTY
        $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

        $this->view->overallrating_required = 1;
        $this->view->form_error = 1;
        //return;
      }

      if (!empty($showProsConsField)) {
        $this->view->prefield_pros = $_POST['pros'];
        $this->view->prefield_cons = $_POST['cons'];

        if (empty($_POST['pros'])) {

          //SHOW PRE-FIELD THE RATINGS IF PROS IS EMPTY
          $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

          $this->view->pros_required = 1;
          $this->view->form_error = 1;
          //return;
        }

        if (empty($_POST['cons'])) {

          //SHOW PRE-FIELD THE RATINGS IF CONS IS EMPTY
          $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

          $this->view->cons_required = 1;
          $this->view->form_error = 1;
          //return;
        }
      }

      if (empty($_POST['title'])) {

        //SHOW PRE-FIELD THE RATINGS IF TITLE IS EMPTY
        $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

        $this->view->title_required = 1;
        $this->view->form_error = 1;
        //return;
      }

      if (empty($_POST['body'])) {

        //SHOW PRE-FIELD THE RATINGS IF BODY IS EMPTY
        $this->view->reviewRateData = Engine_Api::_()->sitepagereview()->prefieldRatingData($_POST);

        $this->view->body_required = 1;
        $this->view->form_error = 1;
        //return;
      }

      if ($this->view->form_error == 1) {
        return;
      }
      Engine_Api::_()->sitepagereview()->setReviewPackages();
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $values = $_POST;

        $values['owner_id'] = $viewer_id;
        $values['page_id'] = $sitepage->page_id;
        $values['modified_date'] = date('Y-m-d H:i:s');

        $review->setFromArray($values);
        $review->save();
        $db->commit();

        $reviewRatingTable = Engine_Api::_()->getDbtable('ratings', 'sitepagereview');
        $reviewRatingTable->delete(array('review_id = ?' => $review->review_id));

        //DO ENTRY IN REVIEW RATING TABLE
        foreach ($_POST as $key => $ratingdata) {
          $string_exist = strstr($key, 'review_rate_');
          if ($string_exist) {
            $reviewcat_id = explode('review_rate_', $key);
            $reviewRatingTable = Engine_Api::_()->getDbtable('ratings', 'sitepagereview');
            $reviewRating = $reviewRatingTable->createRow();
            $reviewRating->review_id = $review->review_id;
            $reviewRating->category_id = $sitepage->category_id;
            $reviewRating->page_id = $review->page_id;
            $reviewRating->reviewcat_id = $reviewcat_id[1];
            $reviewRating->rating = $ratingdata;
            $reviewRating->save();
          }
        }

        //UPDATE RATING IN PAGE TABLE
        Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->pageRatingUpdate($review->page_id);
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      //GO TO SITEPAGE PROFILE PAGE WITH SITEPAGE REVIEW SELECTED TAB
      return $this->_redirectCustom($review->getHref(), array('prependBase' => false));
    }
  }

  //ACTION FOR DELETING A REVIEW
  public function deleteAction() {
    if (!$this->_helper->requireUser()->isValid())
      return;
  
    //GET VIEWER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $review_id = $this->_getParam('review_id');

    //GET REVIEW
    $review = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
    if ($review->owner_id != $viewer_id && $viewer->level_id != 1) {
      return $this->_forwardCustom('requireauth', 'error', 'core');
    }

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepage_main');
    $page_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_id', null);
		
    //GET OBJECT
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page',$page_id);
    $this->view->page_id = $page_id;
    $this->view->tab_selected_id = $tab_selected_id = $this->_getParam('tab');

    if ($this->getRequest()->isPost()) {

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

        Engine_Api::_()->sitepagereview()->deleteContent($review_id);

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      //GO TO SITEPAGE PROFILE PAGE WITH SITEPAGE REVIEW SELECTED TAB
      return $this->_gotoRouteCustom(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($sitepage->getIdentity()), 'tab' => $tab_selected_id), 'sitepage_entry_view', true);
    } else {
      $this->renderScript('index/delete.tpl');
    }
  }

  public function browseAction() {

   //CHECK VIEW PRIVACY
    if (!$this->_helper->requireAuth()->setAuthParams('sitepage_page', null, 'view')->isValid())
      return;

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

  public function homeAction() {
 
    //CHECK VIEW PRIVACY
    if (!$this->_helper->requireAuth()->setAuthParams('sitepage_page', null, 'view')->isValid())
      return;

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

  // ACTION FOR FEATURED REVIEWS CAROUSEL AFTER CLICK ON BUTTON 
  public function featuredReviewsCarouselAction() {
    //RETRIVE THE VALUE OF ITEM VISIBLE
    $this->view->itemsVisible = $limit = (int) $_GET['itemsVisible'];

    //RETRIVE THE VALUE OF NUMBER OF ROW
    $this->view->noOfRow = (int) $_GET['noOfRow'];
    //RETRIVE THE VALUE OF ITEM VISIBLE IN ONE ROW
    $this->view->inOneRow = (int) $_GET['inOneRow'];

    // Total Count Featured Photos
    $totalCount = (int) $_GET['totalItem'];

    //RETRIVE THE VALUE OF START INDEX
    $startindex = $_GET['startindex'] * $limit;

    if ($startindex > $totalCount) {
      $startindex = $totalCount - $limit;
    }
    if ($startindex < 0)
      $startindex = 0;

    $params = array();
    $params['category_id'] = $_GET['category_id'];
    $params['page_validation'] = 1;
		$params['featured'] = 1;
    $widgetType = 'featuredcarousel';

    //RETRIVE THE VALUE OF BUTTON DIRECTION
    $direction = $_GET['direction'];
    $this->view->offset = $params['start_index'] = $startindex;

    //GET Featured Photos with limit * 2
    $this->view->totalItemsInSlide = $params['limit'] = $limit * 2;
    $this->view->featuredReviews =  $featuredReviews = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->reviewRatingData($params,$widgetType);

    //Pass the total number of result in tpl file
    $this->view->count = count($featuredReviews);

    //Pass the direction of button in tpl file
    $this->view->direction = $direction;
  }

}