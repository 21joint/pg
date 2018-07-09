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
        $select = $usersTable->select()
            ->where("search = ?", 1)
            ->where("enabled = ?", 1)
            ;
        
        //Contribution Range
        if(strtolower($contributionRangeType) == "week" || strtolower($contributionRangeType) == "month"){
//            $creditsTable = Engine_Api::_()->getDbtable('credits','sitecredit');
//            $creditsTableName = $creditsTable->info("name");
//            $select->joinLeft($creditsTableName,"$creditsTableName.user_id = $usersTableName.user_id",array())
//                    ->group("$usersTableName.user_id");
//            $maxDate = date("Y-m-d H:i:s",strtotime("+1 months"));
//            if(strtolower($contributionRangeType) == "week"){
//                $maxDate = date("Y-m-d H:i:s",strtotime("+1 week"));
//            }
            
        }
        //Sort data
        //Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
        if($orderBy == 'contributionPoints'){
            $select->order("gg_contribution DESC");
        }elseif($orderBy == 'reviewCount'){
            $select->order("gg_review_count DESC");
        }elseif($orderBy == 'questionCount'){
            $select->order("gg_question_count DESC");
        }elseif($orderBy == 'followers'){
            $select->order("gg_followers_count DESC");
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->getParam("limit",10));
        $paginator->setCurrentPageNumber($this->getParam("page",1));
        
        $response = array(
            'contributionRangeType' => $contributionRangeType,
            'orderBy' => $orderBy,
            'Results' => array(),
        );
        $api = Engine_Api::_()->sdparentalguide();
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $response['ResultCount'] = $paginator->getTotalItemCount();
        foreach($paginator as $user){
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($user->getType());
            $response['Results'][] = $responseApi->getUserData($user);
        }
        $this->respondWithSuccess($response);
    }
    
    public function indexAction(){
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
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->getParam("limit",10));
        $paginator->setCurrentPageNumber($this->getParam("page",1));
        
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['Results'] = array();
        foreach($paginator as $user){
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($user->getType());
            $response['Results'][] = $responseApi->getUserData($user);
        }
        $this->respondWithSuccess($response);
    }
}
