<?php
class Ggcommunity_VoteController extends Core_Controller_Action_Standard
{
 
    public function init()
    {
        // Get viewer
        $viewer = Engine_Api::_()->user()->getViewer();
        $parent_type = $this->_getParam('parent_type');
        $parent_id = $this->_getParam('parent_id', $this->_getParam('parent_id', null));
    
        if(!$parent_id) return;
        $subject = null; 
    
        if(!Engine_Api::_()->core()->hasSubject() ) {
        
            $item = Engine_Api::_()->getItem(strval($parent_type), $parent_id);
            if( $item ) {
                Engine_Api::_()->core()->setSubject($item);
            }
        }
    
        // check for subject
        if (!$this->_helper->requireSubject()->isValid()) return;

    }

    public function voteAction() {
        // get viewer and subject
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();

        $vote_type = $this->_getParam('vote_type', null);

        /* * check if this user already voted for this subject (check if exist vote for this subject and user)
           * if this viewer vote for first time for this subject create new row in votes table
           * f exist vote for this specific user and viewer and vote type for that vote is same with current vote just return(make sure that error wont appear)
           * if exist vote for this specific user and subjec but type is different than current vote type then update that vote type
        */

        $vote = Engine_Api::_()->ggcommunity()->getVote($subject, $viewer);
      
        if(!$vote) {
           
            $new_vote = Engine_Api::_()->ggcommunity()->addVote($subject, $viewer, $vote_type);

            $update_vote_count = Engine_Api::_()->ggcommunity()->updateVoteCount($subject, $vote_type, null);
           
            
        } else {

            if($vote->vote_type == $vote_type) {
                return;
            } else {
            
                $update_vote = Engine_Api::_()->ggcommunity()->updateVote($vote);

                $update_vote_count = Engine_Api::_()->ggcommunity()->updateVoteCount($subject, $vote_type, $vote);

            }
        }

       
    }
  

 
}
