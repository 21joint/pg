<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_GuideitemController extends Pgservicelayer_Controller_Action_Api
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
                $this->requireSubject();
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
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbTable("views","pgservicelayer");
        $select = $table->select()->order("action_id DESC");
        $actionType = $this->getParam("actionType");
        if(!empty($actionType)){
            $select->where("action_type = ?",strtolower($actionType));
        }
        
        $contentType = $this->getParam("contentType");
        $contentType = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($contentType);
        if(!empty($contentType)){
            $select->where("conent_type = ?",$contentType);
        }
        
        $contentID = $this->getParam("contentID");
        if(!empty($contentID)){
            $select->where("content_id = ?",$contentID);
        }
        $actionID = $this->getParam("actionID");
        if(!empty($actionID)){
            $select->where("action_id = ?",$actionID);
        }
        $memberID = $this->getParam("memberID");
        if(!empty($memberID)){
            $select->where("owner_id = ?",$memberID);
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        $response['contentType'] = "";
        if($page > $paginator->count()){
            $this->respondWithSuccess($response);
        }
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        foreach($paginator as $view){
            ++$response['ResultCount'];
            $response['Results'][] = $responseApi->getViewData($view);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!($subject instanceof Core_Model_Item_Abstract) || !$subject->getIdentity() )
            $this->respondWithError('no_record');
        
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        
        $table = Engine_Api::_()->getDbTable("views","pgservicelayer");
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            $actionType = $this->getParam("actionType");
            if(strtolower($actionType) != "click" && strtolower($actionType) != "view" && strtolower($actionType) != "pause"){
                $this->respondWithError('unauthorized',$this->translate("Invalid actionType passed."));
            }
            $view = $table->addView($subject,$actionType);
            $view->save();
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $response['ResultCount'] = 1;
            $response['contentType'] = "";
            $response['Results'] = array();
            $response['Results'][] = $responseApi->getViewData($view);
            $this->respondWithSuccess($response);
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
        
    }
}
