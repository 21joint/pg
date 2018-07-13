<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_TopicController extends Pgservicelayer_Controller_Action_Api
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
        $id = $this->getParam("topicID");
        $search = $this->getParam("topicName");
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $tableName = $table->info("name");
        $select = $table->select();
        $select->order("topic_id DESC");
        $select->where('approved = ? ', 1);
        if(is_string($id) && !empty($id)){
            $select->where("$tableName.topic_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$tableName.topic_id IN (?)",$id);
        }
        if(!empty($search)){
            $select->where("name LIKE ?","%".$search."%");
        }
        $featured = $this->getParam("featured","-1");
        if($featured != -1){
            $select->where("$tableName.featured = ?",(int)$featured);
        }
        
        $select->where("$tableName.gg_deleted = ?",0);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        if($page > $paginator->count()){
            $this->respondWithSuccess($response);
        }
        foreach($paginator as $topic){
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($topic->getType());
            ++$response['ResultCount'];
            $response['Results'][] = $responseApi->getTopicData($topic);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        $this->respondWithError('invalid_method');
    }
    
    public function putAction(){
        $this->respondWithError('invalid_method');
    }
    
    public function deleteAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        $id = $this->getParam("topicID");
        $idsArray = (array)$id;
        if(is_string($id) && !empty($id)){
            $idsArray = array($id);
        }
        $topics = Engine_Api::_()->getItemMulti("sdparentalguide_topic",$idsArray);
        if (empty($topics)) {
            $this->respondWithError('no_record');
        }
        $table = Engine_Api::_()->getItemTable('sdparentalguide_topic');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try {
            foreach($topics as $topic){
                $poster = Engine_Api::_()->getItem("user", $topic->owner_id);
                if(!$poster->isSelf($viewer)){
                    $this->respondWithError('unauthorized');
                }
                $topic->gg_deleted = 1;
                $topic->save();
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        $this->successResponseNoContent('no_content');
    }
}
