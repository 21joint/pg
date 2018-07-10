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
                $select->where('type IN(?)', $contentTypes);
            }else{
                $contentType = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($contentType);
                $select->where('type = ?', $contentType);
            }            
        }else{
            $select->where('type IN(?)', $availableTypes);
        }
        
        if(!empty($search)){
            $sourceUrl = urldecode($this->getParam("source"));
            Engine_Api::_()->getDbTable('search', 'sdparentalguide')->logSearch($search,$sourceUrl);
            $db = $searchTable->getAdapter();
            $select->where('`title` LIKE ? OR `description` LIKE ? OR `keywords` LIKE ? OR `hidden` LIKE ?', "%".$search."%")
                ->order(new Zend_Db_Expr($db->quoteInto('MATCH(`title`, `description`, `keywords`, `hidden`) AGAINST (?) DESC', $search)));
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
}
