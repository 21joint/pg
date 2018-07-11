<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_SearchController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        parent::init();
    }
    
    public function indexAction(){
        try{
            $method = strtolower($this->getRequest()->getMethod());
            $fixSearchDates = $this->getParam("fixSearchDates");
            if(!empty($fixSearchDates)){
                $this->fixSearchDates();
            }
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
                $this->attachTopicsToSearch();
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
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);
        $searchTable = Engine_Api::_()->getDbTable("search","core");
        $searchTableName = $searchTable->info("name");
        $select = $searchTable->select();
        $contentType = $this->getParam("contentType");
        $search= $this->getParam("search");
        $availableTypes = Engine_Api::_()->getItemTypes();
        $topicID = $this->getParam("topicID");
        if(is_string($topicID) && !empty($topicID)){
            $select->where("topic_id = ?",$topicID);
        }else if(is_array($topicID) && !empty($topicID)){
            $select->where("topic_id IN (?)",$topicID);
        }
        
        if(!empty($contentType)){
            if(is_array($contentType)){
                $contentTypes = array();
                foreach($contentType as $type){
                    $contentTypes[] = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($type);
                }
                $select->where($searchTableName.'.type IN(?)', $contentTypes);
            }else{
                $contentType = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($contentType);
                $select->where($searchTableName.'.type = ?', $contentType);
            }            
        }else{
            $select->where($searchTableName.'.type IN(?)', $availableTypes);
        }
        
        $orderBy = $this->getParam("orderBy");
        if(empty($orderBy) && empty($search)){
            $orderBy = "createdDateTime";
        }
        $orderByDirection = $this->getParam("orderByDirection","descending");
        $orderByDirection = (strtolower($orderByDirection) == "descending")?"DESC":"ASC";
        if($orderBy == "createdDateTime"){
            $select->order("$searchTableName.creation_date $orderByDirection");
        }
        
        if(!empty($search)){
            $sourceUrl = urldecode($this->getParam("source"));
            Engine_Api::_()->getDbTable('search', 'sdparentalguide')->logSearch($search,$sourceUrl);
            $db = $searchTable->getAdapter();
            $topicsTable = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
            $topicsTableName = $topicsTable->info("name");
            
            if(!empty($contentType) && is_string($contentType) && ($contentType == "sitereview_listing" || $contentType == "ggcommunity_question")){
                $select->from($searchTableName)->setIntegrityCheck(false)
                        ->joinLeft($topicsTableName,"$topicsTableName.topic_id = $searchTableName.topic_id OR $topicsTableName.topic_id IS NULL",array())
                        ->where("$searchTableName.title LIKE ? OR $searchTableName.description LIKE ? OR $searchTableName.keywords LIKE ? OR $searchTableName.hidden LIKE ?"
                                . " OR $topicsTableName.name LIKE ? OR $topicsTableName.name_plural LIKE ?", "%".$search."%");
            }else{
                $select->where("$searchTableName.title LIKE ? OR $searchTableName.description LIKE ? OR $searchTableName.keywords LIKE ? OR $searchTableName.hidden LIKE ?", "%".$search."%")
                        ;
            }
            
            if($orderBy != "createdDateTime"){
                $select->order(new Zend_Db_Expr($db->quoteInto("MATCH($searchTableName.title, $searchTableName.description, $searchTableName.keywords, $searchTableName.hidden) AGAINST (?) $orderByDirection", $search)));
            }
        }
                        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['Results'] = array();
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        foreach($paginator as $key => $item){
            $searchItemData = $responseApi->getSearchItemData($item);
            if(empty($searchItemData)){
                if(Engine_Api::_()->hasItemType($item->type)){
                    $searchTable->delete(array(
                        'type = ?' => $item->type,
                        'id = ?' => $item->id,
                    ));
                }
                $response['ResultCount'] = $response['ResultCount'] - 1;
                continue;
            }
            $searchItemData['searchRank'] = ++$key;
            $response['Results'][] = $searchItemData;
        }
        $this->respondWithSuccess($response);
    }
    
    public function attachTopicsToSearch(){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);
        $searchTable = Engine_Api::_()->getDbTable("search","core");
        $select = $searchTable->select()
                ->where('type IN (?)',array('ggcommunity_question','sdparentalguide_badge','sitereview_listing'))
                ->where("topic_id = ?",0);
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        foreach($paginator as $item){
            if(!Engine_Api::_()->hasItemType($item->type)){
                continue;
            }
            $itemObject = Engine_Api::_()->getItem($item->type,$item->id);
            if(empty($itemObject)){
                continue;
            }
            $topic_id = 0;
            if($item->type == "sitereview_listing"){
                $listingType = $itemObject->getListingType();
                if(empty($listingType)){
                    continue;
                }
                $topic_id = $listingType->gg_topic_id;
            }else if($item->type == "sdparentalguide_badge" || $item->type == "ggcommunity_question"){
                $topic_id = $itemObject->topic_id;
            }
            if(!empty($topic_id)){
                $item->topic_id = $topic_id;
                $item->save();
            }
        }
        
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['NextPage'] = $paginator->getCurrentPageNumber()+1;
        if($paginator->getCurrentPageNumber() >= $paginator->count()){
            $response['NextPage'] = 0;
            return;
        }
        $response['Results'] = array();
        $this->respondWithSuccess($response);
    }
    
    public function fixSearchDates(){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);
        $searchTable = Engine_Api::_()->getDbTable("search","core");
        $select = $searchTable->select()
                ->where("creation_date IS NULL");
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        
        foreach($paginator as $item){
            if(!Engine_Api::_()->hasItemType($item->type)){
                continue;
            }
            $itemObject = Engine_Api::_()->getItem($item->type,$item->id);
            if(empty($itemObject)){
                $item->creation_date = date("Y-m-d H:i:s");
                $item->modified_date = date("Y-m-d H:i:s");
                $item->save();
                continue;
            }
            
            if(isset($itemObject->gg_dt_created)){
                $item->creation_date = $itemObject->gg_dt_created;
            }
            
            if(isset($itemObject->gg_dt_lastmodified)){
                $item->modified_date = $itemObject->gg_dt_lastmodified;
            }
            
            if(isset($itemObject->creation_date) && empty($item->creation_date)){
                $item->creation_date = $itemObject->creation_date;
            }
            
            if(isset($itemObject->modified_date) && empty($item->modified_date)){
                $item->modified_date = $itemObject->modified_date;
            }
            
            if(empty($item->creation_date)){
                $item->creation_date = date("Y-m-d H:i:s");
            }
            
            if(empty($item->modified_date)){
                $item->modified_date = date("Y-m-d H:i:s");
            }
            $item->save();            
        }
        
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['NextPage'] = $paginator->getCurrentPageNumber()+1;
        $response['totalPages'] = $paginator->count();
        if($paginator->getCurrentPageNumber() >= $paginator->count()){
            $response['NextPage'] = 0;
            return;
        }
        $response['Results'] = array();
        $this->respondWithSuccess($response);
        
    }
}
