<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_AnswerController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        parent::init();
        
        $resourceType = $this->getParam("resourceType","ggcommunity_question");
        $resourceId = $this->getParam("questionID");
        if(empty($resourceType) || empty($resourceId)){
            $this->respondWithError('no_record');
        }
        
        if(!Engine_Api::_()->hasItemType($resourceType)){
            $this->respondWithError('no_record');
        }
        
        $subject = Engine_Api::_()->getItem($resourceType,$resourceId);
        if(empty($subject) || !$subject->getIdentity()){
            $this->respondWithError('no_record');
        }
        
        if(!Engine_Api::_()->core()->hasSubject()){
            Engine_Api::_()->core()->setSubject($subject);
        }
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
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        if (!($subject instanceof Core_Model_Item_Abstract)){
            $this->respondWithError('no_record');
        }
        
        $id = $this->getParam("questionID");
        $search = $this->getParam("topicName");
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
        $tableName = $table->info("name");
        $select = $table->select();
        $select->order("answer_id DESC");
        $approved = $this->getParam("approved","-1");
        if($approved != -1){
            $select->where("$tableName.approved = ?",(!(int)$approved));
        }  
        
        $answerChosen = $this->getParam("answerChosen","-1");
        if($answerChosen != -1){
            $select->where("$tableName.accepted = ?",(int)$answerChosen);
        }
        
        $authorID = $this->getParam("authorID","-1");
        if($authorID != -1){
            $select->where("$tableName.user_id = ?",(int)$authorID);
        }
        
        if(is_string($id) && !empty($id)){
            $select->where("$tableName.parent_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$tableName.parent_id IN (?)",$id);
        }
        if(!empty($search)){
            $select->where("title LIKE ?","%".$search."%");
        }
        $select->where("$tableName.gg_deleted = ?",0);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['Results'] = array();
        foreach($paginator as $answer){
            $response['resourceType'] = $answer->getType();
            $response['Results'][] = $responseApi->getAnswerData($answer,$subject);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        
    }
    
    public function putAction(){
        
    }
    
    public function deleteAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        $id = $this->getParam("answerID");
        $idsArray = (array)$id;
        if(is_string($id) && !empty($id)){
            $idsArray = array($id);
        }
        $answers = Engine_Api::_()->getItemMulti("ggcommunity_answer",$idsArray);
        if (empty($answers)) {
            $this->respondWithError('no_record');
        }
        $table = Engine_Api::_()->getItemTable('ggcommunity_answer');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try {
            foreach($answers as $answer){
                $poster = Engine_Api::_()->getItem("user", $answer->user_id);
                if(!$poster->isSelf($viewer)){
                    $this->respondWithError('unauthorized');
                }
                $answer->gg_deleted = 1;
                $answer->save();
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        $this->successResponseNoContent('no_content');
    }
}
