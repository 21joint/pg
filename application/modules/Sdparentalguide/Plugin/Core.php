<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Core extends Zend_Controller_Plugin_Abstract
{
    public function isAllowedAuditing($payload){
        $className = get_class($payload);
        $allowed = false;
        switch ($className){
            case 'Sitereview_Model_Listing':
            case 'User_Model_User':
            case 'Sitereview_Model_Listingtype':
            case 'Sdparentalguide_Model_AssignedBadge':
            case 'Sdparentalguide_Model_Topic':
            case 'Sdparentalguide_Model_Preference':
            case 'Sitehashtag_Model_Tag':
            case 'Sdparentalguide_Model_Statistic':
            case 'Sdparentalguide_Model_Search':
            case 'Sdparentalguide_Model_ListingTopic':
            case 'Sdparentalguide_Model_SearchTerm':
            case 'Sdparentalguide_Model_SearchTermsAlias':
            case 'Sdparentalguide_Model_ListingRating':
            case 'Sdparentalguide_Model_SearchAnalytic':
            case 'Ggcommunity_Model_Question':
            case 'Ggcommunity_Model_Answer':
            case 'Core_Model_Comment':
            case 'Ggcommunity_Model_Vote':
            case 'Core_Model_Like':
            case 'Nestedcomment_Model_Dislike':
            case 'Pgservicelayer_Model_View':
                $allowed = true;
                break;
            default:
                $allowed = false;
                break;
        }
        return $allowed;
    }
    public function onActivityActionCreateAfter($event){
        $payload = $event->getPayload();
        $viewer = Engine_Api::_()->user()->getViewer();
        $auditingSaved = Zend_Registry::isRegistered("Auditing_Created_Activity");
        try{
            //This will fix max level error
            if($auditingSaved && Zend_Registry::get("Auditing_Created_Activity") > 20){
                return;
            }

        } catch (Exception $ex) {
            //Silent
//            throw $ex;
        }
        if($auditingSaved){
            Zend_Registry::set("Auditing_Created_Activity", Zend_Registry::get("Auditing_Created_Activity")+1);
        }else{
            Zend_Registry::set("Auditing_Created_Activity",1);
        }
    }
    public function onItemCreateBefore($event){
        $payload = $event->getPayload();
        $viewer = Engine_Api::_()->user()->getViewer();
        $auditingSaved = Zend_Registry::isRegistered("Auditing_Created");
        try{
            //This will fix max level error
            if($auditingSaved && Zend_Registry::get("Auditing_Created") > 40){
                return;
            }
            if($this->isAllowedAuditing($payload)){
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $creationDate = time();
                date_default_timezone_set($oldTz);
                $currentDate = date('Y-m-d H:i:s', $creationDate);

                $this->updateAuditing($payload);
                $payload->gg_user_created = $viewer->getIdentity();
                $payload->gg_dt_created = $currentDate;
            }

            if( $payload instanceof Sitecredit_Model_Credit ) {
//                $this->updateUserCredits($payload);
                $this->updateTransactionTopic($payload);
            }

            if( $payload instanceof Sitereview_Model_Listing ) {
                $owner = $payload->getOwner();
                if(!empty($owner)){
                    if($owner->gg_featured){
                        $payload->featured = 1;
                    }
                }
            }

        } catch (Exception $ex) {
            //Silent
//            throw $ex;
        }
        if($auditingSaved){
            Zend_Registry::set("Auditing_Created", Zend_Registry::get("Auditing_Created")+1);
        }else{
            Zend_Registry::set("Auditing_Created",1);
        }
    }
    public function onItemCreateAfter($event){
        $payload = $event->getPayload();
        $viewer = Engine_Api::_()->user()->getViewer();
        $auditingSaved = Zend_Registry::isRegistered("Auditing_Created_After");
        try{
            //This will fix max level error
            if($auditingSaved && Zend_Registry::get("Auditing_Created_After") > 40){
                return;
            }

            if( $payload instanceof Sitereview_Model_Listing && $payload->approved) {
                $this->updateUserData($viewer,array('gg_review_count' => (++$viewer->gg_review_count)));
            }

            if( $payload instanceof Ggcommunity_Model_Question && $payload->approved && !$payload->draft) {
                $this->updateUserData($viewer,array('gg_question_count' => (++$viewer->gg_question_count)));
            }

            if( $payload instanceof Ggcommunity_Model_Answer) {
                $this->updateUserData($viewer,array('gg_answer_count' => (++$viewer->gg_answer_count)));
            }

            if( $payload instanceof Sitecredit_Model_Credit) {
                $this->updateUserCredits($payload);
            }

        } catch (Exception $ex) {
            //Silent
            throw $ex;
        }
        if($auditingSaved){
            Zend_Registry::set("Auditing_Created_After", Zend_Registry::get("Auditing_Created_After")+1);
        }else{
            Zend_Registry::set("Auditing_Created_After",1);
        }
    }
    public function updateUserCredits($payload){
        try{
            $user = Engine_Api::_()->user()->getUser($payload->user_id);
//            if(!$user->getIdentity()){
//                return;
//            }
            $creditsTable = Engine_Api::_()->getDbtable('credits','sdparentalguide');
            $row = $creditsTable->getUserActivityCount($user);
            $userCredits = 0;
            $userActivities = 0;
            $level_id = $user->level_id;
            if(!empty($row)){
                $userCredits = $row->credit;
                $userActivities = $row->activities;
            }
            $data = array('gg_contribution' => $userCredits,'gg_activities' => $userActivities,'gg_contribution_updated' => 1,'level_id' => $level_id);
            $table = Engine_Api::_()->getDbtable('users','user');
            $api = Engine_Api::_()->sdparentalguide();
            $badge = $api->getUserBadge($userCredits);
            if(!empty($badge)){
                $data['gg_contribution_level'] = $badge->gg_contribution_level;
                if(!$user->isAdmin() && $badge->gg_level_id > 0){
                    $data['level_id'] = $badge->gg_level_id;
                }
            }
            $table->update($data,array('user_id = ?' => $payload->user_id));
//            $user->gg_contribution = $userCredits;
//            $user->gg_activities = $userActivities;
//            $user->gg_contribution_updated = 1;
//            $user->save();

        } catch (Exception $ex) {
            //Silent
            throw $ex;
        }
    }
    public function onItemUpdateAfter($event){
        $auditingSaved = Zend_Registry::isRegistered("Auditing_Updated");
        try{
            $payload = $event->getPayload();
            $viewer = Engine_Api::_()->user()->getViewer();
            if($auditingSaved && Zend_Registry::get("Auditing_Updated") > 10){
                return;
            }
            if($payload->getType() != "user" && $payload->getType() != "credit"){
                $this->updateExistingTransactionTopic($payload);
            }

            //This will fix max level error by saving data mannually.
            if($this->isAllowedAuditing($payload)) {
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $creationDate = time();
                date_default_timezone_set($oldTz);
                $currentDate = date('Y-m-d H:i:s', $creationDate);

                $params = $this->getAuditingParams($payload);
                $params['gg_user_lastmodified'] = $viewer->getIdentity();
                $params['gg_dt_lastmodified'] = $currentDate;
                $table = $payload->getTable();
                $info = $table->info("primary");
                if(!empty($info) && is_array($info)){
                    $where = array();
                    foreach($info as $primaryKey){
                        if(!isset($payload->$primaryKey)){
                            continue;
                        }

                        $where["$primaryKey = ?"] = $payload->$primaryKey;
                    }
                    if(!empty($where)){
                        $table->update($params,$where);
                    }
                    Zend_Registry::set("Auditing_Updated",1);
                }
            }
        } catch (Exception $ex) {
            //Silent
        }
        if($auditingSaved){
            Zend_Registry::set("Auditing_Updated", Zend_Registry::get("Auditing_Updated")+1);
        }else{
            Zend_Registry::set("Auditing_Updated",1);
        }
    }
    public function onItemUpdateBefore($event){
        $auditingSaved = Zend_Registry::isRegistered("Auditing_Updated_Before");
        try{
            $payload = $event->getPayload();
            $viewer = Engine_Api::_()->user()->getViewer();
            if($auditingSaved && Zend_Registry::get("Auditing_Updated_Before") > 10){
                return;
            }
            //This will fix max level error by saving data mannually.
            if($this->isAllowedAuditing($payload)) {
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $creationDate = time();
                date_default_timezone_set($oldTz);
                $currentDate = date('Y-m-d H:i:s', $creationDate);

                $params = $this->getAuditingParams($payload);
                $params['gg_user_lastmodified'] = $viewer->getIdentity();
                $params['gg_dt_lastmodified'] = $currentDate;
                $table = $payload->getTable();
                $info = $table->info("primary");
                if(!empty($info) && is_array($info)){
                    $where = array();
                    foreach($info as $primaryKey){
                        if(!isset($payload->$primaryKey)){
                            continue;
                        }

                        $where["$primaryKey = ?"] = $payload->$primaryKey;
                    }
                    if(!empty($where)){
                        $table->update($params,$where);
                    }
                    Zend_Registry::set("Auditing_Updated_Before",1);
                }

            }

            $modifiedFields = $payload->getModifiedFields();

            if( $payload instanceof Sitereview_Model_Listing && $payload->approved && isset($modifiedFields['approved'])) {
                $this->updateUserData($payload->getOwner(),array('gg_review_count' => (++$payload->getOwner()->gg_review_count)));
            }

            if( $payload instanceof Sitereview_Model_Listing && !$payload->approved && isset($modifiedFields['approved'])) {
                $this->updateUserData($payload->getOwner(),array('gg_review_count' => (--$payload->getOwner()->gg_review_count)));
            }

            if( $payload instanceof Ggcommunity_Model_Question && (isset($modifiedFields['approved']) || isset($modifiedFields['draft']))) {
                if($payload->approved && !$payload->draft){
                    $payloadUser = Engine_Api::_()->user()->getUser($payload->user_id);
                    $this->updateUserData($payloadUser,array('gg_question_count' => (++$payloadUser->gg_question_count)));
                }
            }
            if( $payload instanceof Ggcommunity_Model_Question && (isset($modifiedFields['approved']) || isset($modifiedFields['draft']))) {
                if((!$payload->approved || $payload->draft)){
                    $payloadUser = Engine_Api::_()->user()->getUser($payload->user_id);
                    $this->updateUserData($payloadUser,array('gg_question_count' => (--$payloadUser->gg_question_count)));
                }
            }

            $contributionLevelSaved = Zend_Registry::isRegistered("ContributionLevel_Saved");
            if( $payload instanceof User_Model_User && empty($contributionLevelSaved) && isset($modifiedFields['gg_contribution'])) {
                $api = Engine_Api::_()->sdparentalguide();
                $badge = $api->getUserBadge($payload->gg_contribution);
                if(!empty($badge)){
                    $payload->gg_contribution_level = $badge->gg_contribution_level;
                    if(!$payload->isAdmin() && $badge->gg_level_id > 0){
                        $payload->level_id = $badge->gg_level_id;
                    }
                    $payload->save();
                    Zend_Registry::set("ContributionLevel_Saved",1);
                }

            }

        } catch (Exception $ex) {
            //Silent
        }
        if($auditingSaved){
            Zend_Registry::set("Auditing_Updated_Before", Zend_Registry::get("Auditing_Updated_Before")+1);
        }else{
            Zend_Registry::set("Auditing_Updated_Before",1);
        }
    }
    public function updateAuditing($listing){
        $db = Engine_Db_Table::getDefaultAdapter();
        $ipObj = new Engine_IP();
        $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));
        $listing->gg_ip_lastmodified = $ipExpr;
        $listing->gg_guid = $listing->getGuid();
    }
    public function getAuditingParams($listing){
        $db = Engine_Db_Table::getDefaultAdapter();
        $ipObj = new Engine_IP();
        $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));
        $params = array(
            'gg_ip_lastmodified' => $ipExpr,
            'gg_guid' => $listing->getGuid()
        );
        return $params;
    }
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();
        if($moduleName == "sitecredit" && $controllerName == "index" && $actionName == "index"){
            $this->_creditManagePage();
            $request->setModuleName("sdparentalguide");
        }

        if($moduleName == "sitereview" && $controllerName == "index" && $actionName == "home"){
//            $this->replaceListingHomeWidgets();
        }

        if($moduleName == "sitereview" && $controllerName == "index" && ($actionName == "create" || $actionName == "edit")){
            $request->setControllerName("listing");
            $request->setModuleName("sdparentalguide");
        }

        if($moduleName == "user" && $controllerName == "friends"){
            $request->setModuleName("sdparentalguide");
        }

        if($moduleName == "siteusercoverphoto" && $controllerName == "friends"){
            $request->setControllerName("friendsPhoto");
            $request->setModuleName("sdparentalguide");
        }

        $searchText = $request->getParam("query");
        if(!empty($searchText) && $moduleName == "core" && $controllerName == "search" && $actionName == "index"){
            Engine_Api::_()->getDbTable('search', 'sdparentalguide')->logSearch($searchText);
        }

        if(!empty($searchText) && $moduleName == "siteadvsearch" && $controllerName == "index" && $actionName == "index"){
            Engine_Api::_()->getDbTable('search', 'sdparentalguide')->logSearch($searchText);
        }

        $router = Zend_Controller_Front::getInstance()->getRouter();
        $currentRoute = $router->getCurrentRoute();
        if(!empty($currentRoute)){
            $matchedPath = $currentRoute->getMatchedPath();
            if($matchedPath == 'members/home'){
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                return $redirector->gotoRoute(array(), "sdparentalguide_user_home", true);
            }

            $viewer = Engine_Api::_()->user()->getViewer();
            if($matchedPath == 'profile' && $viewer->getIdentity()){
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                return $redirector->gotoRoute(array('id' => $viewer->getIdentity()), "user_profile", true);
            }

            if($matchedPath == 'members'){
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                return $redirector->gotoRoute(array(), "sdparentalguide_user_home", true);
            }

            if($matchedPath == 'wishlists'){
                $request->setControllerName("error");
                $request->setActionName("notfound");
            }

            if($matchedPath == 'reviews'){
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                return $redirector->gotoUrl("reviews/home",array('prependBase' => true));
            }

            if($matchedPath == 'community'){
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                return $redirector->gotoUrl("community/home",array('prependBase' => true));
            }
        }
    }
    public function getPageByName($name){
        $db = Engine_Db_Table::getDefaultAdapter();
        $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', $name)
            ->limit(1)
            ->query()
            ->fetchColumn();
        return $page_id;
    }
    protected function _creditManagePage()
    {
        $customPageId = $this->getPageByName("sdparentalguide_index_index");
        if(!empty($customPageId)){
            return;
        }
        //Install Page
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try{
            Engine_Api::_()->getApi("install","sdparentalguide")->addCredibilityPage();
            $db->commit();
        } catch (Exception $ex) {
            $db->rollBack();
        }

  }

  public function onItemDeleteBefore($event){
      $item = $event->getPayload();
      try{
          if($item instanceof Sdparentalguide_Model_Badge){
                $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
                $assignedTable->delete(array('badge_id = ?' => $item->getIdentity()));
          }
          if($item instanceof User_Model_User){
                $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
                $assignedTable->delete(array('user_id = ?' => $item->getIdentity()));
          }
          if(isset($item->gg_deleted) && !$item->gg_deleted){
                if( $item instanceof Ggcommunity_Model_Question && $item->approved && !$item->draft) {
                     $this->updateUserData($item->getOwner(),array('gg_question_count' => (--$item->getOwner()->gg_question_count)));
                }

                if( $item instanceof Ggcommunity_Model_Answer) {
                      $this->updateUserData($item->getOwner(),array('gg_answer_count' => (--$item->getOwner()->gg_answer_count)));
                }

                if( $item instanceof Sitereview_Model_Listing && $item->approved) {
                      $this->updateUserData($item->getOwner(),array('gg_review_count' => (--$item->getOwner()->gg_review_count)));
                }
          }
      } catch (Exception $ex) {
          //Silent
      }
  }
  public function onRenderLayoutAdmin($event,$simple = false){
       $view = $event->getPayload();
       if( !($view instanceof Zend_View_Interface) ) {
          return;
       }
       $cssBaseUrl = APPLICATION_ENV == 'development' ? rtrim($view->baseUrl(), '/') . '/' : $view->layout()->staticBaseUrl;
       $view->headLink()
            ->prependStylesheet($cssBaseUrl . 'externals/font-awesome/css/font-awesome.min.css');
  }
  public function onRenderLayoutAdminSimple($event){
      $this->onRenderLayoutAdmin($event, true);
  }

    public function onRenderLayoutDefault($event, $mode = null){
        $view = $event->getPayload();
        Engine_Api::_()->getDbTable("statistics","sdparentalguide")->logStatistics($view);
    }

    public function replaceListingHomeWidgets(){
        $listingTypesArray = Engine_Api::_()->sdparentalguide()->getListingTypesArray();
        if(count($listingTypesArray) <= 0){
            return;
        }
        $pageTable = Engine_Api::_()->getDbtable('pages', 'core');
        $pageTableName = $pageTable->info("name");
        $contentTable = Engine_Api::_()->getDbtable('content', 'core');
        $contentTableName = $contentTable->info("name");
        foreach($listingTypesArray as $id => $label){
            $pageName = "sitereview_index_home_listtype_$id";
            $pageId = $pageTable->select()->from($pageTableName,array('page_id'))->where('name = ?',$pageName)->query()->fetchColumn();
            if(empty($pageId)){
                continue;
            }
            $contents = $contentTable->fetchAll($contentTable->select()->where('page_id = ?',$pageId)->where('name IN (?)',array('sitereview.searchbox-sitereview','sitereview.pinboard-listings-sitereview')));
            if(count($contents) <= 0){
                continue;
            }
            foreach($contents as $content){
                if($content->name == "sitereview.searchbox-sitereview"){
                    $content->name = "sdparentalguide.searchbox-sitereview";
                    $content->save();
                }
                if($content->name == "sitereview.pinboard-listings-sitereview"){
                    $content->name = "sdparentalguide.pinboard-listings-sitereview";
                    $content->save();
                }
            }
        }
    }

    public function updateTransactionTopic($payload){
        $resource = Engine_Api::_()->getItem($payload->resource_type,$payload->resource_id);
        if(empty($resource)){
            return;
        }

        //Don't attach topic if
        if($resource->getType() == "core_comment" || $resource->getType() == "core_like" || $resource->getType() == "nestedcomment_dislike" || $resource->getType() == "ggcommunity_vote"){
            return;
        }
        if($resource->getType() == "ggcommunity_answer"){
            $question = $resource->getParent();
            if(empty($question)){
                return;
            }
            $payload->gg_topic_id = $question->topic_id;
        }
        if($payload->resource_type == "sitereview_listing"){
            $listingType = $resource->getListingType();
            if(empty($listingType)){
                return;
            }

            $payload->gg_topic_id = $listingType->gg_topic_id;
        }
        if(isset($resource->topic_id)){
            $payload->gg_topic_id = $resource->topic_id;
        }
        if(isset($resource->gg_topic_id)){
            $payload->gg_topic_id = $resource->gg_topic_id;
        }
//        $payload->save();
    }

    public function updateExistingTransactionTopic($resource){
        //Don't attach topic if
        if($resource->getType() == "core_comment" || $resource->getType() == "core_like" || $resource->getType() == "nestedcomment_dislike" || $resource->getType() == "ggcommunity_vote"){
            return;
        }
        $topic_id = 0;
        if($resource->getType() == "sitereview_listing"){
            $listingType = $resource->getListingType();
            if(empty($listingType)){
                return;
            }

            $topic_id = $listingType->gg_topic_id;
        }
        if($resource->getType() == "ggcommunity_answer"){
            $question = $resource->getParent();
            if(empty($question)){
                return;
            }
            $topic_id = $question->topic_id;
        }
        if(isset($resource->topic_id)){
            $topic_id = $resource->topic_id;
        }
        if(isset($resource->gg_topic_id)){
            $topic_id = $resource->gg_topic_id;
        }
        if(empty($topic_id)){
            return;
        }
        $creditTable = Engine_Api::_()->getItemTable('credit');
        $creditTable->update(array('gg_topic_id' => $topic_id),array('resource_type = ?' => $resource->getType(),'resource_id = ?' => $resource->getIdentity()));
    }

    public function onCreditCreateAfter($event){
        $payload = $event->getPayload();
        $user = Engine_Api::_()->user()->getUser($payload->user_id);
        $user->gg_activities = $user->gg_activities + 1;
        $user->gg_contribution = $user->gg_contribution + $payload->credit_point;

        $data = array();
        $data['gg_activities'] = $user->gg_activities + 1;
        $data['gg_contribution'] = $user->gg_contribution + $payload->credit_point;
        $api = Engine_Api::_()->sdparentalguide();
        $badge = $api->getUserBadge($payload->gg_contribution);
        if(!empty($badge)){
            $payload->gg_contribution_level = $badge->gg_contribution_level;
            $data['gg_contribution_level'] = $badge->gg_contribution_level;
            if(!$payload->isAdminOnly() && $badge->gg_level_id > 0){
                $payload->level_id = $badge->gg_level_id;
                $data['level_id'] = $badge->gg_level_id;
            }
            Zend_Registry::set("ContributionLevel_Saved",1);
        }
        Engine_Api::_()->getDbTable("users","user")->update($data,array('user_id = ?' => (int)$payload->user_id));
    }

    public function updateUserData($user,$data){
        if(empty($data) || empty($user)){
            return;
        }
        $viewer = Engine_Api::_()->user()->getViewer();

        $oldTz = date_default_timezone_get();
        date_default_timezone_set($viewer->timezone);
        $creationDate = time();
        date_default_timezone_set($oldTz);
        $currentDate = date('Y-m-d H:i:s', $creationDate);

        $data['gg_user_lastmodified'] = $viewer->getIdentity();
        $data['gg_dt_lastmodified'] = $currentDate;
        $db = Engine_Db_Table::getDefaultAdapter();
        $ipObj = new Engine_IP();
        $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));
        $data['gg_ip_lastmodified'] = $ipExpr;
        $data['gg_guid'] = $user->getGuid();
        $table = Engine_Api::_()->getDbtable('users','user');
        $table->update($data,array('user_id = ?' => (int)$user->getIdentity()));
    }
}