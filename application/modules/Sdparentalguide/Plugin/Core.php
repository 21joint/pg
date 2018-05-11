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
        $auditingSaved = Zend_Registry::isRegistered("Auditing_Created");
        try{
            //This will fix max level error
            if($auditingSaved && Zend_Registry::get("Auditing_Created") > 20){
                return;
            }
                        
            $this->addHashtagListingMapping($payload);
            
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
    public function onItemCreateBefore($event){
        $payload = $event->getPayload();
        $viewer = Engine_Api::_()->user()->getViewer();
        $auditingSaved = Zend_Registry::isRegistered("Auditing_Created");
        try{
            //This will fix max level error
            if($auditingSaved && Zend_Registry::get("Auditing_Created") > 20){
                return;
            }
            if($this->isAllowedAuditing($payload)){
                $this->updateAuditing($payload);
                $payload->gg_user_created = $viewer->getIdentity();
                $payload->gg_dt_created = date("Y-m-d H:i:s");
            }
            
            if( $payload instanceof Sitecredit_Model_Credit ) {
                $this->updateUserCredits($payload);
            }
            
            if( $payload instanceof Sitereview_Model_Listing ) {
                $owner = $payload->getOwner();
                if(!empty($owner)){
                    if($owner->gg_featured){
                        $payload->featured = 1;
                    }
                }
            }
            
            if( $payload instanceof Sitehashtag_Model_Tag ) {
                $this->addHashtagTopic($payload);
            }
            
            if( $payload instanceof Core_Model_Tag ) {
                $this->addHashtagTopic($payload);
            }
            
            if( $payload instanceof Core_Model_TagMap ) {
                $this->addListingMapping($payload);
            }
            
            if( $payload instanceof Sitehashtag_Model_Tagmap ) {
                $this->addHashtagListingMapping($payload);
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
        $auditingSaved = Zend_Registry::isRegistered("Auditing_Created");
        try{
            //This will fix max level error
            if($auditingSaved && Zend_Registry::get("Auditing_Created") > 20){
                return;
            }
            if( $payload instanceof Sitereview_Model_Listing ) {
                $this->mapListingTopics($payload);
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
    public function updateUserCredits($payload){
        try{
            $user = Engine_Api::_()->user()->getUser($payload->user_id);
            if(!$user->getIdentity()){
                return;
            }
            $creditsTable = Engine_Api::_()->getDbtable('credits','sdparentalguide');
            $row = $creditsTable->getUserActivityCount($user);
            $userCredits = 0;
            $userActivities = 0;
            if(!empty($row)){
                $userCredits = $row->credit;
                $userActivities = $row->activities;
            }
            $user->gg_credibility = $userCredits;
            $user->gg_activities = $userActivities;
            $user->gg_credibility_updated = 1;
            $user->save();
            
        } catch (Exception $ex) {
            //Silent
        }
    }
    public function onItemUpdateAfter($event){
        try{
            $payload = $event->getPayload();
            $viewer = Engine_Api::_()->user()->getViewer();
            $auditingSaved = Zend_Registry::isRegistered("Auditing_Saved");
            if(!empty($auditingSaved)){
                return;
            }
            //This will fix max level error by saving data mannually.
            if($this->isAllowedAuditing($payload)) {
                $params = $this->getAuditingParams($payload);
                $params['gg_user_lastmodified'] = $viewer->getIdentity();
                $params['gg_dt_lastmodified'] = date("Y-m-d H:i:s");
                $table = $payload->getTable();
                $info = $table->info("primary");
                if(empty($info) || !is_array($info)){
                    return;
                }
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
                Zend_Registry::set("Auditing_Saved",1);
            }
        } catch (Exception $ex) {
            //Silent
        }
        Zend_Registry::set("Auditing_Saved",1);
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
        
        $searchText = $request->getParam("query");
        if(!empty($searchText) && $moduleName == "core" && $controllerName == "search" && $actionName == "index"){
            Engine_Api::_()->getDbTable('search', 'sdparentalguide')->logSearch($searchText);
        }
        
        if(!empty($searchText) && $moduleName == "siteadvsearch" && $controllerName == "index" && $actionName == "index"){
            Engine_Api::_()->getDbTable('search', 'sdparentalguide')->logSearch($searchText);
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
      if($item instanceof Sdparentalguide_Model_Badge){
          $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
          $assignedTable->delete(array('badge_id = ?' => $item->getIdentity()));
      }
      if($item instanceof User_Model_User){
          $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
          $assignedTable->delete(array('user_id = ?' => $item->getIdentity()));
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
    public function addHashtagTopic($payload){
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        try {
            $name = str_replace("#","",$payload['text']);
            if($table->checkTopic($name)){
                return;
            }
            
            $topic = $table->createTagTopic($name);            
            $payload->topic_id = $topic->topic_id;
//            $payload->save();
            
        } catch( Exception $e ) {
          //Silent
//          throw $e;
        }
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
    
    public function addListingMapping($payload){
        if($payload->resource_type != "sitereview_listing" || $payload->tag_type != "core_tag"){
            return;
        }        
        $coreTag = Engine_Api::_()->getItem("core_tag",$payload->tag_id);
        if(empty($coreTag)){
            return;
        }
        $table = Engine_Api::_()->getDbTable("listingTopics","sdparentalguide");
        $table->addListingTopic($coreTag->topic_id,$payload->resource_id);
    }
    
    public function addHashtagListingMapping($payload){
        if(!isset($payload->action_id) || empty($payload->action_id) || empty($payload->body) || $payload->object_type != "sitereview_listing"){
            return;
        }
        if($payload->type != "comment_sitereview_listing"){
            return;
        }
        
        $tabMapTable = Engine_Api::_()->getDbtable('tagmaps', 'sitehashtag');
        $tagMap = $tabMapTable->fetchRow($tabMapTable->select()->where('action_id = ?',$payload->action_id));
        if(empty($tagMap)){
            return;
        }
        $table = Engine_Api::_()->getDbTable("listingTopics","sdparentalguide");
        $hashTag = Engine_Api::_()->getItem("sitehashtag_tag",$tagMap->tag_id);
        if(empty($hashTag)){
            return;
        }
        $table->addListingTopic($hashTag->topic_id,$payload->object_id);
    }
    
    public function mapListingTopics($payload){
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $select = $table->select()->where('listingtype_id = ?',$payload->listingtype_id);
        if(!empty($payload->category_id)){
            $select->where("category_id = ?",$payload->category_id);
        }
        if(!empty($payload->subcategory_id)){
            $select->where("subcategory_id = ?",$payload->subcategory_id);
        }
        $topics = $table->fetchAll($select);
        if(count($topics) <= 0){
            return;
        }
        $listingTopicTable = Engine_Api::_()->getDbTable("listingTopics","sdparentalguide");
        foreach($topics as $topic){
            $listingTopicTable->addListingTopic($topic->topic_id,$payload->getIdentity());
            $topic->listing_count++;
            $topic->save();
        }
    }
}