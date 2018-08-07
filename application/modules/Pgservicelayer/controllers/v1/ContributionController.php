<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_ContributionController extends Pgservicelayer_Controller_Action_Api
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
        $creditTable = Engine_Api::_()->getItemTable('credit');
        $creditTableName = $creditTable->info('name');
        $userTable = Engine_Api::_()->getDbTable('users', 'user');
        $userTableName = $userTable->info('name');
        $validityTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $validityTableName = $validityTable->info('name');

        $select = $creditTable->select()->setIntegrityCheck(false);
        $select->from($creditTableName);
        $select->join($userTableName, $userTableName . '.user_id = ' . $creditTableName . '.user_id', array("$userTableName.displayname","$userTableName.username","$userTableName.email"
                ,"$userTableName.level_id"));
        
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        if($coreSettings->getSetting('sitecredit.validity',0)){
            $validity = Engine_Api::_()->getDbtable('credits', 'sitecredit')->validityCheck();
            $select->join($validityTableName, $validityTableName . '.user_id = ' . $creditTableName . '.user_id', array("$validityTableName.start_date"));
            $select->where('DATE_ADD(start_date, INTERVAL ' . $validity . ' MONTH) >' . $creditTableName . '.creation_date')
                    ->where($creditTableName . '.creation_date > start_date');
        }
        if(!$viewer->isAdmin()){
            $select->where("$creditTableName.user_id = ?",(int)$viewer->getIdentity());
        }else{
            $memberID = $this->getParam("memberID",-1);
            if(!empty($memberID) && $memberID != -1){
                $select->where("$creditTableName.user_id = ?",(int)$memberID);
            }
        }
        $select->order("$creditTableName.credit_id DESC");
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        if($page > $paginator->count()){
            $this->respondWithSuccess($response);
        }
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        foreach($paginator as $credit){            
            $response['Results'][] = $responseApi->getContributionData($credit);
            $response['ResultCount']++;
        }
        $this->respondWithSuccess($response);
    }
}
