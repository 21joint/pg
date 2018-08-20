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
                (!method_exists($subject, 'likes')) || $subject->gg_deleted)
            $this->respondWithError('no_record');
        
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity())
            $this->respondWithError('unauthorized');
        

        $db = $subject->likes()->getLikeTable()->getAdapter();
        $db->beginTransaction();

        try {
            //1 for positive reaction and 0 for negative reaction
            $reactionType = $this->getParam("reactionType");
            if(empty($reactionType) || ($reactionType != "upvote" && $reactionType != "downvote" && $reactionType != "like" && $reactionType != "dislike")){
                $this->respondWithError('unauthorized',$this->translate("Invalid reaction type."));
            }
            if(strtolower($reactionType) == "like" || strtolower($reactionType) == "dislike"){
                if($subject->getType() == "ggcommunity_answer" || $subject->getType() == "ggcommunity_question"){
                    $this->respondWithError('unauthorized',$this->translate("You cannot like/dislike this entity."));
                }
            }else if(strtolower($reactionType) == "upvote" || strtolower($reactionType) == "downvote"){
                if($subject->getType() != "ggcommunity_answer" && $subject->getType() != "ggcommunity_question"){
                    $this->respondWithError('unauthorized',$this->translate("You cannot vote this entity."));
                }
            }
            
            //Permissions
            if(strtolower($reactionType) == "like" || strtolower($reactionType) == "dislike"){
                if($subject->getType() == "sitereview_listing"){
                    if(!$this->pggPermission('canLikeReview')){
                        $this->respondWithError('unauthorized');
                    }
                }
                
                if($subject->getType() == "sdparentalguide_guide"){
                    if(!$this->pggPermission('canLikeGuide')){
                        $this->respondWithError('unauthorized');
                    }
                }
                
                if($subject->getType() == "core_comment"){
                    if(!$this->pggPermission('canLikeComments')){
                        $this->respondWithError('unauthorized');
                    }
                }
            }
            
            if(strtolower($reactionType) == "upvote" || strtolower($reactionType) == "downvote"){
                if($subject->getType() == "ggcommunity_question"){
                    if(!$this->pggPermission('canVoteQuestion')){
                        $this->respondWithError('unauthorized');
                    }
                }
                if($subject->getType() == "ggcommunity_answer"){
                    if(!$this->pggPermission('canVoteAnswer')){
                        $this->respondWithError('unauthorized');
                    }
                }
            }
            
            Engine_Api::_()->getApi("V1_Reaction","pgservicelayer")->toggleReaction($subject,$reactionType);            
            $db->commit();
            $totalCount = 1;
            if(strtolower($reactionType) == "like" || strtolower($reactionType) == "dislike"){
                $dislikesTable = Engine_Api::_()->getDbTable("dislikes","nestedcomment");
                $totalCount = $subject->likes()->getLikeCount() - $dislikesTable->dislikes($subject)->getDislikeCount();
            }else if(strtolower($reactionType) == "upvote" || strtolower($reactionType) == "downvote"){
                $totalCount = $subject->up_vote_count - $subject->down_vote_count;
            }
            $this->respondWithSuccess(array('totalCount' => $totalCount));            
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($e);
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
