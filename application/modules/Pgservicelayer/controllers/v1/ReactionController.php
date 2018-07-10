<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_ReactionController extends Pgservicelayer_Controller_Action_Api
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
        
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity())
            $this->respondWithError('unauthorized');
        

        $db = $subject->likes()->getLikeTable()->getAdapter();
        $db->beginTransaction();

        try {
            //1 for positive reaction and 0 for negative reaction
            $reactionType = $this->getParam("reactionType",1);
            Engine_Api::_()->getApi("V1_Reaction","pgservicelayer")->toggleReaction($subject,$reactionType);
            $db->commit();
            $this->successResponseNoContent('no_content');            
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
        
    }
    
    public function putAction(){
        $this->postAction();
    }
    
    public function deleteAction(){
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity())
            $this->respondWithError('unauthorized');
        
        $db = $subject->likes()->getLikeTable()->getAdapter();
        $db->beginTransaction();
        try {
            //1 for positive reaction and 0 for negative reaction
            $reactionType = $this->getParam("reactionType",1);
            Engine_Api::_()->getApi("V1_Reaction","pgservicelayer")->removeReaction($subject,$reactionType);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
        $this->successResponseNoContent('no_content');        
    }
}
