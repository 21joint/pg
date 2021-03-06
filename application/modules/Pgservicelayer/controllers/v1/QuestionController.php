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
        if(!$this->pggPermission('canViewQuestion')){
            $this->respondWithError('unauthorized');
        }
        
        $id = $this->getParam("questionID");
        $search = $this->getParam("topicName");
        $orderBy = $this->getParam("orderBy","createdDateTime");
        $orderByDirection = $this->getParam("orderByDirection","descending");
        $orderByDirection = (strtolower($orderByDirection) == "descending")?"DESC":"ASC";
        
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbtable('questions', 'ggcommunity');
        $tableName = $table->info("name");
        $select = $table->select()->from($table->info("name"),array("*",new Zend_Db_Expr("(up_vote_count-down_vote_count) as total_vote_count")));
        $select->where('approved = ? ', $this->getParam("approved",1));
        $closed = $this->getParam("closed","-1");
        if($closed != -1){
            $select->where("$tableName.open = ?",(!(int)$closed));
        } 
        
        $featured = $this->getParam("featured","-1");
        if($featured != -1){
            $select->where("$tableName.featured = ?",(int)$featured);
        }
        
        $approved = $this->getParam("approved","-1");
        if($approved != -1){
            $select->where("$tableName.approved = ?",(int)$approved);
        }
        
        $answerChosen = $this->getParam("answerChosen","-1");
        if($answerChosen != -1){
            $select->where("$tableName.accepted_answer = ?",(int)$answerChosen);
        }   
        
        $topicID = $this->getParam("topicID","-1");
        if($topicID != -1){
            $select->where("$tableName.topic_id = ?",(int)$topicID);
        }
        
        $status = $this->getParam("status","published");
        if(!empty($status)){
            $status = (strtolower($status) == "draft")?1:0;
            $select->where("$tableName.draft = ?",(int)$status);
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
        
        if($orderBy == "createDateTime"){
            $select->order("creation_date $orderByDirection");
        }else if($orderBy == "publishedDateTime"){
            $select->order("approved_date $orderByDirection");
        }else if($orderBy == "lastModifiedDateTime"){
            $select->order("modified_date $orderByDirection");
        }else if($orderBy == "closedDateTime"){
            $select->order("date_closed $orderByDirection");
        }else if($orderBy == "viewCount"){
            $select->order("view_count $orderByDirection");
        }else if($orderBy == "commentsCount"){
            $select->order("comment_count $orderByDirection");
        }else if($orderBy == "answerCount"){
            $select->order("answer_count $orderByDirection");
        }else if($orderBy == "totalVoteCount"){
            $select->order("total_vote_count $orderByDirection");
        }else{
            $select->order("creation_date $orderByDirection");
        }
        
        $filterInfluencers = $this->getParam("filterInfluencers",false);
        if($filterInfluencers){
            $influencers = Engine_Api::_()->pgservicelayer()->getInfluencers();
            if(!empty($influencers)){
                $select->where("$tableName.user_id IN (?)",$influencers);
            }
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
        foreach($paginator as $question){
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($question->getType());
            ++$response['ResultCount'];
            $response['Results'][] = $responseApi->getQuestionData($question);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = !empty($viewer_id) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        if (empty($viewer_id)) {
            $this->respondWithError('unauthorized');
        }
        
        if(!$this->pggPermission('canCreateQuestion')){
            $this->respondWithError('unauthorized');
        }
        $can_approve = (int)$this->pggPermission('canApproveQuestion');
        $can_change = (int)$this->pggPermission('canChangeCloseDate');
        
        $form = Engine_Api::_()->getApi("V1_Forms","pgservicelayer")->getQuestionForm();
        $validators = Engine_Api::_()->getApi("V1_Validators","pgservicelayer")->getQuestionValidators();
        
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
            'title' => $this->getParam("title"),
            'topic_id' => $this->getParam("topicID"),
            'body' => $this->getParam("body"),
            'photo_id' => (int)$this->getParam("photoID"),
            'draft' => (int)$this->getParam("draft",0),
            'date_closed' => $this->getParam("closedDateTime")
        );
        $table = Engine_Api::_()->getDbTable('questions','ggcommunity');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            
            $oldTz = date_default_timezone_get();
            date_default_timezone_set($viewer->timezone);
            $creationDate = time();
            date_default_timezone_set($oldTz);
            $currentDate = date('Y-m-d H:i:s', $creationDate);
            
            if($can_approve == 1) {
                $values['approved'] = 1;
                $values['approved_date'] = $currentDate;
            } else {
                $values['approved'] = 0;
            }
            $values['user_id'] = $viewer_id;
            
            if($can_change != 1 || empty($values['date_closed'])) {
                $automatically_close = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.automatically.close');
                $date = date('Y-m-d H:i:s');
                $closed_date = date('Y-m-d H:i:s', strtotime($date . " + ". $automatically_close." day")); 
                $values['date_closed'] = $closed_date;
            }
            
            if($values['draft'] == 1) {
                $values['date_closed'] = NULL;
                $values['approved'] = 0;
                $values['search'] = 0;
                $values['open'] = 0;
            } else {
                $values['search'] = 1;
                $values['open'] = 1;
            }
            $question = $table->createRow(); 
            $question->setFromArray($values);
            $question->save();
            
            $question->creation_date = $currentDate;
            $question->save();
            
            
            if($question->approved && !$question->draft){
                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $question, "question_create");
                if(!empty($action)){
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $question);
                }
            }
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $response['ResultCount'] = 1;
            $response['Results'] = array();
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($question->getType());
            $response['Results'][] = $responseApi->getQuestionData($question);
            $this->respondWithSuccess($response);
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
    }
    
    public function putAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = !empty($viewer_id) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        if (empty($viewer_id)) {
            $this->respondWithError('unauthorized');
        }
        
        $questionID = $this->getParam("questionID");
        $question = Engine_Api::_()->getItem("ggcommunity_question",$questionID);
        if(empty($question) || $question->gg_deleted){
            $this->respondWithError('no_record');
        }
        if(!$this->pggPermission('canEditQuestion')){
            $this->respondWithError('unauthorized');
        }
        if(!$question->isOwner($viewer) && !$this->pggPermission('canEditOthersQuestion')){
            $this->respondWithError('unauthorized');
        }
        
        $can_approve = (int)$this->pggPermission('canApproveQuestion');
        $can_change = (int)$this->pggPermission('canChangeCloseDate');
        
        $form = Engine_Api::_()->getApi("V1_Forms","pgservicelayer")->getQuestionForm();
        $validators = Engine_Api::_()->getApi("V1_Validators","pgservicelayer")->getQuestionValidators();
        
        $values = $data = array(
            'title' => $this->getParam("title",$question->getTitle()),
            'topicID' => $this->getParam("topicID",$question->topic_id),
            'body' => $this->getParam("body",$question->body),
            'photoID' => $this->getParam("photoID",$question->photo_id),
            'draft' => (int)$this->getParam("draft",0),
        );;

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
            'title' => $this->getParam("title",$question->getTitle()),
            'topic_id' => $this->getParam("topicID",$question->topic_id),
            'body' => $this->getParam("body",$question->body),
            'photo_id' => $this->getParam("photoID",$question->photo_id),
            'draft' => (int)$this->getParam("draft",$question->draft),
            'approved' => (int)$this->getParam("approved",$question->approved),
            'date_closed' => $this->getParam("closedDateTime")
        );
        $table = Engine_Api::_()->getDbTable('questions','ggcommunity');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            
            if(($can_change != 1 || empty($values['date_closed'])) && $question->date_closed == "0000-00-00 00:00:00") {
                $automatically_close = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.automatically.close');
                $date = date('Y-m-d H:i:s');
                $closed_date = date('Y-m-d H:i:s', strtotime($date . " + ". $automatically_close." day")); 
                $values['date_closed'] = $closed_date;
            }
            if(empty($values['date_closed'])){
                unset($values['date_closed']);
            }
            $oldTz = date_default_timezone_get();
            date_default_timezone_set($viewer->timezone);
            $creationDate = time();
            date_default_timezone_set($oldTz);
            $currentDate = date('Y-m-d H:i:s', $creationDate);
            
            if(!empty($values['approved']) && empty($question->approved)) {
                $values['approved_date'] = $currentDate;
            }            
            if($values['draft'] == 1) {
                $values['date_closed'] = NULL;
                $values['approved'] = 0;
                $values['search'] = 0;
                $values['open'] = 0;
            } else {
                $values['search'] = 1;
                $values['open'] = 1;
            }
            $question->setFromArray($values);
            $question->save();
            
            $api = Engine_Api::_()->getApi("V1_Reaction","pgservicelayer");
            if(!$question->approved || $question->draft){
                $question->deletePoints(0); //Delete points but keep answers.
            }
            
            if($question->approved && !$question->draft && !$api->hasActivity($question,'question_create',$question->getOwner())){
                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($question->getOwner(), $question, "question_create");
                if(!empty($action)){
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $question);
                }
            }
            
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $response['ResultCount'] = 1;
            $response['Results'] = array();
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($question->getType());
            $response['Results'][] = $responseApi->getQuestionData($question);
            $this->respondWithSuccess($response);
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
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
        if (empty($questions) || count($questions) <= 0) {
            $this->respondWithError('no_record');
        }
        $table = Engine_Api::_()->getItemTable('ggcommunity_question');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try {
            foreach($questions as $question){
                $poster = Engine_Api::_()->getItem("user", $question->user_id);
                if(!$this->pggPermission('canDeleteQuestion')){
                    $this->respondWithError('unauthorized');
                }

                if(!$question->isOwner($viewer) && !$this->pggPermission('canDeleteOthersQuestion')){
                    $this->respondWithError('unauthorized');
                }
                $question->gg_deleted = 1;
                $question->save();
                if($question->approved && !$question->draft) {
                    Engine_Api::_()->pgservicelayer()->updateUserCount(array('gg_question_count' => (--$poster->gg_question_count)),$question->user_id);                    
                }
                $question->deletePoints();
            }            
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($e);
        }
        $this->successResponseNoContent('no_content');
    }
}
