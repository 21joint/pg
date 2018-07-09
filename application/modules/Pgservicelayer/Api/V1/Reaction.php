<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Api_V1_Reaction extends Sdparentalguide_Api_Core {
    public function toggleReaction(Core_Model_Item_Abstract $subject,$voteType = 1){
        $viewer = Engine_Api::_()->user()->getViewer();
        switch ($subject->getType()){
            case "sitereview_listing":
            case "core_comment":
                $proxyObject = $subject->likes();
                $dislikesTable = Engine_Api::_()->getDbTable("dislikes","nestedcomment");
                if($proxyObject->isLike($viewer)){
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
                break;
            case "ggcommunity_question":
            case "ggcommunity_answer":
                $proxyObject = Engine_Api::_()->getDbTable("votes","pgservicelayer")->votes($subject);
                $negativeVoteType = !$voteType;
                if($proxyObject->isVote($viewer,$voteType)){
                    $proxyObject->removeVote($viewer,$voteType);                    
                }else{
                    if($proxyObject->isVote($viewer,$negativeVoteType)){
                        $proxyObject->removeVote($viewer,$negativeVoteType);
                    }
                    $proxyObject->addVote($viewer,$voteType);                    
                }
                break;
            
        }
    }
    
    public function removeReaction(Core_Model_Item_Abstract $subject,$voteType = 1){
        $viewer = Engine_Api::_()->user()->getViewer();
        switch ($subject->getType()){
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
            case "ggcommunity_question":
            case "ggcommunity_answer":
                $proxyObject = Engine_Api::_()->getDbTable("votes","pgservicelayer")->votes($subject);
                if($proxyObject->isVote($viewer,1)){
                    $proxyObject->removeVote($viewer,1);
                }
                if($proxyObject->isVote($viewer,0)){
                    $proxyObject->removeVote($viewer,0);
                }
                break;            
        }
    }
}