<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Api_V1_Reaction extends Sdparentalguide_Api_Core {
    public function toggleReaction(Core_Model_Item_Abstract $subject,$reactionType = 1){
        if(strtolower($reactionType) == "like" || strtolower($reactionType) == "upvote"){
            $reactionType = 1;
        }else if(strtolower($reactionType) == "dislike" || strtolower($reactionType) == "downvote"){
            $reactionType = 0;
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        switch ($subject->getType()){
            
            //Like/Dislike
            case "sitereview_listing":
            case "core_comment":
                $proxyObject = $subject->likes();
                $dislikesTable = Engine_Api::_()->getDbTable("dislikes","nestedcomment");
                //Handle Like
                if($reactionType == 1){
                    if($proxyObject->isLike($viewer)){ //If liked; remove like otherwise like and remove dislike
                        $proxyObject->removeLike($viewer);
                        if(!$dislikesTable->isDislike($subject,$viewer)){
                            $dislikesTable->addDislike($subject,$viewer);
                        }
                    }else{
                        $proxyObject->addLike($viewer);
                        if($dislikesTable->isDislike($subject,$viewer)){
                            $dislikesTable->removeDislike($subject,$viewer);
                        }
                        Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.likes');
                    }
                }
                //Handle Dislike
                else{
                    if($dislikesTable->isDislike($subject,$viewer)){
                        $dislikesTable->removeDislike($subject,$viewer);
                        if(!$proxyObject->isLike($viewer)){
                            $proxyObject->addLike($viewer);
                        }
                    }else{
                        $dislikesTable->addDislike($subject,$viewer);
                        if($proxyObject->isLike($viewer)){
                            $proxyObject->removeLike($viewer);
                        }
                    }
                }                
                break;
            
            //VoteUp/VoteDown
            case "ggcommunity_question":
            case "ggcommunity_answer":
                $proxyObject = Engine_Api::_()->getDbTable("votes","pgservicelayer")->votes($subject);
                $negativeVoteType = !$reactionType;
                if($proxyObject->isVote($viewer,$reactionType)){ //If voted; remove vote otherwise add vote and remove down vote
                    $proxyObject->removeVote($viewer,$reactionType);
                    if(!$proxyObject->isVote($viewer,$negativeVoteType)){
                        $proxyObject->addVote($viewer,$negativeVoteType);
                    }
                    
                    //Code for vote points
                    if($negativeVoteType == 1){
                        $this->addVoteActivity($subject);
                    }
                    
                    if($reactionType == 1){
                        $this->removeVoteActivity($subject);
                    }
                    
                }else{
                    if($proxyObject->isVote($viewer,$negativeVoteType)){
                        $proxyObject->removeVote($viewer,$negativeVoteType);
                    }
                    $proxyObject->addVote($viewer,$reactionType);
                    
                    //Code for vote points
                    if($reactionType == 1){
                        $this->addVoteActivity($subject);
                    }
                    
                    if($negativeVoteType == 1){
                        $this->removeVoteActivity($subject);
                    }
                }
                break;
            
            //Default: don't do anything
            default:
                break;
        }
    }
    
    public function removeReaction(Core_Model_Item_Abstract $subject,$reactionType = 1){
        if(strtolower($reactionType) == "like" || strtolower($reactionType) == "upvote"){
            $reactionType = 1;
        }else if(strtolower($reactionType) == "dislike" || strtolower($reactionType) == "downvote"){
            $reactionType = 0;
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        switch ($subject->getType()){
            
            //Like/Dislike
            case "sitereview_listing":
            case "core_comment":
                $proxyObject = $subject->likes();
                $dislikesTable = Engine_Api::_()->getDbTable("dislikes","nestedcomment");
                if($proxyObject->isLike($viewer)){
                    $proxyObject->removeLike($viewer);
                }
                if($dislikesTable->isDislike($subject,$viewer)){
                    $dislikesTable->removeDislike($subject,$viewer);
                }
                break;
                
            //VoteUp/VoteDown
            case "ggcommunity_question":
            case "ggcommunity_answer":
                $proxyObject = Engine_Api::_()->getDbTable("votes","pgservicelayer")->votes($subject);
                if($proxyObject->isVote($viewer,1)){
                    $proxyObject->removeVote($viewer,1);
                    $this->removeVoteActivity($subject);
                }
                if($proxyObject->isVote($viewer,0)){
                    $proxyObject->removeVote($viewer,0);
                }
                break;            
        }
    }
    
    public function hasActivity(Core_Model_Item_Abstract $object,$actionType = "question_answer_vote",Core_Model_Item_Abstract $subject = null){
        if(empty($subject)){
            $subject = Engine_Api::_()->user()->getViewer();
        }
        $table = Engine_Api::_()->getDbtable('actions', 'activity');
        $select = $table->select()
                ->where('type = ?',$actionType)
                ->where("object_type = ?",$object->getType())
                ->where("object_id = ?",$object->getIdentity())
                ->where("subject_type = ?",$subject->getType())
                ->where("subject_id = ?",$subject->getIdentity());
        return $table->fetchRow($select);
    }
    public function addVoteActivity(Core_Model_Item_Abstract $object){
        $mainActionType = "question_vote";
        $authorActionType = "question_author_vote";
        if($object->getType() == "ggcommunity_answer"){
            $mainActionType = "question_answer_vote";
            $authorActionType = "question_answer_author_vote";
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        if($viewer->isSelf($object->getOwner())){
            return;
        }
        if($this->hasActivity($object,$mainActionType)){
            return;
        }
        
        $actionOwner = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($object->getOwner(), $object, $authorActionType,null,array(
            'owner' => $object->getOwner()->getGuid(),
        ));
        if(!empty($actionOwner)){
            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($actionOwner, $object);
        }
        
        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $object, $mainActionType,null,array(
            'owner' => $object->getOwner()->getGuid(),
        ));
        if(!empty($action)){
            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $object);
        }
    }
    
    public function removeVoteActivity(Core_Model_Item_Abstract $object){
        $mainActionType = "question_vote";
        $authorActionType = "question_author_vote";
        if($object->getType() == "ggcommunity_answer"){
            $mainActionType = "question_answer_vote";
            $authorActionType = "question_answer_author_vote";
        }
        
        if(($actionOwner = $this->hasActivity($object,$authorActionType,$object->getOwner()))){
            $actionOwner->delete();
        }
        
        if(($action = $this->hasActivity($object,$mainActionType))){
            $action->delete();
        }
        
        
    }
}