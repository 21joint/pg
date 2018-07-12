<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_ActionController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        parent::init();
        
        $this->requireSubject();
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
        if (!($subject instanceof Core_Model_Item_Abstract) || !$subject->getIdentity() )
            $this->respondWithError('no_record');
        
        $table = Engine_Api::_()->getDbTable("views","pgservicelayer");
        $select = $table->select();
        $paginator = Zend_Paginator::factory($select);
        
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['Results'] = array();
        $response['contentType'] = "";
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        foreach($paginator as $view){            
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
            $actionType = $this->getParam("actionType","click");
            if(empty($actionType)){
                $actionType = "click";
            }
            $view = $table->addView($subject,$actionType);
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
