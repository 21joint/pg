<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_UserController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        parent::init();
    }
    
    public function rankingAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        $contributionRangeType = $this->getParam("contributionRangeType","Overall");
        $orderBy = $this->getParam("orderBy","contributionPoints");

        $usersTable = Engine_Api::_()->getDbTable("users","user");
        $usersTableName = $usersTable->info("name");
        $creditsTable = Engine_Api::_()->getDbtable('credits','sitecredit');
        $creditsTableName = $creditsTable->info("name");
        $select = $usersTable->select()
            ->from($usersTable)
            ->setIntegrityCheck(false)
//            ->where("search = ?", 1)
//            ->where("enabled = ?", 1)
            ;
        $mvp = $this->getParam("mvp");
        if(!empty($mvp)){
            $select->where("gg_mvp = ?",1);
        }
        $expert = $this->getParam("expert");
        if(!empty($expert)){
            $select->where("gg_expert_bronze_count > ? OR gg_expert_silver_count > ? OR gg_expert_gold_count > ? OR gg_expert_platinum_count > ?",0);
        }

        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);

        $topicID = $this->getParam("topicID", 0);
        $checkFilter = false;

        //Contribution Range
        if(strtolower($contributionRangeType) == "week" || strtolower($contributionRangeType) == "month" || strtolower($contributionRangeType) == "today"){
            $maxDate = date("Y-m-d H:i:s",strtotime("-1 months"));
            $currentDate = date("Y-m-d H:i:s");
            if(strtolower($contributionRangeType) == "week"){
                $maxDate = date("Y-m-d H:i:s",strtotime("-1 week"));
            }
            if(strtolower($contributionRangeType) == "today"){
                $maxDate = date("Y-m-d H:i:s",strtotime("-1 day"));
            }
            if($orderBy == "contributionPoints"){
                $select->joinLeft($creditsTableName,"$creditsTableName.user_id = $usersTableName.user_id OR $creditsTableName.user_id IS NULL",array(new Zend_Db_Expr("SUM($creditsTableName.credit_point) as gg_contribution",
                        new Zend_Db_Expr("COUNT($creditsTableName.credit_id) as gg_activities"))))
                        ->group("$creditsTableName.user_id");
                $select->where("$creditsTableName.creation_date BETWEEN '$maxDate' AND '$currentDate'");
                if ( !empty( $topicID ) ) {
                    $select->where("$creditsTableName.gg_topic_id = ?", $topicID);
                }
                $checkFilter = true;
            }elseif($orderBy == 'reviewCount' || strtolower($orderBy) == 'reviews'){
                $listingTable = Engine_Api::_()->getDbtable('listings','sitereview');
                $listingTableName = $listingTable->info("name");
                $select->joinLeft($listingTableName,"$listingTableName.owner_id = $usersTableName.user_id",array(new Zend_Db_Expr("COUNT($listingTableName.listing_id) as gg_review_count")));
                $select->where("($listingTableName.creation_date BETWEEN '$maxDate' AND '$currentDate') OR $listingTableName.listing_id IS NULL")
                        ->where("$listingTableName.approved = ?",1)
                        ->where("$listingTableName.draft = ?",0)
                        ->group("$listingTableName.owner_id");
            }elseif($orderBy == 'followers'){
                $membershipTable = Engine_Api::_()->getDbtable('membership','user');
                $membershipTableName = $membershipTable->info("name");
                $select->joinLeft($membershipTableName,"$membershipTableName.resource_id = $usersTableName.user_id",array(new Zend_Db_Expr("COUNT($membershipTableName.user_id) as gg_followers_count")));
                $select->where("$membershipTableName.creation_date BETWEEN '$maxDate' AND '$currentDate'")
                        ->where("$membershipTableName.active = ?",1)
                        ->group("$membershipTableName.resource_id");
            }elseif($orderBy == 'questionCount' || strtolower($orderBy) == 'questions'){
                $questionsTable = Engine_Api::_()->getDbtable('questions','ggcommunity');
                $questionsTableName = $questionsTable->info("name");
                $select->joinLeft($questionsTableName,"$questionsTableName.user_id = $usersTableName.user_id",array(new Zend_Db_Expr("COUNT($questionsTableName.question_id) as gg_question_count")));
                $select->where("$questionsTableName.creation_date BETWEEN '$maxDate' AND '$currentDate'")
                        ->where("$questionsTableName.approved = ?",1)
                        ->where("$questionsTableName.draft = ?",0)
                        ->group("$questionsTableName.user_id");
            }
        }
        if ( !$checkFilter ) {
            $select->joinLeft($creditsTableName,"$creditsTableName.user_id = $usersTableName.user_id OR $creditsTableName.user_id IS NULL",array(new Zend_Db_Expr("SUM($creditsTableName.credit_point) as gg_contribution",
                    new Zend_Db_Expr("COUNT($creditsTableName.credit_id) as gg_activities"))))
                    ->group("$creditsTableName.user_id");            
        }
        if(!empty( $topicID )){
            $select->where("$creditsTableName.gg_topic_id = ?", $topicID);
        }
        
        //Sort data
        //Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
        if($orderBy == 'contributionPoints'){
            $select->order("gg_contribution DESC");
        }elseif($orderBy == 'reviewCount' || strtolower($orderBy) == 'reviews'){
            $select->order("gg_review_count DESC");
        }elseif($orderBy == 'questionCount' || strtolower($orderBy) == 'questions'){
            $select->order("gg_question_count DESC");
        }elseif($orderBy == 'followers'){
            $select->order("gg_followers_count DESC");
        }else{
            $select->order("gg_contribution DESC");
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        
        $response = array(
            'contributionRangeType' => $contributionRangeType,
            'orderBy' => $orderBy,
            'ResultCount' => 0,
            'Results' => array(),
        );
        
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        if($page > $paginator->count()){
            $this->respondWithSuccess($response);
        }
        foreach($paginator as $user){
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($user->getType());
            ++$response['ResultCount'];
            $response['Results'][] = $responseApi->getUserData($user);
        }
        $this->respondWithSuccess($response);
    }
    
    public function indexAction(){
        try{
            $method = strtolower($this->getRequest()->getMethod());
            if($method == 'get'){
                $this->getAction();
            }
            else if($method == 'post'){
                $this->respondWithError('invalid_method');
            }
            else if($method == 'put'){
                $this->putAction();
            }
            else if($method == 'delete'){
                $this->respondWithError('invalid_method');
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
        $this->validateParams(array('mvp','expert','memberID','page','limit'));
        $this->validateRequestMethod("GET");
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $usersTable = Engine_Api::_()->getDbTable("users","user");
        $usersTableName = $usersTable->info("name");
        $select = $usersTable->select()
//            ->where("search = ?", 1)
//            ->where("enabled = ?", 1)
            ->order("user_id DESC")
            ;
        $mvp = $this->getParam("mvp");
        if(!empty($mvp)){
            $select->where("gg_mvp = ?",1);
        }
        $expert = $this->getParam("expert");
        if(!empty($expert)){
            $select->where("gg_expert_bronze_count > ? OR gg_expert_silver_count > ? OR gg_expert_gold_count > ? OR gg_expert_platinum_count > ?",0);
        }
        $id = $this->getParam("memberID");
        if(is_string($id) && !empty($id)){
            $select->where("$usersTableName.user_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$usersTableName.user_id IN (?)",$id);
        }
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        if($page > $paginator->count()){
            $this->respondWithSuccess($response);
        }
        foreach($paginator as $user){
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($user->getType());
            ++$response['ResultCount'];
            $response['Results'][] = $responseApi->getUserData($user);
        }
        $this->respondWithSuccess($response);
    }
    
    public function putAction(){
        $id = $this->getParam("memberID");
        $user = Engine_Api::_()->user()->getUser($id);
        if(!$user->getIdentity()){
            $this->respondWithError('no_record');
        }
        
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try{
            $isPrivate = $this->getParam("isPrivate",-1);
            if($isPrivate == -1){
                $isPrivate = $user->search;
            }else{
                $isPrivate = !$isPrivate;
            }
            $user->setFromArray(array(
                'search' => (int)$isPrivate,
                'coverphoto' => (int)$this->getParam("coverPhotoID",$user->coverphoto),
                'photo_id' => (int)$this->getParam("avatarPhotoID",$user->photo_id)
            ));
            $coverPhotoId = $this->getParam("coverPhotoID");
            $coverPhotoPosition = $this->getParam("coverPhotoPosition");
            if(!empty($coverPhotoId) || !empty($coverPhotoPosition)){
                $user->coverphotoparams = Zend_Json_Encoder::encode($this->getParam('coverPhotoPosition', array('top' => '0', 'left' => 0)));
            }
            $user->save();
            
            $db->commit();
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $response['ResultCount'] = 1;
        $response['Results'] = array();
        $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($user->getType());
        $response['Results'][] = $responseApi->getUserData($user);
        $this->respondWithSuccess($response);
    }
}
