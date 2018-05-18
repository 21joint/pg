<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagealbum
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepagereview_ReviewController extends Siteapi_Controller_Action_Standard {

    /*
    *   Checks auth and gets subject
    *
    *
    */
    public function init() {
        $viewer = Engine_Api::_()->user()->getViewer();

        $page_id = $this->_getParam('page_id');
        if (!empty($page_id)) {
            $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
            if (!empty($sitepage))
                Engine_Api::_()->core()->setSubject($sitepage);
        }


        // Authorization check
        if (!$this->_helper->requireAuth()->setAuthParams('sitepage_page', $viewer, "view")->isValid())
            $this->respondWithError('unauthorized');
    }

    /**
     * Create a review
     * 
     */
    public function createAction() {

        if (Engine_Api::_()->core()->hasSubject('sitepage_page'))
            $sitepage = $subject = Engine_Api::_()->core()->getSubject('sitepage_page');
        else
            $this->respondWithError('no_record');

        // Get viewer info
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $create_level_allow = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitepagereview_review', "create");

        if (!$create_level_allow)
            $this->respondWithError('unauthorized');


        // Check if this user has already reviewed on this page
        $hasPostedReview = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->canPostReview($sitepage->page_id, $viewer_id);
        
        if ($hasPostedReview) {
                  $this->respondWithError('review_already_present');
        }

        // Core settings
        $coreApi = Engine_Api::_()->getApi('settings', 'core');
        $sitepagereview_proscons = $coreApi->getSetting('sitepagereview.proscons', 1);
        $sitepagereview_limit_proscons = $coreApi->getSetting('sitepagereview.limit.proscons', 500);
        $sitepagereview_recommend = $coreApi->getSetting('sitepagereview.recommend', 1);

        // Fetch review categories
        $categoryIdsArray = array();
        $categoryIdsArray[] = $sitepage->category_id;
        $categoryIdsArray[] = $sitepage->subcategory_id;
        $categoryIdsArray[] = $sitepage->subsubcategory_id;

        $ratingParams = Engine_Api::_()->getDbtable('reviewcats', 'sitepagereview')->reviewParams($sitepage->category_id);
        $ratingParamsarray = $ratingParams->toArray();

        if ($this->getRequest()->isGet()) {

            $ratingParam = array();
            $ratingParam[] = array(
                'type' => 'Rating',
                'name' => 'review_rate_0',
                'label' => $this->translate('Overall Rating')
            );

            $profileTypeReview = Engine_Api::_()->getDbtable('categories', 'sitepage')->getProfileType(array(), $sitepage->category_id);

            foreach ($ratingParams as $ratingparam_id) {
                $ratingParam[] = array(
                    'type' => 'Rating',
                    'name' => 'review_rate_' . $ratingparam_id->reviewcat_id,
                    'label' => $ratingparam_id->reviewcat_name
                );
            }
            $response['form'] = Engine_Api::_()->getApi('Siteapi_Core', 'Sitepagereview')->getReviewCreateForm(array("settingsReview" => array('sitepagereview_proscons' => $sitepagereview_proscons, 'sitepagereview_limit_proscons' => $sitepagereview_limit_proscons, 'sitepagereview_recommend' => $sitepagereview_recommend), 'item' => $sitepage, 'profileTypeReview' => $profileTypeReview));
            $response['ratingParams'] = $ratingParam;
            $this->respondWithSuccess($response, true);
        }

        if ($this->getRequest()->isPost()) {
            // Convert post data into an array
            $values = $postData = $this->_getAllParams();
            $getForm = Engine_Api::_()->getApi('Siteapi_Core', 'Sitepagereview')->getReviewCreateForm(array("settingsReview" => array('sitepagereview_proscons' => $sitepagereview_proscons, 'sitepagereview_limit_proscons' => $sitepagereview_limit_proscons, 'sitepagereview_recommend' => $sitepagereview_recommend), 'item' => $sitepage, 'profileTypeReview' => $profileTypeReview));
            foreach ($getForm as $element) {
                if (isset($_REQUEST[$element['name']]))
                    $values[$element['name']] = $_REQUEST[$element['name']];
            }

            // Start form validation
            $validators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'sitepagereview')->getReviewCreateFormValidators(array("settingsReview" => array('sitepagereview_proscons' => $sitepagereview_proscons, 'sitepagereview_limit_proscons' => $sitepagereview_limit_proscons, 'sitepagereview_recommend' => $sitepagereview_recommend), 'item' => $sitepage, 'profileTypeReview' => $profileTypeReview));
            $values['validators'] = $validators;
            $validationMessage = $this->isValid($values);

            // Response validation error
            if (!empty($validationMessage) && @is_array($validationMessage)) {
                $this->respondWithValidationError('validation_fail', $validationMessage);
            }

            if(!isset($values['review_rate_0']) || empty(intval($values['review_rate_0'])))
                $this->respondWithValidationError('parameter_missing' , 'Overall rating required: Please complete this field');

            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {

                $values['owner_id'] = $viewer_id;
                $values['resource_id'] = $sitepage->page_id;
                $values['resource_type'] = $sitepage->getType();
                $values['profile_type_review'] = $profileTypeReview;
                $values['type'] = $viewer_id ? 'user' : 'visitor';

                if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1)) {
                    $values['recommend'] = 0;
                }
                // Add review
                $reviewTable = Engine_Api::_()->getDbtable('reviews', 'sitepagereview');
                $review = $reviewTable->createRow();
                $review->setFromArray($values);
                $review->view_count = 1;
                $review->save();

                $review_id = $review->review_id;
                
                // increment review count
                if (!empty($viewer_id))
                {
                    $sitepage->review_count++;
                    $sitepage->rating = (float)(($sitepage->rating + $values['review_rate_0']) / $sitepage->review_count) ;
                    $sitepage->save();
                }


                $reviewRatingTable = Engine_Api::_()->getDbtable('ratings', 'sitepagereview');
                if (!empty($review_id)) {
                    $reviewRatingTable->delete(array('review_id = ?' => $review->review_id));
                }
                
                //Insert rating params
                if(isset($postData['review_rate_0']) && !empty($postData['review_rate_0']))
                {
                    $newRating = $reviewRatingTable->createRow();
                    $newRating->review_id = $review->getIdentity();
                    $newRating->page_id = $sitepage->getIdentity();
                    $newRating->reviewcat_id = 0;
                    $newRating->category_id = $sitepage->category_id;
                    $newRating->rating = $postData['review_rate_0'];
                    $newRating->save();
                }
                foreach($ratingParamsarray as $row => $value)
                {
                    if(isset($postData['review_rate_'.$value['reviewcat_id']]) && !empty($postData['review_rate_'.$value['reviewcat_id']]))
                    {
                        $newRating = $reviewRatingTable->createRow();
                        $newRating->review_id = $review->getIdentity();
                        $newRating->page_id = $sitepage->getIdentity();
                        $newRating->reviewcat_id = $value['reviewcat_id'];
                        $newRating->category_id = $sitepage->category_id;
                        $newRating->rating = $postData['review_rate_'.$value['reviewcat_id']];
                        $newRating->save();
                    }
                }
                

                if (empty($review_id) && !empty($viewer_id)) {
                    $activityApi = Engine_Api::_()->getDbtable('actions', 'seaocore');

                    // Activity feed
                    $action = $activityApi->addActivity($viewer, $sitepage, 'sitepagereview_new');

                    if ($action != null) {
                        $activityApi->attachActivity($action, $review);

                        //START NOTIFICATION AND EMAIL WORK
                        //Engine_Api::_()->getApi('Siteapi_Core', 'sitepagereview')->sendNotificationEmail($sitepage, $action, 'sitepage_write_review', 'SITEPAGE_REVIEW_WRITENOTIFICATION_EMAIL', null, null, 'created', $review);
                        //Engine_Api::_()->getApi('Siteapi_Core', 'sitepagereview')->sendNotificationToFollowers($sitepage, 'sitepage_write_review');
                        //END NOTIFICATION AND EMAIL WORK
                    }
                }

                $db->commit();
                $this->successResponseNoContent('no_content', true);
                
            } catch (Exception $e) {

                $db->rollback();
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
                
            }
        }
    }

    /*
    * Returns review detail
    *
    */
    public function viewAction() {

        $this->validateRequestMethod();

        // Require user
        if (!$this->_helper->requireUser()->isValid())
            $this->respondWithError('unauthorized');

        // Gets logged in user info
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $review_id = $this->_getParam('review_id', $this->_getParam('review_id', null));
        if ($review_id) {
            $review = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
        }

        if (!$review) {
            $this->respondWithError('no_record');
        }

        if (!Engine_Api::_()->core()->hasSubject('sitepage_page')) {
            $this->respondWithError('no_record');
        }
        // Get the page 
        $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');

        // Get user level id
        if (!empty($viewer_id)) {
            $level_id = $viewer->level_id;
        } else {
            $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        }

        // Get level id
        //$can_view = Engine_Api::_()->authorization()->getPermission($level_id, 'sitepage_page', "view");


        // if ($can_view != 2 && $viewer_id != $sitepage->owner_id && ($sitepage->draft == 1 || $sitepage->search == 0 || $sitepage->approved != 1)) {
        //     $this->respondWithError('unauthorized');
        // }
        // if ($can_view != 2 && ($review->status != 1 && empty($review->owner_id))) {
        //     $this->respondWithError('unauthorized');
        // }

        $params = array();
        $params = $review->toArray();
        $params['owner_title'] = $review->getOwner()->getTitle();
        // Get location
        if (!empty($sitepage->location) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.location', 1)) {
            $params['location'] = $sitepage->location;
        }

        $params['tag'] = $sitepage->getKeywords(', ');

        $tableCategory = Engine_Api::_()->getDbTable('categories', 'sitepage');

        $category_id = $sitepage->category_id;

        if (!empty($category_id)) {

            $params['categoryname'] = Engine_Api::_()->getItem('sitepage_category', $category_id)->category_name;

            $subcategory_id = $sitepage->subcategory_id;

            if (!empty($subcategory_id)) {

                $params['subcategoryname'] = Engine_Api::_()->getItem('sitepage_category', $subcategory_id)->category_name;

                $subsubcategory_id = $sitepage->subsubcategory_id;

                if (!empty($subsubcategory_id)) {

                    $params['subsubcategoryname'] = Engine_Api::_()->getItem('sitepage_category', $subsubcategory_id)->category_name;
                }
            }
        }

        // Get the rating if present
        $ratingParams = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->profileRatingbyCategory($review->review_id);

        $params['ratingParams'] = $ratingParams;
        $guttermenu = $this->guttermenu($sitepage, $review, 'view');
        if ($params['comment_count'] > 0) {

            $guttermenu['comments'] = array(
                'title' => $this->translate('List Comments'),
                'name' => 'listcomments',
                'url' => 'sitepage/review/listcomments/' . $sitepage->page_id . '/' . $review->review_id,
            );
        }

        $response['response'] = $params;
        $this->respondWithSuccess($response, true);
    }

    /*
    * Returns review search form
    *
    *
    */
    public function searchAction() {
        $this->validateRequestMethod();
        $this->respondWithSuccess(Engine_Api::_()->getApi('Siteapi_Core', 'Sitepagereview')->getReviewSearchForm(), true);
    }


    /*
    *  Returns review listing with pagination filtering the form fields
    *
    *
    */
    public function browseAction() {

        $this->validateRequestMethod();

        // Get viewer info
        $viewer = Engine_Api::_()->user()->getViewer();

        if($viewer)
            $viewer_id = $viewer->getIdentity();

        // Page subject should be set
        if (!Engine_Api::_()->core()->hasSubject('sitepage_page'))
            $this->respondWithError('no_record');

        $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');

        $page_id = $sitepage->page_id;

        // Get params
        $params['type'] = '';

        $params = $this->_getAllParams();
        if (!isset($params['order']) || empty($params['order']))
            $params['order'] = 'recent';

        if (isset($params['show'])) {

            switch ($params['show']) {
                case 'friends_reviews':
                    $params['user_ids'] = $viewer->membership()->getMembershipsOfIds();
                    if (empty($params['user_ids']))
                        $params['user_ids'] = -1;
                    break;
                case 'self_reviews':
                    $params['user_id'] = $viewer_id;
                    break;
                case 'featured':
                    $params['featured'] = 1;
                    break;
            }
        }

        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $params['resource_type'] = 'sitepage_page';
        $params['page_id'] = $page_id;

        if (isset($params['user_id']) && !empty($params['user_id']))
            $user_id = $params['user_id'];
        else
            $user_id = $viewer_id;

        // Get review table
        $reviewTable = Engine_Api::_()->getDbTable('reviews', 'sitepagereview');
        // Get rating table
        $ratingTable = Engine_Api::_()->getDbTable('ratings', 'sitepagereview');
        
        $type = 'user';
        
        if($viewer_id)
            $level_id = $viewer->level_id;

        try {
            // Custom field work
            //$customFieldValues = array_intersect_key($searchParams, $searchForm->getFieldElements());
            // Get paginator
            // Get review table
            $paginator = $reviewTable->getReviewsPaginator($params, null);
            $paginator->setItemCountPerPage(10);
            $paginator->setCurrentPageNumber($this->_getParam('page', 1));

            if (isset($params['subcategory_id']) && $params['subcategory_id'])
                $searchParams['subcategory_id'] = $params['subcategory_id'];
            if (isset($params['subsubcategory_id']) && $params['subsubcategory_id'])
                $searchParams['subsubcategory_id'] = $params['subsubcategory_id'];

            // Get total reviews
            $totalReviews = $paginator->getTotalItemCount();
            
            // Start top section for overall rating and it's parameter
            $params['resource_id'] = $page_id;
            $params['resource_type'] = $sitepage->getType();
            $params['viewer_id'] = $viewer_id;
            $params['type'] = 'user';
            $noReviewCheck = $reviewTable->getAvgRecommendation($page_id);
            if (!empty($noReviewCheck)) {
                $noReviewCheck = $noReviewCheck->toArray();
                if ($noReviewCheck)
                    $recommend_percentage = round($noReviewCheck[0]['avg_recommend'] * 100, 3);
            }

            // for ($i = 5; $i > 0; $i--) {
            //     $ratingCount[$i] = $ratingTable->getNumbersOfUserRating($page_id, 'user', 0, $i, 0, 'sitepage_page', array());
            // }

            $ratingData = $ratingTable->ratingbyCategory($page_id);
            $hasPosted = $reviewTable->canPostReview($page_id, $viewer_id);
            $reviewRateMyData = $ratingTable->ratingsData($hasPosted);
            $coreApi = Engine_Api::_()->getApi('settings', 'core');

            $sitepagereview_proscons = $coreApi->getSetting('sitepagereview.proscons', 1);
            $sitepagereview_limit_proscons = $coreApi->getSetting('sitepagereview.limit.proscons', 500);
            $sitepagereview_recommend = $coreApi->getSetting('sitepagereview.recommend', 1);
            $sitepagereview_report = $coreApi->getSetting('sitepagereview.report', 1);
            $sitepagereview_email = $coreApi->getSetting('sitepagereview.email', 1);
            $sitepagereview_share = $coreApi->getSetting('sitepagereview.share', 1);

            if($viewer_id)
            {
                $create_level_allow = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitepagereview_review', "review_create");
            }
            else
            {
                $create_level_allow = 0 ;
            }

            $create_review = ($sitepage->owner_id == $viewer_id) ? Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.allowownerreview', 1) : 1;

            if (!$create_review || empty($create_level_allow)) {
                $can_create = 0;
            } else {
                $can_create = 1;
            }

            if($viewer_id)
            {
                $can_delete = Engine_Api::_()->authorization()->getPermission($level_id, 'sitepagereview_review', "review_delete");

                $can_reply = Engine_Api::_()->authorization()->getPermission($level_id, 'sitepagereview_review', "review_reply");

                $can_update = Engine_Api::_()->authorization()->getPermission($level_id, 'sitepagereview_review', "review_update");
                
            }
            else
            {
                $can_delete = $can_reply = $can_update = 0;
            }
            // review breackdown rating_params
            $ratings_params = $ratingTable->ratingbyCategory($page_id);
            $rating_params_data = array();
            if(!empty($ratings_params))
            {
                foreach($ratings_params as $value)
                {
                    if($value['reviewcat_id'])
                        $rating_params_data[] = $value;
                }
            }
            
            if (isset($params['getRating']) && !empty($params['getRating'])) {
                $ratings['rating_avg'] = $sitepage->rating;
                $ratings['rating_users'] = $sitepage->review_count;
                $ratings['breakdown_ratings_params'] = $rating_params_data;
                $ratings['myRatings'] = $reviewRateMyData;
                $ratings['review_id'] = $hasPosted;
                $ratings['recomended'] = $recommend_percentage." %";
                $response['ratings'] = $ratings;
            }

            $metaParams = array();
            $response['total_reviews'] = $totalReviews;
            $response['content_title'] = $sitepage->getTitle();            


            $tableCategory = Engine_Api::_()->getDbTable('categories', 'sitepage');

            $request = Zend_Controller_Front::getInstance()->getRequest();

            $category_id = $request->getParam('category_id', null);



            if (!empty($category_id)) {

                $metaParams['categoryname'] = Engine_Api::_()->getItem('sitepage_category', $category_id)->getCategorySlug();

                $subcategory_id = $request->getParam('subcategory_id', null);

                if (!empty($subcategory_id)) {

                    $metaParams['subcategoryname'] = Engine_Api::_()->getItem('sitepage_category', $subcategory_id)->getCategorySlug();

                    $subsubcategory_id = $request->getParam('subsubcategory_id', null);

                    if (!empty($subsubcategory_id)) {

                        $metaParams['subsubcategoryname'] = Engine_Api::_()->getItem('sitepage_category', $subsubcategory_id)->getCategorySlug();
                    }
                }
            }

            // Set meta titles
            // Todo error in set meta titles
            // Engine_Api::_()->sitepage()->setMetaTitles($metaParams);

            $allow_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.allowreview', 1);

            if (empty($allow_review)) {
                $this->respondWithError('unauthorized');
            }

            $metaParams['page_type_title'] = $this->translate('sitepage');

            // Get tag
            if ($this->_getParam('search', null)) {
                $metaParams['search'] = $this->_getParam('search', null);
            }

            foreach ($paginator as $review) {

                $params = $review->toArray();

                if($params['recommend'] == 1)
                    $params['recommend'] = "Yes";
                else
                    $params['recommend'] = "No";

                // isliked
                $corelikesTable = Engine_Api::_()->getDbtable('likes', 'core');
                $isliked = $corelikesTable->getLike($review , $viewer);
                $params['is_liked'] = false;
                if($isliked)
                    $params['is_liked'] = true;

                if (isset($params['body']) && !empty($params['body']))
                    $params['body'] = strip_tags($params['body']);

                $params ["owner_title"] = $review->getOwner()->getTitle();

                // Owner image Add images 
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($review, true);

                $params = array_merge($params, $getContentImages);
                $page_id = $review->page_id;
                $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);

                $params['page_title'] = $sitepage->title;

                $user_ratings = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->ratingsData($review->review_id, $review->getOwner()->getIdentity(), $review->page_id, 0);
                $params['overall_rating'] = $user_ratings[0]['rating'];
                $params['category_name'] = Engine_Api::_()->getItem('sitepage_category', $sitepage->category_id)->category_name;
                // $helpfulTable = Engine_Api::_()->getDbtable('helpful', 'sitepage');
                // $helpful_entry = $helpfulTable->getHelpful($review->review_id, $viewer_id, 1);
                // $nothelpful_entry = $helpfulTable->getHelpful($review->review_id, $viewer_id, 2);
                // $params['is_helful'] = ($helpful_entry) ? true : false;
                // $params['is_not_helful'] = ($nothelpful_entry) ? true : false;
                // $params['helpful_count'] = $review->getCountHelpful(1);
                // $params['nothelpful_count'] = $review->getCountHelpful(2);
                // Add owner images

                $guttermenu = $this->guttermenu($sitepage, $review, 'browse');
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($sitepage);
                $params = array_merge($params, $getContentImages);
                $params['guttermenu'] = $guttermenu;
                
                // get rating params
                $profileRating = $ratingTable->profileRatingbyCategory($review->review_id);
                $breakdown_ratings_params = array();
                
                if(!empty($profileRating))
                {
                    foreach($profileRating as $value)
                    {
                        if($value['reviewcat_id'])
                            $breakdown_ratings_params[] = $value;
                    }
                }
                
                $params['breakdown_ratings_params'] = $breakdown_ratings_params;
                
                $tempResponse[] = $params;
            }
            if (isset($tempResponse) && !empty($tempResponse))
                $response['reviews'] = $tempResponse;
            $this->respondWithSuccess($response, true);
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    /*
    * Action for deleting review
    *
    *
    */
    public function deleteAction() {

        // Validate request method
        $this->validateRequestMethod("DELETE");

        // Get logged in user info
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        $review_id = $this->_getParam('review_id', $this->_getParam('review_id', null));
        if ($review_id) {
            $review = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
        }

        if ($review->owner_id != $viewer_id ) {
            $this->respondWithError('unauthorized');
        }

        if (!$review) {
            $this->respondWithError('no_record');
        }

        if (!Engine_Api::_()->core()->hasSubject('sitepage_page')) {
            $this->respondWithError('no_record');
        }
        // Get the page 
        $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            Engine_Api::_()->sitepagereview()->deleteContent($review_id);
            $db->commit();
            $this->successResponseNoContent('no_content', true);
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    /*
    * Returns comments list of the review
    *
    *
    */
    public function listcommentsAction() {
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$this->_helper->requireUser()->isValid()) {
            $this->respondWithError('unauthorized');
        }
        $review_id = $this->_getParam('review_id', $this->_getParam('review_id', null));
        if ($review_id) {
            $review = $subject = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
        }

        if (!$review && empty($review))
            $this->respondWithError('no_record');

        $subjectParent = $subject->getParent();

        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');

        $pageSubject = $subject->getParent();
        $pageApi = Engine_Api::_()->sitepage();
        $canComment = $pageApi->isManageAdmin($pageSubject, 'comment');
        $pageApi->isManageAdmin($pageSubject, 'edit');
        $viewAllLikes = $this->_getParam('viewAllLikes', false);
        $likes = $subject->likes()->getLikePaginator();
        $likesData = array();
        if (!empty($likes)) {
            foreach ($likes as $like) {
                $likesData[$like->like_id] = $like->toArray();
                $poster = $like->getPoster();
                $posterImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($poster, true);
                $likesData[$like->like_id]['owner_images'] = $posterImages;
                $likesData[$like->like_id]['owner_title'] = $poster->getTitle();
            }
        }
        // Comments
        // If has a page, display oldest to newest
        if (null !== ( $page = $this->_getParam('page'))) {
            $commentSelect = $subject->comments()->getCommentSelect('ASC');
            $commentSelect->order('comment_id ASC');
            $comments = Zend_Paginator::factory($commentSelect);
            $comments->setCurrentPageNumber($page);
            $comments->setItemCountPerPage(10);
        }
        // If not has a page, show the
        else {
            $commentSelect = $subject->comments()->getCommentSelect('DESC');
            $commentSelect->order('comment_id DESC');

            $comments = Zend_Paginator::factory($commentSelect);
            $comments->setCurrentPageNumber(1);
            $comments->setItemCountPerPage(4);
        }
        $commentsData = array();
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $poster = $comment->getPoster();
                $posterImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($poster, true);
                $likes = $comment->likes();
                $commentsData[$comment->comment_id] = $comment->toArray();
                $commentsData[$comment->comment_id]['owner_images'] = $posterImages;
                $commentsData[$comment->comment_id]['owner_title'] = $poster->getTitle();
            }
        }

        $response['comments'] = $commentsData;
        $response['likes'] = $likesData;

        $this->respondWithSuccess($response, true);
    }

    /*
    * Returns comment form and posts comment on a review
    *
    *
    */
    public function commentAction() {
        if (!$this->_helper->requireUser()->isValid()) {
            $this->respondWithError('unauthorized');
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        $review_id = $this->_getParam('review_id', $this->_getParam('review_id', null));
        if ($review_id) {
            $review = $subject = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
        }

        if (!$review && empty($review))
            $this->respondWithError('no_record');

        $subjectParent = $subject->getParent();


        if ($this->getRequest()->isGet()) {
            $commentform = Engine_Api::_()->getApi('Siteapi_Core', 'Sitepagereview')->getcommentForm($review->getType(), $review->review_id);
            $this->respondWithSuccess($commentform, true);
        }

        if ($this->getRequest()->isPost()) {
            $values = array();
            $values = $this->_getAllParams();
            
            // Start form validation
            $validators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'sitepagereview')->getcommentValidation();
            $values['validators'] = $validators;
            $validationMessage = $this->isValid($values);

            // Response validation error
            if (!empty($validationMessage) && @is_array($validationMessage)) {
                $this->respondWithValidationError('validation_fail', $validationMessage);
            }
            
            $body = $values['body'];
            $values['type'] = $subject->getType();
            $values['id'] = $subject->review_id;
            $values['identity'] = $subject->review_id;
            $db = $subject->comments()->getCommentTable()->getAdapter();
            $db->beginTransaction();
            try {
                $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
                $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
                $subjectOwner = $subject->getOwner('user');
                $subject->comments()->addComment($viewer, $body);

                // Activity
                $action = $activityApi->addActivity($viewer, $subject, 'comment_' . $subject->getType(), '', array(
                    'owner' => $subjectOwner->getGuid(),
                    'body' => $body
                ));

                if (!empty($action)) {
                    $activityApi->attachActivity($action, $subject);
                }


                // add notification
//                $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
//                $notifyApi->addNotification($notifyUser, $viewer, $subject, 'commented_commented', array(
//                    'label' => $subject->getShortType()
//                ));
                

                // Add a notification for all users that commented or like except the viewer and poster
                // @todo we should probably limit this
//                $commentedUserNotifications = array();
//                foreach ($subject->comments()->getAllCommentsUsers() as $notifyUser) {
//                    if ($notifyUser->getIdentity() == $viewer->getIdentity() || $notifyUser->getIdentity() == $subjectOwner->getIdentity())
//                        continue;
//
//                    // Don't send a notification if the user both commented and liked this
//                    $commentedUserNotifications[] = $notifyUser->getIdentity();
//                    $notifyApi->addNotification($notifyUser, $viewer, $subject, 'commented_commented', array(
//                        'label' => $subject->getShortType()
//                    ));
//                }


                // Add a notification for all users that liked
                // @todo we should probably limit this
//                foreach ($subject->likes()->getAllLikesUsers() as $notifyUser) {
//                    // Skip viewer and owner
//                    if ($notifyUser->getIdentity() == $viewer->getIdentity() || $notifyUser->getIdentity() == $subjectOwner->getIdentity())
//                        continue;
//
//                    // Don't send a notification if the user both commented and liked this
//                    if (in_array($notifyUser->getIdentity(), $commentedUserNotifications))
//                        continue;
//
//                    $notifyApi->addNotification($notifyUser, $viewer, $subject, 'liked_commented', array(
//                        'label' => $subject->getShortType()
//                    ));
//
//
//                    //end check for page admin and page owner
//                }

                // Send notification to Page admins
                $sitepageVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitepage')->version;
                if ($sitepageVersion >= '4.2.9p3') {
                    Engine_Api::_()->sitepage()->itemCommentLike($subject, 'sitepage_contentcomment', $baseOnContentOwner);
                }

                // Increment comment count
                Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.comments');

                $db->commit();
                $this->successResponseNoContent('no_content', true);
            } catch (Exception $e) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        }
    }

    /*
    * Deletes a comment
    *
    *
    */
    public function removecommentAction() {
        $viewer = Engine_Api::_()->user()->getViewer();
        $review_id = $this->_getParam('review_id', $this->_getParam('review_id', null));
        if ($review_id) {
            $review = $subject = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
        }

        if (!$review && empty($review))
            $this->respondWithError('no_record');
        // Validate request methods
        $this->validateRequestMethod();
        if (!$this->_helper->requireUser()->isValid()) {
            $this->respondWithError('unauthorized');
        }

        // Comment id
        $comment_id = $this->_getParam('comment_id');

        // Comment
        $comment = $subject->comments()->getComment($comment_id);
        if (!$comment) {
            $this->respondWithError('no_record');
        }
        if ($this->getRequest()->isPost()) {
            $commentsremoveform = array(
                'type' => 'int',
                'name' => 'comment_id',
            );
            $response['form'] = $commentsremoveform;
            $this->respondWithSuccess($response, true);
        }
        if ($this->getRequest()->isPost()) {
            // Process
            $db = $subject->comments()->getCommentTable()->getAdapter();
            $db->beginTransaction();

            try {
                $subject->comments()->removeComment($comment_id);
                $db->commit();
                $this->successResponseNoContent('no_content', true);
            } catch (Exception $e) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $ex->getMessage());
            }
        }
    }

    /*
    *   Updates a review form and posting
    *
    *
    */
    public function editAction() {

        if (Engine_Api::_()->core()->hasSubject('sitepage_page'))
            $sitepage = $subject = Engine_Api::_()->core()->getSubject('sitepage_page');
        else
            $this->respondWithError('no_record');

        $review_id = $this->_getParam('review_id', $this->_getParam('review_id', null));
        if ($review_id) {
            $review = $subject = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
        }

        if (!$review && empty($review))
            $this->respondWithError('no_record');

        //GET VIEWER INFO
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        
        if(($review->owner_id != $viewer_id))
            $this->respondWithError('unauthorized');
        
        // core settings
        $coreApi = Engine_Api::_()->getApi('settings', 'core');
        $sitepagereview_proscons = $coreApi->getSetting('sitepagereview.proscons', 1);
        $sitepagereview_limit_proscons = $coreApi->getSetting('sitepagereview.limit.proscons', 500);
        $sitepagereview_recommend = $coreApi->getSetting('sitepagereview.recommend', 1);


        //FETCH REVIEW CATEGORIES
        $categoryIdsArray = array();
        $categoryIdsArray[] = $sitepage->category_id;
        $categoryIdsArray[] = $sitepage->subcategory_id;
        $categoryIdsArray[] = $sitepage->subsubcategory_id;

        $ratingParams = Engine_Api::_()->getDbtable('reviewcats', 'sitepagereview')->reviewParams($sitepage->category_id);

        $ratingParam = array();
        $ratingParam[] = array(            
            'type' => 'Rating',
            'name' => 'review_rate_0',
            'label' => $this->translate('Overall Rating')
        );

        $profileTypeReview = Engine_Api::_()->getDbtable('categories', 'sitepage')->getProfileType(array(), $sitepage->category_id);

        foreach ($ratingParams as $ratingparam_id) {
            $ratingParam[] = array(
                'type' => 'Rating',
                'name' => 'review_rate_' . $ratingparam_id->reviewcat_id,
                'label' => $ratingparam_id->reviewcat_name
            );
        }

        //GET LEVEL SETTING
        $can_view = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitepage_page', "view");


        // if ($can_view != 2 && $viewer_id != $sitepage->owner_id && ($sitepage->draft == 1 || $sitepage->search == 0 || $sitepage->approved != 1)) {
        //     echo "database";die;
        //     $this->respondWithError('unauthorized');
        // }
        // if ($can_view != 2 && ($review->status != 1 && empty($review->owner_id))) {
        //     $this->respondWithError('unauthorized');
        // }

        $params = array();
        $params['pros'] = $review->pros;
        $params['cons'] = $review->cons;
        $params['title'] = $review->title;
        $params['body'] = $review->body;
        $params['owner_title'] = $review->getOwner()->getTitle();
        $params['recommend'] = $review->recommend;
        
        // GET LOCATION
        if (!empty($sitepage->location) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.location', 1)) {
            $params['location'] = $sitepage->location;
        }

        $params['tag'] = $sitepage->getKeywords(', ');

        //GET EVENT CATEGORY TABLE
        $tableCategory = Engine_Api::_()->getDbTable('categories', 'sitepage');

        $category_id = $sitepage->category_id;

        if (!empty($category_id)) {

            $params['categoryname'] = Engine_Api::_()->getItem('sitepage_category', $category_id)->category_name;

            $subcategory_id = $sitepage->subcategory_id;

            if (!empty($subcategory_id)) {

                $params['subcategoryname'] = Engine_Api::_()->getItem('sitepage_category', $subcategory_id)->category_name;

                $subsubcategory_id = $sitepage->subsubcategory_id;

                if (!empty($subsubcategory_id)) {

                    $params['subsubcategoryname'] = Engine_Api::_()->getItem('sitepage_category', $subsubcategory_id)->category_name;
                }
            }
        }

        // Get the rating if present
        $ratingParams = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->profileRatingbyCategory($review->review_id);
        $ratingParamsarray = array();
        
        foreach($ratingParams as $value)
        {
            $ratingParamsarray['review_rate_'.$value['reviewcat_id']] = $value['rating'] ;
        }

        $params = array_merge($params,$ratingParamsarray);
        if ($this->getRequest()->isGet()) {
            $response['form'] = Engine_Api::_()->getApi('Siteapi_Core', 'Sitepagereview')->getReviewCreateForm(array("settingsReview" => array('sitepagereview_proscons' => $sitepagereview_proscons, 'sitepagereview_limit_proscons' => $sitepagereview_limit_proscons, 'sitepagereview_recommend' => $sitepagereview_recommend), 'item' => $sitepage, 'profileTypeReview' => $profileTypeReview));
            $response['ratingParams'] = $ratingParam;
            $response['formValues'] = $params;
            $this->respondWithSuccess($response, true);
        }

        if ($this->getRequest()->isPost() || $this->getRequest()->isPut()) {
            // Convert post data into the array.
            $values = array();
            $values = $this->_getAllParams();
            $getForm = Engine_Api::_()->getApi('Siteapi_Core', 'Sitepagereview')->getReviewCreateForm(array("settingsReview" => array('sitepagereview_proscons' => $sitepagereview_proscons, 'sitepagereview_limit_proscons' => $sitepagereview_limit_proscons, 'sitepagereview_recommend' => $sitepagereview_recommend), 'item' => $sitepage, 'profileTypeReview' => $profileTypeReview));
            foreach ($getForm as $element) {
                if (isset($_REQUEST[$element['name']]))
                    $values[$element['name']] = $_REQUEST[$element['name']];
            }

            // Start form validation
            $validators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'sitepagereview')->getReviewCreateFormValidators(array("settingsReview" => array('sitepagereview_proscons' => $sitepagereview_proscons, 'sitepagereview_limit_proscons' => $sitepagereview_limit_proscons, 'sitepagereview_recommend' => $sitepagereview_recommend), 'item' => $sitepage, 'profileTypeReview' => $profileTypeReview));
            $values['validators'] = $validators;
            $validationMessage = $this->isValid($values);

            // Response validation error
            if (!empty($validationMessage) && @is_array($validationMessage)) {
                $this->respondWithValidationError('validation_fail', $validationMessage);
            }

            $postData = $this->_getAllParams();

            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            try {

                $values['owner_id'] = $viewer_id;
                $values['resource_id'] = $sitepage->page_id;
                $values['resource_type'] = $sitepage->getType();
                $values['profile_type_review'] = $profileTypeReview;
                $values['type'] = $viewer_id ? 'user' : 'visitor';

                if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1)) {
                    $values['recommend'] = 0;
                }

                $review->setFromArray($values);
                $review->view_count = 1;
                $review->save();

                $review_id = $review->getIdentity();


                $reviewTable = Engine_Api::_()->getDbtable('reviews','sitepagereview');
                $reviewTableName = $reviewTable->info('name');
                $reviewRatingTable = Engine_Api::_()->getDbtable('ratings', 'sitepagereview');
                $reviewRatingTableName = $reviewRatingTable->info('name');

                if ($review_id)
                    $reviewRatingTable->delete(array('review_id = ?' => $review->getIdentity()));

                
                //Insert rating params
                foreach($ratingParam as $row => $value)
                {
                    if(isset($postData[$value['name']]) && !empty($postData[$value['name']]))
                    {
                        $de = explode('_', $value['name']);
                        $ratingCat_id = $de[2];
                        $newRating = $reviewRatingTable->createRow();
                        $newRating->review_id = $review->getIdentity();
                        $newRating->page_id = $sitepage->getIdentity();
                        $newRating->reviewcat_id = $ratingCat_id;
                        $newRating->category_id = $sitepage->category_id;
                        $newRating->rating = $postData[$value['name']];
                        $newRating->save();
                    }
                }

                if (empty($review_id) && !empty($viewer_id)) {
                    $activityApi = Engine_Api::_()->getDbtable('actions', 'seaocore');

                    // Activity feed
                    $action = $activityApi->addActivity($viewer, $sitepage, 'sitepagereview_new');

                    if ($action != null) {
                        $activityApi->attachActivity($action, $review);

                        //START NOTIFICATION AND EMAIL WORK
                        //Engine_Api::_()->sitepage()->sendNotificationEmail($sitepage, $action, 'sitepage_write_review', 'SITEPAGE_REVIEW_WRITENOTIFICATION_EMAIL', null, null, 'created', $review);
                        // $isChildIdLeader = Engine_Api::_()->getDbtable('listItems', 'sitepage')->checkLeader($sitepage);
                        // if (!empty($isChildIdLeader)) {
                        //     Engine_Api::_()->sitepage()->sendNotificationToFollowers($sitepage, 'sitepage_write_review');
                        // }
                        //END NOTIFICATION AND EMAIL WORK
                    }
                }

                $sum_rating = $reviewRatingTable->select()
                                            ->from($reviewRatingTableName , array("sum(rating)"))
                                            ->where("reviewcat_id = ?" , '0')
                                            ->where("page_id = ?" , $sitepage->getIdentity())
                                            ->query()->fetchColumn();

                $sitepage->rating = (float) $sum_rating / $sitepage->review_count;
                $sitepage->save();

                $db->commit();
                $this->successResponseNoContent('no_content', true);
            } catch (Exception $e) {
                $db->rollback();
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        }
    }

    /*
    * Allows to like a review
    *
    *
    */
    public function likeAction() {

        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $viewer = Engine_Api::_()->user()->getViewer();
        $review_id = $this->_getParam('review_id', $this->_getParam('review_id', null));
        if ($review_id) {
            $review = $subject = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
        }

        if (!$review && empty($review))
            $this->respondWithError('no_record');
        // Validate request methods
        $this->validateRequestMethod("POST");
        if (!$this->_helper->requireUser()->isValid()) {
            $this->respondWithError('unauthorized');
        }
        if (!$this->_helper->requireAuth()->setAuthParams(null, null, 'comment')->isValid()) {
            $this->respondWithError('unauthorized');
        }

        if ($this->getRequest()->isPost()) {
            $commentedItem = $subject;
            // Process
            $db = $commentedItem->likes()->getAdapter();
            $db->beginTransaction();
            try {

                if($commentedItem->likes()->isLike($viewer))
                {
                    $commentedItem->likes()->removeLike($viewer);
                }
                else
                {
                    $commentedItem->likes()->addLike($viewer);
                    // Add notification
                    $owner = $commentedItem->getOwner();
                    $this->view->owner = $owner->getGuid();
                    if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
                        $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
                        $notifyApi->addNotification($owner, $viewer, $commentedItem, 'liked', array(
                            'label' => $commentedItem->getShortType()
                        ));
                    }

                    $sitepageVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitepage')->version;
                    if ($sitepageVersion >= '4.2.9p3') {
                        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagemember'))
                            Engine_Api::_()->sitepagemember()->joinLeave($subject, 'Join');
                        Engine_Api::_()->sitepage()->itemCommentLike($subject, 'sitepage_contentlike', '');
                    }
                }

                $db->commit();
                $this->successResponseNoContent('no_content', true);
            } catch (Exception $ex) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $ex->getMessage());
            }
        }
    }

    /*
    *   Allows to ulike a review
    *
    */
    public function unlikeAction() {
        $viewer = Engine_Api::_()->user()->getViewer();
        $review_id = $this->_getParam('review_id', $this->_getParam('review_id', null));
        if ($review_id) {
            $review = $subject = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
        }

        if (!$review && empty($review))
            $this->respondWithError('no_record');
        // Validate request methods
        $this->validateRequestMethod();
        if (!$this->_helper->requireUser()->isValid()) {
            $this->respondWithError('unauthorized');
        }
        if (!$this->_helper->requireAuth()->setAuthParams(null, null, 'comment')->isValid()) {
            $this->respondWithError('unauthorized');
        }

        if ($this->getRequest()->isPost()) {
            $commentedItem = $subject;
            // Process
            $db = $commentedItem->likes()->getAdapter();
            $db->beginTransaction();
            try {
                $commentedItem->likes()->removeLike($viewer);

                // Remove notification
                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitelike')) {
                    Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type = ?' => 'liked', 'subject_id = ?' => $viewer->getIdentity(), 'subject_type = ?' => $viewer->getType(), 'object_type = ?' => $subject->getType(), 'object_id = ?' => $subject->getIdentity()));
                }

                $sitepageVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitepage')->version;
                if ($sitepageVersion >= '4.2.9p3') {
                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagemember'))
                        Engine_Api::_()->sitepagemember()->joinLeave($subject, 'Join');
                }

                $db->commit();
                $this->successResponseNoContent('no_content', true);
            } catch (Exception $ex) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $ex->getMessage());
            }
        }
    }

    /*
    * Returns menu for a review
    *
    * @return array
    */
    private function guttermenu($sitepage = array(), $review = array(), $action = NULL) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        $level_id=0;
        if($viewer_id)
            $level_id = $viewer->level_id;

        $guttermenu = array();

        // if ($action != 'view') {
        //     $guttermenu[] = array(
        //         'label' => $this->translate("View Review"),
        //         'name' => 'View',
        //         'url' => "sitepage/review/view/" . $sitepage->page_id . "/" . $review->review_id,
        //     );
        // }
        if ($review->owner_id == $viewer_id || $level_id == 1) {
            $guttermenu[] = array(
                'label' => $this->translate("Delete Review"),
                'name' => 'delete',
                'url' => "sitepage/review/delete/" . $sitepage->page_id . "/" . $review->review_id,
            );

            $guttermenu[] = array(
                'label' => $this->translate("Edit Review"),
                'name' => 'edit_review',
                'url' => "sitepage/review/edit/" . $sitepage->page_id . "/" . $review->review_id,                
            );
        }

        if ($action == 'view') {
            $guttermenu[] = array(
                'label' => $this->translate("Comment"),
                'url' => "sitepage/review/comment/" . $sitepage->page_id . "/" . $review->review_id,
                'name' => 'comment'
            );

            $likeTable = Engine_Api::_()->getDbtable('likes', 'core');
            if ($likeTable->isLike($review, $viewer)) {
                $guttermenu[] = array(
                    'label' => $this->translate("Unlike"),
                    'url' => "sitepage/review/unlike/" . $sitepage->page_id . "/" . $review->review_id,
                    'name' => 'unlike'
                );
            } else {
                $guttermenu[] = array(
                    'label' => $this->translate("like"),
                    'url' => "sitepage/review/like/" . $sitepage->page_id . "/" . $review->review_id,
                    'name' => 'unlike'
                );
            }
        }

        return $guttermenu;
    }

}
