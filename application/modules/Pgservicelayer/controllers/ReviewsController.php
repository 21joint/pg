<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_ReviewsController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        $timezone = Engine_Api::_()->getApi('settings', 'core')->core_locale_timezone;
        $viewer   = Engine_Api::_()->user()->getViewer();
        $defaultLocale = $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en_US');
        $defaultLocaleObj = new Zend_Locale($defaultLocale);
        Zend_Registry::set('LocaleDefault', $defaultLocaleObj);

        if ($viewer->getIdentity()) {
            $timezone = $viewer->timezone;
        }
        Zend_Registry::set('timezone', $timezone);
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();
    }
    public function indexAction(){
        try{
            $method = strtolower($this->getRequest()->getMethod());
            if($method == 'get'){
                $this->getAction();
            }
            else if($method == 'post'){
                $this->postAction();
            }
            else if($method == 'put'){
                $this->putAction();
            }
            else if($method == 'delete'){
                $this->deleteAction();
            }
            else{
                $this->respondWithError('invalid_method');
            }
        } catch (Exception $ex) {
            $this->respondWithServerError($ex);
        }
    }
    public function getAction(){
        $id = $this->getParam("id");   
        $responseApi = Engine_Api::_()->getApi("response","pgservicelayer");
        $params = array();
        $customFieldValues = array();
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);
        $params['type'] = 'browse';
        $listingTable = Engine_Api::_()->getDbTable('listings', 'sitereview');
        $listingTableName = $listingTable->info("name");
        $select = $listingTable->getSitereviewsSelect($params, $customFieldValues);
        if(is_string($id)){
            $select->where("$listingTableName.listing_id = ?",$id);
        }else if(is_array($id)){
            $select->where("$listingTableName.listing_id IN (?)",$id);
        }
        $select->where("$listingTableName.gg_deleted = ?",0);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['Results'] = array();
        foreach($paginator as $sitereview){
            $response['Results'][] = $responseApi->getReviewData($sitereview);
        }
        $this->respondWithSuccess($response);
    }
    public function postAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = !empty($viewer_id) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        $listingtype_id = $this->getParam("typeID",0);
        $listingType = Engine_Api::_()->getItem("sitereview_listingtype",$listingtype_id);
        if(empty($listingType)){
            $this->respondWithError('no_record',$this->translate("Review category not specified"));
        }
        $canCreate = Engine_Api::_()->authorization()->getPermission($level_id, 'sitereview_listing', "create_listtype_$listingtype_id");
        if (!$canCreate) {
            $this->respondWithError('unauthorized');
        }
        
        $form = Engine_Api::_()->getApi("forms","pgservicelayer")->getReviewForm();
        $validators = Engine_Api::_()->getApi("validators","pgservicelayer")->getReviewValidators();
        $values = $data = $_REQUEST;

        foreach ($form as $element) {
            if (isset($_REQUEST[$element['name']])){
                $values[$element['name']] = $_REQUEST[$element['name']];
            }
        }
        $values['validators'] = $validators;
        $validationMessage = $this->isValid($values);
        if (!empty($validationMessage) && @is_array($validationMessage)) {
            $this->respondWithValidationError('validation_fail', $validationMessage);
        }
        
        //Values for database
        $values = array(
            'listingtype_id' => $this->getParam("typeID"),
            'title' => $this->getParam("title"),
            'category_id' => $this->getParam("categoryID"),
            'subcategory_id' => $this->getParam("subCategoryID"),
            'body' => $this->getParam("summaryDescription"),
            'gg_author_product_rating' => (int)$this->getParam("ownerRating",0),
            'photo_id' => (int)$this->getParam("photoID",0),
            'search' => (int)$this->getParam("search",0),
            'auth_view' => $this->getParam("authView","everyone"),
            'auth_comment' => $this->getParam("authComment","everyone"),
            'auth_topic' => $this->getParam("authTopic","everyone"),
            'auth_photo' => $this->getParam("authPhoto","everyone"),
            'auth_video' => $this->getParam("authVideo","everyone"),
        );
        $table = Engine_Api::_()->getItemTable('sitereview_listing');
        $db = $table->getAdapter();
        $db->beginTransaction();
        $user_level = $viewer->level_id;
        try{
            //Create sitereview
            if (!Engine_Api::_()->sitereview()->hasPackageEnable()) {
                $values = array_merge($values, array(
                    'listingtype_id' => $listingtype_id,
                    'owner_type' => $viewer->getType(),
                    'owner_id' => $viewer_id,
                    'featured' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "featured_listtype_$listingtype_id"),
                    'sponsored' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "sponsored_listtype_$listingtype_id"),
                    'approved' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "approved_listtype_$listingtype_id")
                ));
            } else {
                $values = array_merge($values, array(
                    'listingtype_id' => $listingtype_id,
                    'owner_type' => $viewer->getType(),
                    'owner_id' => $viewer_id,
                    'featured' => 0,
                    'sponsored' => 0
                ));

                $values['approved'] = 0;                    
            }
            
            if (empty($values['subcategory_id'])) {
                $values['subcategory_id'] = 0;
            }

            if (empty($values['subsubcategory_id'])) {
                $values['subsubcategory_id'] = 0;
            }
            
            $expiry_setting = Engine_Api::_()->sitereview()->expirySettings($listingtype_id);
            if ($expiry_setting == 1 && $values['end_date_enable'] == 1) {
                // Convert times
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $end = strtotime($values['end_date']);
                date_default_timezone_set($oldTz);
                $values['end_date'] = date('Y-m-d H:i:s', $end);
            } elseif (isset($values['end_date'])) {
                unset($values['end_date']);
            }

            if (Engine_Api::_()->sitereview()->listBaseNetworkEnable()) {
                if (isset($values['networks_privacy']) && !empty($values['networks_privacy'])) {
                    if (in_array(0, $values['networks_privacy'])) {
                        unset($values['networks_privacy']);
                    }
                }
            }
            $values['gg_author_product_rating'] = (int)$values['owner_rating'];
            $sitereview = $table->createRow();
            $sitereview->setFromArray($values);

            if ($sitereview->approved) {
                $sitereview->approved_date = date('Y-m-d H:i:s');
            }
            
            //START PACKAGE WORK
            if (!empty($sitereview->approved)) {
                if (isset($sitereview->pending))
                    $sitereview->pending = 0;
                $sitereview->approved_date = date('Y-m-d H:i:s');
                if (Engine_Api::_()->sitereview()->hasPackageEnable()) {
                    $sitereview->expiration_date = '2250-01-01 00:00:00';
                }
            }
            //END PACKAGE WORK

            $sitereview->save();
            
            $categoryIds = array();
            $categoryIds[] = $sitereview->category_id;
            $categoryIds[] = $sitereview->subcategory_id;
            $categoryIds[] = $sitereview->subsubcategory_id;
            $sitereview->profile_type = Engine_Api::_()->getDbTable('categories', 'sitereview')->getProfileType($categoryIds, 0, 'profile_type');

            //NOT SEARCHABLE IF SAVED IN DRAFT MODE
            if (!empty($sitereview->draft)) {
                $sitereview->search = 0;
            }

            $sitereview->save();
            
            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

            if (empty($values['auth_view'])) {
                $values['auth_view'] = "everyone";
            }

            if (empty($values['auth_comment'])) {
                $values['auth_comment'] = "everyone";
            }

            $viewMax = array_search($values['auth_view'], $roles);
            $commentMax = array_search($values['auth_comment'], $roles);

            foreach ($roles as $i => $role) {
                $auth->setAllowed($sitereview, $role, "view_listtype_$listingtype_id", ($i <= $viewMax));
                $auth->setAllowed($sitereview, $role, "view", ($i <= $viewMax));
                $auth->setAllowed($sitereview, $role, "comment_listtype_$listingtype_id", ($i <= $commentMax));
                $auth->setAllowed($sitereview, $role, "comment", ($i <= $commentMax));
            }

            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered');

            if (empty($values['auth_topic'])) {
                $values['auth_topic'] = "registered";
            }

            if (empty($values['auth_photo'])) {
                $values['auth_photo'] = "registered";
            }

            if (!isset($values['auth_video']) && empty($values['auth_video'])) {
                $values['auth_video'] = "registered";
            }

            if (isset($values['auth_event']) && empty($values['auth_event'])) {
                $values['auth_event'] = "registered";
            }

            if (isset($values['auth_event']) && !empty($values['auth_event'])) {
                $eventMax = array_search($values['auth_event'], $roles);
                foreach ($roles as $i => $roles) {
                    $auth->setAllowed($sitereview, $roles, "event_listtype_$listingtype_id", ($i <= $eventMax));
                }
            }

            if (isset($values['auth_sprcreate']) && empty($values['auth_sprcreate'])) {
                $values['auth_sprcreate'] = "registered";
            }

            if (isset($values['auth_sprcreate']) && !empty($values['auth_sprcreate'])) {
                $projectMax = array_search($values['auth_sprcreate'], $roles);
                foreach ($roles as $i => $roles) {
                    $auth->setAllowed($sitereview, $roles, "sprcreate_listtype_$listingtype_id", ($i <= $projectMax));
                }
            }

            $topicMax = array_search($values['auth_topic'], $roles);
            $photoMax = array_search($values['auth_photo'], $roles);
            $videoMax = array_search($values['auth_video'], $roles);
            foreach ($roles as $i => $roles) {
                $auth->setAllowed($sitereview, $roles, "topic_listtype_$listingtype_id", ($i <= $topicMax));
                $auth->setAllowed($sitereview, $roles, "photo_listtype_$listingtype_id", ($i <= $photoMax));
                $auth->setAllowed($sitereview, $roles, "video_listtype_$listingtype_id", ($i <= $videoMax));
            }
            
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("response","pgservicelayer");
            $response = $responseApi->getReviewData($sitereview);
            $this->respondWithSuccess($response);
        } catch (Exception $ex) {
            $db->rollBack();
        }
        
    }
    public function putAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = !empty($viewer_id) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        $listingtype_id = $this->getParam("typeID",0);
        $listingType = Engine_Api::_()->getItem("sitereview_listingtype",$listingtype_id);
        if(empty($listingType)){
            $this->respondWithError('no_record',$this->translate("Review category not specified"));
        }
        $canCreate = Engine_Api::_()->authorization()->getPermission($level_id, 'sitereview_listing', "create_listtype_$listingtype_id");
        if (!$canCreate) {
            $this->respondWithError('unauthorized');
        }
        
        $id = $this->getParam("id");
        $sitereview = Engine_Api::_()->getItem("sitereview_listing",$listingtype_id);
        
        $form = Engine_Api::_()->getApi("forms","pgservicelayer")->getReviewForm();
        $validators = Engine_Api::_()->getApi("validators","pgservicelayer")->getReviewValidators();
        $values = $data = $_REQUEST;

        foreach ($form as $element) {
            if (isset($_REQUEST[$element['name']])){
                $values[$element['name']] = $_REQUEST[$element['name']];
            }
        }
        $values['validators'] = $validators;
        $validationMessage = $this->isValid($values);
        if (!empty($validationMessage) && @is_array($validationMessage)) {
            $this->respondWithValidationError('validation_fail', $validationMessage);
        }
        
        //Values for database
        $values = array(
            'listingtype_id' => $this->getParam("typeID"),
            'title' => $this->getParam("title"),
            'category_id' => $this->getParam("categoryID"),
            'subcategory_id' => $this->getParam("subCategoryID"),
            'body' => $this->getParam("summaryDescription"),
            'gg_author_product_rating' => (int)$this->getParam("ownerRating",0),
            'photo_id' => (int)$this->getParam("photoID",0),
            'search' => (int)$this->getParam("search",0),
            'auth_view' => $this->getParam("authView","everyone"),
            'auth_comment' => $this->getParam("authComment","everyone"),
            'auth_topic' => $this->getParam("authTopic","everyone"),
            'auth_photo' => $this->getParam("authPhoto","everyone"),
            'auth_video' => $this->getParam("authVideo","everyone"),
        );
        $table = Engine_Api::_()->getItemTable('sitereview_listing');
        $db = $table->getAdapter();
        $db->beginTransaction();
        $user_level = $viewer->level_id;
        try{
            //Create sitereview
            if (!Engine_Api::_()->sitereview()->hasPackageEnable()) {
                $values = array_merge($values, array(
                    'listingtype_id' => $listingtype_id,
                    'owner_type' => $viewer->getType(),
                    'owner_id' => $viewer_id,
                    'featured' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "featured_listtype_$listingtype_id"),
                    'sponsored' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "sponsored_listtype_$listingtype_id"),
                    'approved' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "approved_listtype_$listingtype_id")
                ));
            } else {
                $values = array_merge($values, array(
                    'listingtype_id' => $listingtype_id,
                    'owner_type' => $viewer->getType(),
                    'owner_id' => $viewer_id,
                    'featured' => 0,
                    'sponsored' => 0
                ));

                $values['approved'] = 0;                    
            }
            
            if (empty($values['subcategory_id'])) {
                $values['subcategory_id'] = 0;
            }

            if (empty($values['subsubcategory_id'])) {
                $values['subsubcategory_id'] = 0;
            }
            
            $expiry_setting = Engine_Api::_()->sitereview()->expirySettings($listingtype_id);
            if ($expiry_setting == 1 && $values['end_date_enable'] == 1) {
                // Convert times
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $end = strtotime($values['end_date']);
                date_default_timezone_set($oldTz);
                $values['end_date'] = date('Y-m-d H:i:s', $end);
            } elseif (isset($values['end_date'])) {
                unset($values['end_date']);
            }

            if (Engine_Api::_()->sitereview()->listBaseNetworkEnable()) {
                if (isset($values['networks_privacy']) && !empty($values['networks_privacy'])) {
                    if (in_array(0, $values['networks_privacy'])) {
                        unset($values['networks_privacy']);
                    }
                }
            }
            $values['gg_author_product_rating'] = (int)$values['owner_rating'];
            $sitereview->modified_date = date('Y-m-d H:i:s');
            $sitereview->setFromArray($values);

            if ($sitereview->approved) {
                $sitereview->approved_date = date('Y-m-d H:i:s');
            }
            
            //START PACKAGE WORK
            if (!empty($sitereview->approved)) {
                if (isset($sitereview->pending))
                    $sitereview->pending = 0;
                $sitereview->approved_date = date('Y-m-d H:i:s');
                if (Engine_Api::_()->sitereview()->hasPackageEnable()) {
                    $sitereview->expiration_date = '2250-01-01 00:00:00';
                }
            }
            //END PACKAGE WORK

            $sitereview->save();
            
            $categoryIds = array();
            $categoryIds[] = $sitereview->category_id;
            $categoryIds[] = $sitereview->subcategory_id;
            $categoryIds[] = $sitereview->subsubcategory_id;
            $sitereview->profile_type = Engine_Api::_()->getDbTable('categories', 'sitereview')->getProfileType($categoryIds, 0, 'profile_type');

            //NOT SEARCHABLE IF SAVED IN DRAFT MODE
            if (!empty($sitereview->draft)) {
                $sitereview->search = 0;
            }

            $sitereview->save();
            
            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

            if (empty($values['auth_view'])) {
                $values['auth_view'] = "everyone";
            }

            if (empty($values['auth_comment'])) {
                $values['auth_comment'] = "everyone";
            }

            $viewMax = array_search($values['auth_view'], $roles);
            $commentMax = array_search($values['auth_comment'], $roles);

            foreach ($roles as $i => $role) {
                $auth->setAllowed($sitereview, $role, "view_listtype_$listingtype_id", ($i <= $viewMax));
                $auth->setAllowed($sitereview, $role, "view", ($i <= $viewMax));
                $auth->setAllowed($sitereview, $role, "comment_listtype_$listingtype_id", ($i <= $commentMax));
                $auth->setAllowed($sitereview, $role, "comment", ($i <= $commentMax));
            }

            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered');

            if (empty($values['auth_topic'])) {
                $values['auth_topic'] = "registered";
            }

            if (empty($values['auth_photo'])) {
                $values['auth_photo'] = "registered";
            }

            if (!isset($values['auth_video']) && empty($values['auth_video'])) {
                $values['auth_video'] = "registered";
            }

            if (isset($values['auth_event']) && empty($values['auth_event'])) {
                $values['auth_event'] = "registered";
            }

            if (isset($values['auth_event']) && !empty($values['auth_event'])) {
                $eventMax = array_search($values['auth_event'], $roles);
                foreach ($roles as $i => $roles) {
                    $auth->setAllowed($sitereview, $roles, "event_listtype_$listingtype_id", ($i <= $eventMax));
                }
            }

            if (isset($values['auth_sprcreate']) && empty($values['auth_sprcreate'])) {
                $values['auth_sprcreate'] = "registered";
            }

            if (isset($values['auth_sprcreate']) && !empty($values['auth_sprcreate'])) {
                $projectMax = array_search($values['auth_sprcreate'], $roles);
                foreach ($roles as $i => $roles) {
                    $auth->setAllowed($sitereview, $roles, "sprcreate_listtype_$listingtype_id", ($i <= $projectMax));
                }
            }

            $topicMax = array_search($values['auth_topic'], $roles);
            $photoMax = array_search($values['auth_photo'], $roles);
            $videoMax = array_search($values['auth_video'], $roles);
            foreach ($roles as $i => $roles) {
                $auth->setAllowed($sitereview, $roles, "topic_listtype_$listingtype_id", ($i <= $topicMax));
                $auth->setAllowed($sitereview, $roles, "photo_listtype_$listingtype_id", ($i <= $photoMax));
                $auth->setAllowed($sitereview, $roles, "video_listtype_$listingtype_id", ($i <= $videoMax));
            }
            
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("response","pgservicelayer");
            $response = $responseApi->getReviewData($sitereview);
            $this->respondWithSuccess($response);
        } catch (Exception $ex) {
            $db->rollBack();
        }
        
    }
    public function deleteAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = !empty($viewer_id) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        $id = $this->getParam("id");
        $idsArray = (array)$id;
        if(is_string($id)){
            $idsArray = array($id);
        }
        $sitereviews = Engine_Api::_()->getItemMulti("sitereview_listing",$idsArray);
        if (empty($sitereviews)) {
            $this->respondWithError('no_record');
        }
        foreach($sitereviews as $sitereview){
            $canDelete = Engine_Api::_()->authorization()->getPermission($level_id, 'sitereview_listing', "delete_listtype_".$sitereview->listingtype_id);
            if (!$canDelete) {
//                $this->respondWithError('unauthorized');
            }
            $sitereview->gg_deleted = 1;
            $sitereview->save();
        }
        $this->successResponseNoContent('no_content');
    }
    
    public function categoriesAction(){
        $table = Engine_Api::_()->getDbTable("categories","sitereview");
        $categories = $table->getCategoriesList(0,0);
        $response['ResultCount'] = count($categories);
        $response['Results'] = array();
        foreach($categories as $category){
            $listingType = Engine_Api::_()->getItem('sitereview_listingtype', $category->listingtype_id);
            $categoryArray = array(
                'type' => $listingType->getTitle(),
                'typeID' => (string)$listingType->getIdentity(),
                'category' => $category->getTitle(),
                'categoryID' => (string)$category->getIdentity(),
                'subCategory' => '',
                'subCategoryID' => ''
            );
            $response['Results'][] = $categoryArray;
            $subcategories = $table->getCategoriesList($listingType->getIdentity(),$category->getIdentity());
            foreach($subcategories as $subcategory){
                $categoryArray = array(
                    'type' => $listingType->getTitle(),
                    'typeID' => (string)$listingType->getIdentity(),
                    'category' => $category->getTitle(),
                    'categoryID' => (string)$category->getIdentity(),
                    'subCategory' => '',
                    'subCategoryID' => ''
                );
                $categoryArray['subCategory'] = $subcategory->getTitle();
                $categoryArray['subCategoryID'] = (string)$subcategory->getIdentity();
                $response['Results'][] = $categoryArray;
            }
        }
        $this->respondWithSuccess($response);
    }
    
    public function rankingAction(){
        $contributionRangeType = $this->getParam("contributionRangeType","Overall");
        $orderBy = $this->getParam("orderBy","contributionPoints");
        
        $usersTable = Engine_Api::_()->getDbTable("users","user");
        $select = $usersTable->select()
            ->where("search = ?", 1)
            ->where("enabled = ?", 1)
            ;
        
        //Contribution Range
        if(strtolower($contributionRangeType) == "week" || strtolower($contributionRangeType) == "month"){
//            $creditsTable = Engine_Api::_()->getDbtable('credits','sitecredit');
//            $creditsTableName = $creditsTable->info("name");
            
        }
        //Sort data
        //Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
        if($orderBy == 'contributionPoints'){
            $select->order("gg_contribution DESC");
        }elseif($orderBy == 'reviewCount'){
            $select->order("gg_review_count DESC");
        }elseif($orderBy == 'questionCount'){
            $select->order("gg_question_count DESC");
        }elseif($orderBy == 'followers'){
            $select->order("gg_followers_count DESC");
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->getParam("limit",10));
        $paginator->setCurrentPageNumber($this->getParam("page",1));
        
        $response = array(
            'contributionRangeType' => $contributionRangeType,
            'orderBy' => $orderBy,
            'contributions' => array(),
        );
        $api = Engine_Api::_()->sdparentalguide();
        foreach($paginator as $user){
            $temp = array(
                'contributorID' => $user->getIdentity(),
                'contributionPoints' => $user->gg_contribution,
                'reviewCount' => $user->gg_review_count,
                'questionCount' => $user->gg_question_count,
                'answerCount' => 0, //Don't have answers count in users table for now.
                'followers' => $user->gg_followers_count,
                'title' => $user->getTitle(),
            );
            $contentImages = $api->getContentImage($user);
            $temp = array_merge($temp,$contentImages);
            $response['contributions'][] = $temp;
        }
        $this->respondWithSuccess($response);
    }
}
