<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_QuestionController extends Pgservicelayer_Controller_Action_Api
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
        $id = $this->getParam("questionID");
        $search = $this->getParam("topicName");
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbtable('questions', 'ggcommunity');
        $tableName = $table->info("name");
        $select = $table->select();
        $select->order("question_id DESC");
        $select->where('approved = ? ', $this->getParam("approved",1));
        $closed = $this->getParam("closed","-1");
        if($closed != -1){
            $select->where("$tableName.open = ?",(!(int)$closed));
        } 
        
        $featured = $this->getParam("featured","-1");
        if($featured != -1){
            $select->where("$tableName.featured = ?",(int)$featured);
        }     
        
        $answerChosen = $this->getParam("answerChosen","-1");
        if($answerChosen != -1){
            $select->where("$tableName.accepted_answer = ?",(int)$answerChosen);
        }   
        
        $topicID = $this->getParam("topicID","-1");
        if($topicID != -1){
//            $select->where("$tableName.gg_topic_id = ?",(int)$topicID);
        }
        
        $authorID = $this->getParam("authorID","-1");
        if($authorID != -1){
            $select->where("$tableName.user_id = ?",(int)$authorID);
        }
        
        if(is_string($id) && !empty($id)){
            $select->where("$tableName.question_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$tableName.question_id IN (?)",$id);
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
        foreach($paginator as $question){
            $response['resourceType'] = $question->getType();
            $response['Results'][] = $responseApi->getQuestionData($question);
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
        $id = $this->getParam("questionID");
        $idsArray = (array)$id;
        if(is_string($id) && !empty($id)){
            $idsArray = array($id);
        }
        $questions = Engine_Api::_()->getItemMulti("ggcommunity_question",$idsArray);
        if (empty($questions)) {
            $this->respondWithError('no_record');
        }
        $table = Engine_Api::_()->getItemTable('ggcommunity_question');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try {
            foreach($questions as $question){
                $poster = Engine_Api::_()->getItem("user", $question->user_id);
                if(!$poster->isSelf($viewer)){
                    $this->respondWithError('unauthorized');
                }
                $question->gg_deleted = 1;
                $question->save();
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        $this->successResponseNoContent('no_content');
    }
}
