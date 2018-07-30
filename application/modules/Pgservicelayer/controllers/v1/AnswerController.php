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
        
        $resourceType = $this->getParam("contentType","ggcommunity_question");
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
        $answerID = $this->getParam("answerID");
        $search = $this->getParam("topicName");
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
        $tableName = $table->info("name");
        $select = $table->select()->from($table->info("name"),array("*",new Zend_Db_Expr("(up_vote_count-down_vote_count) as total_vote_count")));
        $approved = $this->getParam("approved","-1");
        if($approved != -1){
            $select->where("$tableName.approved = ?",(!(int)$approved));
        }  
        
        $answerChosen = $this->getParam("answerChosen","-1");
        if($answerChosen != -1){
            $select->where("$tableName.accepted = ?",(int)$answerChosen);
        }
        
        $authorID = $this->getParam("memberID",$this->getParam("authorID","-1"));
        if($authorID != -1){
            $select->where("$tableName.user_id = ?",(int)$authorID);
        }
        
        if(is_string($answerID) && !empty($answerID)){
            $select->where("$tableName.answer_id = ?",$answerID);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$tableName.answer_id IN (?)",$answerID);
        }
        
        if(is_string($id) && !empty($id)){
            $select->where("$tableName.parent_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$tableName.parent_id IN (?)",$id);
        }
        
        $orderBy = $this->getParam("orderBy","createdDateTime");
        $orderByDirection = $this->getParam("orderByDirection","descending");
        $orderByDirection = (strtolower($orderByDirection) == "descending")?"DESC":"ASC";
        if($orderBy == "createdDateTime"){
            $select->order("creation_date $orderByDirection");
        }else if($orderBy == "totalVoteCount"){
            $select->order("total_vote_count $orderByDirection");
        }else if($orderBy == "commentsCount"){
            $select->order("comment_count $orderByDirection");
        }else{
            $select->order("answer_id $orderByDirection");
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
        foreach($paginator as $answer){
            $response['resourceType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($answer->getType());
            ++$response['ResultCount'];
            $response['Results'][] = $responseApi->getAnswerData($answer,$subject);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
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
        
        if(!Engine_Api::_()->authorization()->isAllowed('ggcommunity', null, 'answer_question')){
            $this->respondWithError('unauthorized');
        }
        
        $body = $this->getParam("body");
        if(empty($body)){
            $this->respondWithValidationError('validation_fail', array(
                'body' => $this->translate("Please complete this field - it is required.")
            ));
        }
        
        $table = Engine_Api::_()->getDbTable('answers', 'ggcommunity');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
           
            $answer = $table->createRow();
            $answer->user_id = $viewer->getIdentity();
            $answer->parent_type = $subject->getType();
            $answer->parent_id = $subject->getIdentity();
            $answer->body = $body;

            $answer->save();
            
            $oldTz = date_default_timezone_get();
            date_default_timezone_set($viewer->timezone);
            $creationDate = time();
            date_default_timezone_set($oldTz);
            $currentDate = date('Y-m-d H:i:s', $creationDate);
            $answer->creation_date = $currentDate;
            $answer->save();
            
            $answerChosen = $this->getParam("answerChosen");
            if(!empty($answerChosen) && ($subject->user_id = $viewer->getIdentity() || $viewer->isAdminOnly())){
                $choosenAnswer = $subject->getChoosenAnswer();
                $table->update(array('accepted' => 0),array('parent_id = ?' => $subject->getIdentity(),'parent_type = ?' => $subject->getType()));
                $answer->accepted = 1;
                $answer->save();
                
                $subject->accepted_answer = 1;
                $subject->save();
                
                if(!empty($choosenAnswer)){
                    $choosenAction = $choosenAnswer->getChoosenActivity();
                    if(!empty($choosenAction)){
                        $choosenAction->delete();
                    }
                }
            }
            
            $subject->answer_count = $subject->answer_count+1;
            $subject->save();
            
            if(!$viewer->isSelf($subject->getOwner())){
                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $answer, "question_answer",null,array(
                    'owner' => $subject->getOwner()->getGuid(),
                    'body' => $body,
                ));
                if(!empty($action)){
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $answer);
                }
                
                $actionOwner = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($subject->getOwner(), $answer, "question_author_answer",null,array(
                    'owner' => $subject->getOwner()->getGuid(),
                    'body' => $body,
                ));
                if(!empty($actionOwner)){
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($actionOwner, $answer);
                }
            }
            
            $db->commit();            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $response['ResultCount'] = 1;
            $response['Results'] = array();
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($answer->getType());
            $response['Results'][] = $responseApi->getAnswerData($answer);
            $this->respondWithSuccess($response);

        } catch(Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    public function putAction(){
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
        
        if(!Engine_Api::_()->authorization()->isAllowed('ggcommunity', null, 'answer_question')){
            $this->respondWithError('unauthorized');
        }
        
        $answerID = $this->getParam("answerID");
        $answer = Engine_Api::_()->getItem("ggcommunity_answer",$answerID);
        if(empty($answer)){
            $this->respondWithError('no_record');
        }
        
        $body = $this->getParam("body",$answer->body);
        if(empty($body)){
            $this->respondWithValidationError('validation_fail', array(
                'body' => $this->translate("Please complete this field - it is required.")
            ));
        }
        
        $table = Engine_Api::_()->getDbTable('answers', 'ggcommunity');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
           
//            $answer->user_id = $viewer->getIdentity();
            $answer->parent_type = $subject->getType();
            $answer->parent_id = $subject->getIdentity();
            $answer->body = $body;
            $answer->save();
            
            $answerChosen = $this->getParam("answerChosen");
            if(!empty($answerChosen) && ($answer->user_id == $viewer->getIdentity() || $viewer->isAdmin())){
                $table->update(array('accepted' => 0),array('parent_id = ?' => $subject->getIdentity(),'parent_type = ?' => $subject->getType()));
                $answer->accepted = 1;
                $answer->save();
                
                $subject->accepted_answer = 1;
                $subject->save();
                
                if(!$viewer->isSelf($answer->getOwner())){
                    $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($answer->getOwner(), $answer, "question_answer_chosen",array(
                        'owner' => $subject->getOwner()->getGuid(),
                        'body' => $body,
                    ));
                    if(!empty($action)){
                        Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $answer);
                    }
                }
            }
            
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $response['ResultCount'] = 1;
            $response['Results'] = array();
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($answer->getType());
            $response['Results'][] = $responseApi->getAnswerData($answer);
            $this->respondWithSuccess($response);

        } catch(Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    public function deleteAction(){
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
        
        $id = $this->getParam("answerID");
        $idsArray = (array)$id;
        if(is_string($id) && !empty($id)){
            $idsArray = array($id);
        }
        $answers = Engine_Api::_()->getItemMulti("ggcommunity_answer",$idsArray);
        if (empty($answers) || count($answers) <= 0) {
            $this->respondWithError('no_record');
        }
        $table = Engine_Api::_()->getItemTable('ggcommunity_answer');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try {
            foreach($answers as $answer){
                $poster = Engine_Api::_()->getItem("user", $answer->user_id);
                if(!$poster->isSelf($viewer) && !$viewer->isAdmin()){
                    $this->respondWithError('unauthorized');
                }
                $answer->gg_deleted = 1;
                $answer->save();
                
                $subject->answer_count = $subject->answer_count - 1;
                $answer->deletePoints();
            }
            $subject->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($e);
        }
        $this->successResponseNoContent('no_content');
    }
}
