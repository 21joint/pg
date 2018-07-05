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
        parent::init();
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
            else if($method == 'put' || $method == 'patch'){
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
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        $id = $this->getParam("reviewID");   
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $params = array();
        $customFieldValues = array();
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $params['type'] = 'browse';
        $params['user_id'] = $this->getParam("authorID",$this->getParam("author"));
        $params['listingtype_id'] = $this->getParam("typeID",-1);
        $params['category_id'] = $this->getParam("categoryID");
        $params['subcategory_id'] = $this->getParam("subCategoryID");
        $listingTable = Engine_Api::_()->getDbTable('listings', 'sitereview');
        $listingTableName = $listingTable->info("name");
        $listingTypeTable = Engine_Api::_()->getDbtable('locations', 'sitereview');
        $listingTypeTableName = $listingTypeTable->info('name');
        $select = $listingTable->getSitereviewsSelect($params, $customFieldValues);
        if(is_string($id) && !empty($id)){
            $select->where("$listingTableName.listing_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$listingTableName.listing_id IN (?)",$id);
        }
        $topicID = $this->getParam("topicID","-1");
        if($topicID != -1){
            $select->joinLeft($listingTypeTableName,"$listingTypeTableName.listingtype_id = $listingTableName.listingtype_id",array());
            $select->where("$listingTypeTableName.gg_topic_id = ?",(int)$topicID);
        }
        
        $status = $this->getParam("status","1");
        if($status != ''){
            if($status == 1){
                $select->where("$listingTableName.draft = ?",0);
                $select->where("$listingTableName.approved = ?",1);
            }else{
                $select->where("$listingTableName.draft = ?",(!(int)$status));
            }            
        }
        
        $authorRating = $this->getParam("authorRating","-1");
        if($authorRating != -1){
            if(strstr($authorRating,">")){
                $authorRating = str_replace(">","",$authorRating);
                $select->where("$listingTableName.gg_author_product_rating > ?",(int)$authorRating);
            }else{
                $select->where("$listingTableName.gg_author_product_rating = ?",(int)$authorRating);
            }            
        }
        
        $featured = $this->getParam("featured","-1");
        if($featured != -1){
            $select->where("$listingTypeTableName.featured = ?",(int)$featured);
        }
                
        $select->where("$listingTableName.gg_deleted = ?",0);
        $select->reset("order");
        $orderByDirection = $this->getParam("orderByDirection","descending");
        $orderBy = $this->getParam("orderBy","createdDateTime");
        $orderByDirection = ($orderByDirection == "descending")?"DESC":"ASC";
        if($orderBy == "createdDateTime"){
            $select->order("creation_date $orderByDirection");
        }else if($orderBy == "publishedDateTime"){
            $select->order("approved_date $orderByDirection");
        }else if($orderBy == "likesCount"){
            $select->order("like_count $orderByDirection");
        }else if($orderBy == "commentsCount"){
            $select->order("comment_count $orderByDirection");
        }else if($orderBy == "lastModifiedDateTime"){
            $select->order("modified_date $orderByDirection");
        }else{
            $select->order("creation_date $orderByDirection");
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = $paginator->getTotalItemCount();        
        $response['Results'] = array();
        foreach($paginator as $sitereview){
            $response['resourceType'] = $sitereview->getType();
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
        
        $form = Engine_Api::_()->getApi("V1_Forms","pgservicelayer")->getReviewForm();
        $validators = Engine_Api::_()->getApi("V1_Validators","pgservicelayer")->getReviewValidators();
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
            'body' => $this->getParam("longDescription"),
            'gg_author_product_rating' => (int)$this->getParam("authorRating",0),
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
            
            if(!empty($sitereview->photo_id)){
                Engine_Api::_()->getDbTable('files', 'pgservicelayer')->updatePhotoParent($sitereview->photo_id,$sitereview);
            }
            
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
            
            $listing_id = $sitereview->getIdentity();
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        
        $tableOtherinfo = Engine_Api::_()->getDbTable('otherinfo', 'sitereview');
        $db->beginTransaction();
        try {
            $row = $tableOtherinfo->getOtherinfo($listing_id);
            $overview = '';
            if (isset($values['longDescription'])) {
                $overview = $values['longDescription'];
            }
            if (empty($row))
                Engine_Api::_()->getDbTable('otherinfo', 'sitereview')->insert(array(
                    'listing_id' => $listing_id,
                    'overview' => $overview
                )); //COMMIT
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($e);
        }

        if (!empty($listing_id)) {
            $sitereview->setLocation();
        }
        
        $db->beginTransaction();
        try {

            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting'))
                $sitereview_pending = $sitereview->pending;
            else
                $sitereview_pending = 0;

            if ($sitereview->draft == 0 && $sitereview->search && time() >= strtotime($sitereview->creation_date) && empty($sitereview_pending) && $sitereview->approved) {
                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $sitereview, 'sitereview_new_listtype_' . $listingtype_id);
                
                if ($action != null) {
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sitereview);
                }
            }

            $users = Engine_Api::_()->getDbtable('editors', 'sitereview')->getAllEditors($listingtype_id, 0, 1);

            foreach ($users as $user_ids) {

                $subjectOwner = Engine_Api::_()->getItem('user', $user_ids->user_id);

                if (!($subjectOwner instanceof User_Model_User)) {
                    continue;
                }

                $host = $_SERVER['HTTP_HOST'];
                $newVar = _ENGINE_SSL ? 'https://' : 'http://';
                $object_link = $newVar . $host . $sitereview->getHref();

                Engine_Api::_()->getApi('mail', 'core')->sendSystem($subjectOwner->email, 'SITEREVIEW_LISTING_CREATION_EDITOR', array(
                    'listing_type' => strtolower($listingType->title_singular),
                    'object_link' => $object_link,
                    'object_title' => $sitereview->getTitle(),
                    'object_description' => $sitereview->getDescription(),
                    'queue' => true
                ));
            }

            //SEND NOTIFICATIONS FOR SUBSCRIBERS
            if ($listingType->subscription)
                Engine_Api::_()->getDbtable('subscriptions', 'sitereview')->sendNotifications($sitereview);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($e);
        }
        
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $sitereviewData = $responseApi->getReviewData($sitereview);
        $response['ResultCount'] = 1;
        $response['resourceType'] = $sitereview->getType();
        $response['Results'] = array($sitereviewData);
        $this->respondWithSuccess($response);
        
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
        
        $id = $this->getParam("reviewID");
        $sitereview = Engine_Api::_()->getItem("sitereview_listing",$id);
        if(empty($sitereview)){
            $this->respondWithError('no_record');
        }
        
        $form = Engine_Api::_()->getApi("V1_Forms","pgservicelayer")->getReviewForm();
        $validators = Engine_Api::_()->getApi("V1_Validators","pgservicelayer")->getReviewValidators();
        $values = $data = $this->getAllParams();
        
        foreach ($form as $element) {
            if (isset($data[$element['name']])){
                $values[$element['name']] = $data[$element['name']];
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
            'body' => $this->getParam("longDescription"),
            'gg_author_product_rating' => (int)$this->getParam("authorRating",0),
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
            $sitereview->modified_date = date('Y-m-d H:i:s');
            $sitereview->setFromArray($values);
            
            if(!empty($sitereview->photo_id)){
                Engine_Api::_()->getDbTable('files', 'pgservicelayer')->updatePhotoParent($sitereview->photo_id,$sitereview);
            }

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
            $listing_id = $sitereview->getIdentity();            
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        
        $sitereview->setLocation();
        $db->beginTransaction();
        try {
            $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
            foreach ($actionTable->getActionsByObject($sitereview) as $action) {
                $actionTable->resetActivityBindings($action);
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $sitereviewData = $responseApi->getReviewData($sitereview);
        $response['ResultCount'] = 1;
        $response['resourceType'] = $sitereview->getType();
        $response['Results'] = array($sitereviewData);
        $this->respondWithSuccess($response);
    }
    public function deleteAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = !empty($viewer_id) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        $id = $this->getParam("reviewID");
        $idsArray = (array)$id;
        if(is_string($id) && !empty($id)){
            $idsArray = array($id);
        }
        $sitereviews = Engine_Api::_()->getItemMulti("sitereview_listing",$idsArray);
        if (empty($sitereviews)) {
            $this->respondWithError('no_record');
        }
        $table = Engine_Api::_()->getItemTable('sitereview_listing');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try {
            foreach($sitereviews as $sitereview){
                $canDelete = Engine_Api::_()->authorization()->getPermission($level_id, 'sitereview_listing', "delete_listtype_".$sitereview->listingtype_id);
                if (!$canDelete) {
                    $this->respondWithError('unauthorized');
                }
                $sitereview->gg_deleted = 1;
                $sitereview->save();
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        $this->successResponseNoContent('no_content');
    }
    
    public function categoriesAction(){
        $listingTypeTable = Engine_Api::_()->getDbTable('listingtypes', 'sitereview');
        $listingTypeTableName = $listingTypeTable->info('name');
        $select = $listingTypeTable->select()->from($listingTypeTableName, array('title_plural','title_singular', 'listingtype_id'))
                ->where('visible = ?',1)->order("order ASC")->order("listingtype_id ASC");
        $listingTypes = $listingTypeTable->fetchAll($select);
        
        $table = Engine_Api::_()->getDbTable("categories","sitereview");
        
        $response['ResultCount'] = count($listingTypes);
        $response['Results'] = array();
        foreach($listingTypes as $listingType){
            $categories = $table->getCategoriesList($listingType->getIdentity(),0);
            $categoriesArray = array();
            foreach($categories as $category){
                $categoryArray = array(
                    'category' => $category->getTitle(),
                    'categoryID' => (string)$category->getIdentity(),
                );
                $subcategories = $table->getCategoriesList($listingType->getIdentity(),$category->getIdentity());
                $subcategoriesArray = array();
                foreach($subcategories as $subcategory){
                    $subcategoryArray = array(
                        'category' => $subcategory->getTitle(),
                        'categoryID' => (string)$subcategory->getIdentity(),
                    );
                    $subcategoriesArray[] = $subcategoryArray;
                }
                $categoryArray['subcategories'] = $subcategoriesArray;
                $categoriesArray[] = $categoryArray;
            }
            $response['Results'][] = array(
                'type' => $listingType->getTitle(),
                'typeID' => (string)$listingType->getIdentity(),
                'categories' => $categoriesArray
            );
        }
        $this->respondWithSuccess($response);
    }
}
