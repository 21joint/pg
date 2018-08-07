<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_PermissionController extends Pgservicelayer_Controller_Action_Api
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
                $this->respondWithError('invalid_method');
            }
            else if($method == 'put'){
                $this->respondWithError('invalid_method');
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
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $userTable = Engine_Api::_()->getDbTable('users', 'user');
        $userTableName = $userTable->info('name');

        $select = $userTable->select();
        if(!$viewer->isAdmin()){
            $select->where("$userTableName.user_id = ?",(int)$viewer->getIdentity());
        }else{
//            $memberID = $this->getParam("memberID",-1);
//            if(!empty($memberID) && $memberID != -1){
//                $select->where("$userTableName.user_id = ?",(int)$memberID);
//            }
            $select->where("$userTableName.user_id = ?",(int)$viewer->getIdentity());
        }
        $select->order("$userTableName.user_id DESC");
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        if($page > $paginator->count()){
            $this->respondWithSuccess($response);
        }
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        foreach($paginator as $user){
            $response['Results'][] = $responseApi->getPermissionData($user);
            $response['ResultCount']++;
        }
        $this->respondWithSuccess($response);
    }
}
