<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_BadgeController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        parent::init();
    }
    
    public function indexAction(){
        $this->validateRequestMethod();
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        try{
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
            $tableName = $table->info("name");    
            $select = $table->select()->setIntegrityCheck(false)->from($tableName);
            $select->where("active = ?",1);
            $select->where("profile_display = ?",1);
            $select->order("badge_id DESC");
            $id = $this->getParam("badgeID");
            if(is_string($id) && !empty($id)){
                $select->where("$tableName.badge_id = ?",$id);
            }else if(is_array($id) && !empty ($id)){
                $select->where("$tableName.badge_id IN (?)",$id);
            }
            
            $topicID = $this->getParam("topicID");
            if(is_string($topicID) && !empty($topicID)){
                $select->where("$tableName.topic_id = ?",$topicID);
            }else if(is_array($topicID) && !empty ($topicID)){
                $select->where("$tableName.topic_id IN (?)",$topicID);
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
            foreach($paginator as $badge){
                $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($badge->getType());
                ++$response['ResultCount'];
                $response['Results'][] = $responseApi->getBadgeData($badge);
            }
            $this->respondWithSuccess($response);
        } catch (Exception $ex) {
            $this->respondWithServerError($ex);
        }        
    }
    
    public function memberbadgeAction(){
        $this->validateRequestMethod();
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        try{
            $this->validateParams(array('memberID','badgeID','page','limit'));
            $id = $this->getParam("memberID");
            if(empty($id)){
                $this->respondWithError("parameter_missing",$this->translate("Missing memberID parameter."));
            }
            
            $subject = Engine_Api::_()->user()->getUser($id);
            if(!$subject || !$subject->getIdentity()){
                $this->respondWithError("parameter_missing",$this->translate("Invalid memberID parameter."));
            }
            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
            $tableName = $table->info("name");    
            $select = $table->select()->setIntegrityCheck(false)->from($tableName);
            $select->where("active = ?",1);
            $select->where("profile_display = ?",1);
            $select->order("badge_id DESC");
            
            $badgeID = $this->getParam("badgeID");
            if(is_string($badgeID) && !empty($badgeID)){
                $select->where("$tableName.badge_id = ?",$badgeID);
            }else if(is_array($badgeID) && !empty ($badgeID)){
                $select->where("$tableName.badge_id IN (?)",$badgeID);
            }
            
            $topicID = $this->getParam("topicID");
            if(is_string($topicID) && !empty($topicID)){
                $select->where("$tableName.topic_id = ?",$topicID);
            }else if(is_array($topicID) && !empty ($topicID)){
                $select->where("$tableName.topic_id IN (?)",$topicID);
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
            foreach($paginator as $badge){
                $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($badge->getType());
                ++$response['ResultCount'];
                $response['Results'][] = $responseApi->getMemberBadgeData($badge,$subject);
            }
            $this->respondWithSuccess($response);
        } catch (Exception $ex) {
            $this->respondWithServerError($ex);
        }
    }
}
