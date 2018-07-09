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
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);
        $searchTable = Engine_Api::_()->getDbTable("search","core");
        $select = $searchTable->select();
        $contentType = $this->getParam("contentType");
        $search= $this->getParam("search");
        $availableTypes = Engine_Api::_()->getItemTypes();
        $topicID = $this->getParam("topicID");
        if(is_string($topicID) && !empty($topicID)){
            $contentType = "sdparentalguide_topic";
            $select->where("id = ?",$topicID);
        }else if(is_string($topicID) && !empty($topicID)){
            $contentType = "sdparentalguide_topic";
            $select->where("id IN (?)",$topicID);
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
}
