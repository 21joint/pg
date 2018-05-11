<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: VoteController.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_VoteController extends Core_Controller_Action_Standard
{
  //ACTION FOR DO VOTE
  public function voteAction()
	{
		//GET REQUEST DETAIL
    $feedback_id = (int) $this->_getParam('feedback_id');
    $viewer_id = (int) $this->_getParam('viewer_id');

    //DO ENTRY IN feedback_votes TABLE
    $voteTable = Engine_Api::_()->getItemTable('feedback_vote');
		$voteTableName =  $voteTable->info('name');
    
		//CODE FOR GET VOTE ID
    $vote_id = $voteTable->getFeedbackVoteId($viewer_id, $feedback_id);
    if(empty($vote_id)) {
    
			//MAKE DATABASE ENTRY FOR DOING VOTE
	    $vote = $voteTable->createRow();
	    $vote->voter_id = $viewer_id;
	    $vote->feedback_id = $feedback_id;
	    //$vote->total_votes++;
	    $vote->save();

	    //GET TOTAL VOTES 
	    $vote_total = $voteTable->countFeedbackVote($feedback_id);
	   	
      //UPDATE TOTAL VOTES IN FEEDBACK TABLE
			$feedback = Engine_Api::_()->getItem('feedback', $feedback_id);
			$feedback->total_votes = $vote_total;
			$feedback->save();
	   	    
	   	//CODE FOR GET VOTE ID
			$vote_id = $voteTable->getFeedbackVoteId($viewer_id, $feedback_id);
			
			//ASSIGN REMOVE VOTE LINK
	   	$remove_link = '<span><a href="javascript:void(0);" onclick="removevote(\'' . $vote_id . '\', \'' . $feedback_id . '\', \'' . $viewer_id . '\');">'.$this->view->translate('Remove').'</a></span>';
	   	    
			//SEND OTHER DETAIL
	   	$this->view->status = true;
	   	$this->view->abc = $remove_link;
	   	$this->view->total =  $vote_total;
    }
    else {

    	//GET TOTAL VOTES 
	    $vote_total = $voteTable->countFeedbackVote($feedback_id);
	   	    
      //UPDATE TOTAL VOTES IN FEEDBACK TABLE
			$feedback = Engine_Api::_()->getItem('feedback', $feedback_id);
			$feedback->total_votes = $vote_total;
			$feedback->save();
	   	    
	   	//CODE FOR GET VOTE ID
			$vote_id = $voteTable->getFeedbackVoteId($viewer_id, $feedback_id);
			
			//ASSIGN REMOVE VOTE LINK
			$remove_link = '<span><a href="javascript:void(0);" onclick="removevote(\'' . $vote_id . '\', \'' . $feedback_id . '\', \'' . $viewer_id . '\');">'.$this->view->translate('Remove').'</a></span>';
					
			//SEND OTHER DETAIL
			$this->view->status = true;
			$this->view->abc = $remove_link;
			$this->view->total =  $vote_total;
    }
	}
	
	//ACTION FOR REMOVE VOTE
	public function removevoteAction()
	{
		//GET REQUEST DETAIL
    $vote_id = (int) $this->_getParam('vote_id');
    $feedback_id = (int) $this->_getParam('feedback_id');
    $viewer_id = (int) $this->_getParam('viewer_id');
        
    //DELETE VOTE ENTRY FROM DATABASE
    $revote = Engine_Api::_()->getItem('feedback_vote', $vote_id);
    $revote->delete();
        
    //GET TOTAL VOTES
    $vote_total = Engine_Api::_()->getItemTable('feedback_vote')->countFeedbackVote($feedback_id);
   	   	    
    //UPDATE TOTAL VOTES IN FEEDBACK TABLE
    $feedback = Engine_Api::_()->getItem('feedback', $feedback_id);
    $feedback->total_votes = $vote_total;
    $feedback->save();  
          
		//ASSIGN VOTE LINK
   	$vote_link =  '<span><a href="javascript:void(0);" onclick="vote(\'' . $viewer_id . '\', \'' . $feedback_id . '\');">'.$this->view->translate('Vote').'</a></span>';

		//SEND OTHER DETAIL
   	$this->view->status = true;
   	$this->view->total =   $vote_total;
   	$this->view->abc = $vote_link;
	}
}
